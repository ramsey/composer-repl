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
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Ramsey\Dev\Repl\Process\ProcessFactory;

use function dirname;
use function realpath;

/**
 * Composer plugin providing REPL functionality
 */
class ReplPlugin implements Capable, CommandProvider, PluginInterface
{
    private static Composer $composer;

    private ProcessFactory $processFactory;
    private string $repoRoot;

    public function __construct()
    {
        $composerFile = (string) Factory::getComposerFile();

        $this->repoRoot = (string) realpath(dirname($composerFile));
        $this->processFactory = new ProcessFactory();
    }

    /**
     * @return array<string, string>
     */
    public function getCapabilities(): array
    {
        return [
            CommandProvider::class => self::class,
        ];
    }

    /**
     * @return BaseCommand[]
     */
    public function getCommands(): array
    {
        return [
            new ReplCommand($this->repoRoot, $this->processFactory, self::$composer),
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        self::$composer = $composer;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
