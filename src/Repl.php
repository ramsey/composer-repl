<?php

/**
 * This file is part of ramsey/composer-repl
 *
 * ramsey/composer-repl is open source software: you can distribute
 * it and/or modify it under the terms of the MIT License
 * (the "License"). You may not use this file except in
 * compliance with the License.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Ramsey\Dev\Repl;

use Composer\Composer;
use PHPUnit\Framework\TestCase;
use Psy\Configuration;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Psy\ElephpantCommand;
use Ramsey\Dev\Repl\Psy\PhpunitRunCommand;
use Ramsey\Dev\Repl\Psy\PhpunitTestCommand;

use function getenv;
use function sprintf;

/**
 * Customizes and controls an instance of PsySH Shell
 */
class Repl
{
    private string $repositoryRoot;
    private ProcessFactory $processFactory;
    private Composer $composer;
    private bool $isInteractive;

    public function __construct(
        string $repositoryRoot,
        ProcessFactory $processFactory,
        Composer $composer,
        bool $isInteractive = true
    ) {
        $this->repositoryRoot = $repositoryRoot;
        $this->processFactory = $processFactory;
        $this->composer = $composer;
        $this->isInteractive = $isInteractive;
    }

    public function run(): int
    {
        $shell = new Shell($this->getPsyConfig());
        $shell->setScopeVariables($this->getScopeVariables());
        $shell->add(new PhpunitTestCommand());
        $shell->add(new PhpunitRunCommand(
            $this->repositoryRoot,
            $this->processFactory,
            $this->composer,
        ));
        $shell->add(new ElephpantCommand());

        return $shell->run();
    }

    private function getPsyConfig(): Configuration
    {
        $config = new Configuration([
            'startupMessage' => $this->getStartupMessage(),
            'colorMode' => Configuration::COLOR_MODE_FORCED,
            'updateCheck' => 'never',
            'useBracketedPaste' => true,
            'defaultIncludes' => $this->getDefaultIncludes(),
        ]);

        if ($this->isInteractive === false) {
            $config->setInteractiveMode(Configuration::INTERACTIVE_MODE_DISABLED);
        }

        return $config;
    }

    private function getStartupMessage(): string
    {
        $startupMessage = <<<'EOD'
            ------------------------------------------------------------------------
            <info>Welcome to the development console (REPL) for %s.</info>
            <fg=cyan>To learn more about what you can do in PsySH, type `help`.</>
            ------------------------------------------------------------------------
            EOD;

        $packageName = $this->composer->getPackage()->getPrettyName();

        return sprintf($startupMessage, $packageName);
    }

    /**
     * @return array{
     *     env: array<string, string>,
     *     phpunit: TestCase,
     * }
     */
    private function getScopeVariables(): array
    {
        return [
            'env' => getenv(),
            'phpunit' => $this->getPhpUnitTestCase(),
        ];
    }

    /**
     * @return string[]
     */
    private function getDefaultIncludes(): array
    {
        /** @var string[] $includes */
        $includes = $this->composer->getPackage()->getExtra()['ramsey/composer-repl']['includes'] ?? [];

        return $includes;
    }

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private function getPhpUnitTestCase(): TestCase
    {
        return new class extends TestCase {
        };
    }
}
