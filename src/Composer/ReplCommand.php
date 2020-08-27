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

namespace Ramsey\Dev\Repl\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Composer\Config;
use PHPUnit\Framework\TestCase;
use Psy\Configuration;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Psy\ElephpantCommand;
use Ramsey\Dev\Repl\Psy\PhpunitRunCommand;
use Ramsey\Dev\Repl\Psy\PhpunitTestCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function getenv;
use function sprintf;

/**
 * Composer command to launch a PsySH REPL
 */
class ReplCommand extends BaseCommand
{
    private string $repositoryRoot;
    private ProcessFactory $processFactory;
    private bool $isInteractive;

    public function __construct(
        string $repositoryRoot,
        ProcessFactory $processFactory,
        Composer $composer,
        bool $isInteractive = true
    ) {
        parent::__construct(null);

        $this->repositoryRoot = $repositoryRoot;
        $this->processFactory = $processFactory;
        $this->setComposer($composer);
        $this->isInteractive = $isInteractive;
    }

    protected function configure(): void
    {
        $this
            ->setName('repl')
            ->setDescription('Launches a development console (REPL) for PHP.')
            ->setAliases(['shell']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Config::disableProcessTimeout();
        $this->loadEnvironment();

        $config = new Configuration([
            'startupMessage' => $this->getStartupMessage(),
            'colorMode' => Configuration::COLOR_MODE_FORCED,
            'updateCheck' => 'never',
            'useBracketedPaste' => true,
        ]);

        if ($this->isInteractive === false) {
            $config->setInteractiveMode(Configuration::INTERACTIVE_MODE_DISABLED);
        }

        /** @var Composer $composer */
        $composer = $this->getComposer(true);

        $shell = new Shell($config);
        $shell->setScopeVariables($this->getScopeVariables());
        $shell->add(new PhpunitTestCommand());
        $shell->add(new PhpunitRunCommand(
            $this->repositoryRoot,
            $this->processFactory,
            $composer,
        ));
        $shell->add(new ElephpantCommand());

        return $shell->run();
    }

    private function loadEnvironment(): void
    {
        /** @var Composer $composer */
        $composer = $this->getComposer(true);

        $vendorDir = (string) $composer->getConfig()->get('vendor-dir');

        /** @psalm-suppress UnresolvableInclude */
        require $vendorDir . '/autoload.php';
    }

    private function getStartupMessage(): string
    {
        $startupMessage = <<<'EOD'
            ------------------------------------------------------------------------
            <info>Welcome to the development console (REPL) for %s.</info>
            <fg=cyan>To learn more about what you can do in PsySH, type `help`.</>
            ------------------------------------------------------------------------
            EOD;

        /** @var Composer $composer */
        $composer = $this->getComposer(true);

        $packageName = (string) $composer->getPackage()->getPrettyName();

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
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private function getPhpUnitTestCase(): TestCase
    {
        return new class extends TestCase {
        };
    }
}
