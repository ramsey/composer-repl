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

use function realpath;

/**
 * Composer plugin providing REPL functionality
 */
class ReplPlugin implements Capable, CommandProvider, PluginInterface
{
    private ProcessFactory $processFactory;
    private string $repoRoot;

    /**
     * @var mixed[]
     */
    private array $capabilityArgs = [];

    /**
     * Composer\Plugin\PluginManager::getPluginCapability() passes an array to
     * the constructor of the capability class, which is this class. We don't
     * use this argument, but it's provided here to avoid errors.
     *
     * @link https://github.com/composer/composer/blob/a5e608fb73f8eeff8f3acc6fb938c15e5310efcb/src/Composer/Plugin/PluginManager.php#L473-L474
     *
     * @param mixed[] $args
     */
    public function __construct(array $args = [], ?ProcessFactory $processFactory = null)
    {
        $composerFile = (string) Factory::getComposerFile();

        $this->capabilityArgs = $args;
        $this->repoRoot = (string) realpath(dirname($composerFile));
        $this->processFactory = $processFactory ?? new ProcessFactory();
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
            new ReplCommand($this->repoRoot, $this->processFactory),
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
