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

namespace Ramsey\Dev\Repl\Psy;

use Composer\Composer;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function array_merge;
use function count;
use function implode;

use const DIRECTORY_SEPARATOR;

/**
 * PsySH command to allow running the PHPUnit CLI from the development console
 */
class PhpunitRunCommand extends ContextAwareCommand
{
    private string $repositoryRoot;
    private ProcessFactory $processFactory;
    private Composer $composer;

    public function __construct(
        string $repositoryRoot,
        ProcessFactory $processFactory,
        Composer $composer
    ) {
        parent::__construct(null);

        $this->repositoryRoot = $repositoryRoot;
        $this->processFactory = $processFactory;
        $this->composer = $composer;
    }

    protected function configure(): void
    {
        $this
            ->setName('phpunit:run')
            ->setAliases(['phpunit'])
            ->setDefinition([
                new InputArgument(
                    'tests',
                    InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                    'Space-separated list of tests to run.',
                    [],
                ),
                new InputOption(
                    'group',
                    'g',
                    InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                    'Only run tests from the specified group(s).',
                    [],
                ),
                new InputOption(
                    'exclude-group',
                    'x',
                    InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                    'Exclude tests from the specified group(s).',
                    [],
                ),
                new InputOption(
                    'testsuite',
                    's',
                    InputOption::VALUE_REQUIRED,
                    'Filter which testsuite to run.',
                ),
                new InputOption(
                    'filter',
                    'f',
                    InputOption::VALUE_REQUIRED,
                    'Filter which tests to run.',
                ),
                new InputOption(
                    'list-groups',
                    '',
                    InputOption::VALUE_NONE,
                    'List available test groups.',
                ),
                new InputOption(
                    'list-suites',
                    '',
                    InputOption::VALUE_NONE,
                    'List available test suites.',
                ),
                new InputOption(
                    'list-tests',
                    '',
                    InputOption::VALUE_NONE,
                    'List available tests.',
                ),
                new InputOption(
                    'testdox',
                    '',
                    InputOption::VALUE_NONE,
                    'Report test progress in TestDox format.',
                ),
            ])
            ->setDescription('Run PHPUnit tests from the dev console.')
            ->setHelp(
                <<<'HELP'
                Use PHPUnit tests from the development console.

                This command provides a subset of features available to the phpunit
                command line interface. From the development console, you may run all
                tests or filter tests on groups, test suites, and more.

                e.g.
                <return>>>> phpunit:run</return>
                <return>>>> phpunit:run FooTest BarTest</return>
                <return>>>> phpunit --group foo --group bar</return>
                <return>>>> phpunit --testdox</return>
                HELP,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $binDir */
        $binDir = $this->composer->getConfig()->get('bin-dir');
        $phpunit = $binDir . DIRECTORY_SEPARATOR . 'phpunit';

        $process = $this->processFactory->factory(
            array_merge([$phpunit, '--colors=always'], $this->buildArguments($input)),
            $this->repositoryRoot,
        );

        $process->start();

        $output->writeln('');

        $exitCode = $process->wait(
            function (string $_type, string $buffer) use ($output): void {
                $output->write($buffer);
            },
        );

        $output->writeln('');

        return $exitCode;
    }

    /**
     * @return string[]
     */
    private function buildArguments(InputInterface $input): array
    {
        /** @var string[] $testsArgument */
        $testsArgument = $input->getArgument('tests');

        /** @var string[] $groupOption */
        $groupOption = $input->getOption('group');

        /** @var string[] $excludeGroupOption */
        $excludeGroupOption = $input->getOption('exclude-group');

        /** @var string $testsuiteOption */
        $testsuiteOption = $input->getOption('testsuite') ?? '';

        /** @var string $filterOption */
        $filterOption = $input->getOption('filter') ?? '';

        /** @var bool $listGroupsOption */
        $listGroupsOption = $input->getOption('list-groups');

        /** @var bool $listSuitesOption */
        $listSuitesOption = $input->getOption('list-suites');

        /** @var bool $listTestsOption */
        $listTestsOption = $input->getOption('list-tests');

        /** @var bool $testdoxOption */
        $testdoxOption = $input->getOption('testdox');

        $arguments = [];

        if (count($testsArgument) > 0 && $filterOption === '') {
            $arguments[] = '--filter';
            $arguments[] = '/' . implode('|', $testsArgument) . '/i';
        }

        if (count($groupOption) > 0) {
            $arguments[] = '--group';
            $arguments[] = implode(',', $groupOption);
        }

        if (count($excludeGroupOption) > 0) {
            $arguments[] = '--exclude-group';
            $arguments[] = implode(',', $excludeGroupOption);
        }

        if ($testsuiteOption !== '') {
            $arguments[] = '--testsuite';
            $arguments[] = $testsuiteOption;
        }

        if ($filterOption !== '') {
            $arguments[] = '--filter';
            $arguments[] = $filterOption;
        }

        if ($listGroupsOption) {
            $arguments[] = '--list-groups';
        }

        if ($listSuitesOption) {
            $arguments[] = '--list-suites';
        }

        if ($listTestsOption) {
            $arguments[] = '--list-tests';
        }

        if ($testdoxOption) {
            $arguments[] = '--testdox';
        }

        return $arguments;
    }
}
