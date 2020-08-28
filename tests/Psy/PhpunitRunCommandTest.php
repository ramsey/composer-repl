<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Psy;

use Composer\Composer;
use Composer\Config;
use Mockery\MockInterface;
use Psy\Context;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\Process;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Psy\PhpunitRunCommand;
use Ramsey\Test\Dev\Repl\RamseyTestCase;
use RuntimeException;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

use const DIRECTORY_SEPARATOR;

class PhpunitRunCommandTest extends RamseyTestCase
{
    /**
     * @var Composer & MockInterface $composer
     */
    private Composer $composer;

    public function setUp(): void
    {
        /** @var Config & MockInterface $config */
        $config = $this->mockery(Config::class);
        $config->allows()->get('bin-dir')->andReturn('/path/to/vendor/bin');

        $this->composer = $this->mockery(Composer::class, [
            'getConfig' => $config,
        ]);
    }

    public function testGetApplication(): void
    {
        /** @var ProcessFactory & MockInterface $processFactory */
        $processFactory = $this->mockery(ProcessFactory::class);

        /** @var Shell & MockInterface $application */
        $application = $this->mockery(Shell::class, [
            'getHelperSet' => $this->mockery(HelperSet::class),
        ]);

        $command = new PhpunitRunCommand('/path/to/repo', $processFactory, $this->composer);
        $command->setApplication($application);

        $this->assertSame($application, $command->getApplication());
    }

    public function testGetApplicationThrowsException(): void
    {
        /** @var ProcessFactory & MockInterface $processFactory */
        $processFactory = $this->mockery(ProcessFactory::class);

        $command = new PhpunitRunCommand('/path/to/repo', $processFactory, $this->composer);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Application is not set');

        $command->getApplication();
    }

    public function testGetContext(): void
    {
        /** @var ProcessFactory & MockInterface $processFactory */
        $processFactory = $this->mockery(ProcessFactory::class);

        /** @var Context & MockInterface $context */
        $context = $this->mockery(Context::class);

        $command = new PhpunitRunCommand('/path/to/repo', $processFactory, $this->composer);
        $command->setContext($context);

        $this->assertSame($context, $command->getContext());
    }

    public function testGetContextThrowsException(): void
    {
        /** @var ProcessFactory & MockInterface $processFactory */
        $processFactory = $this->mockery(ProcessFactory::class);

        $command = new PhpunitRunCommand('/path/to/repo', $processFactory, $this->composer);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Context is not set');

        $command->getContext();
    }

    /**
     * @param string[] $expectedParams
     *
     * @dataProvider provideCommandInput
     */
    public function testCommand(
        string $commandLine,
        array $expectedParams,
        int $expectedExitCode
    ): void {
        $input = new StringInput($commandLine);

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);
        $output->expects()->writeln('')->twice();
        $output->expects()->write('this is some output');

        /** @var Process & MockInterface $process */
        $process = $this->mockery(Process::class);
        $process->expects()->start();
        $process->shouldReceive('wait')->andReturnUsing(
            function (callable $callback) use ($expectedExitCode): int {
                $callback('type', 'this is some output');

                return $expectedExitCode;
            },
        );

        /** @var ProcessFactory & MockInterface $processFactory */
        $processFactory = $this->mockery(ProcessFactory::class);
        $processFactory
            ->expects()
            ->factory($expectedParams, '/path/to/repo')
            ->andReturn($process);

        $command = new PhpunitRunCommand('/path/to/repo', $processFactory, $this->composer);

        $this->assertSame($expectedExitCode, $command->run($input, $output));
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function provideCommandInput(): array
    {
        $phpunit = '/path/to/vendor/bin' . DIRECTORY_SEPARATOR . 'phpunit';

        return [
            [
                'commandLine' => '',
                'expectedParams' => [$phpunit, '--colors=always'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => 'foo bar baz',
                'expectedParams' => [$phpunit, '--colors=always', '--filter', '/foo|bar|baz/i'],
                'expectedExitCode' => 1,
            ],
            [
                'commandLine' => 'foo bar baz --filter "/qux|quux/"',
                'expectedParams' => [$phpunit, '--colors=always', '--filter', '/qux|quux/'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--group foo --group bar',
                'expectedParams' => [$phpunit, '--colors=always', '--group', 'foo,bar'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--exclude-group foo --exclude-group bar',
                'expectedParams' => [$phpunit, '--colors=always', '--exclude-group', 'foo,bar'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--testsuite unittests',
                'expectedParams' => [$phpunit, '--colors=always', '--testsuite', 'unittests'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--list-groups',
                'expectedParams' => [$phpunit, '--colors=always', '--list-groups'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--list-suites',
                'expectedParams' => [$phpunit, '--colors=always', '--list-suites'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--list-tests',
                'expectedParams' => [$phpunit, '--colors=always', '--list-tests'],
                'expectedExitCode' => 0,
            ],
            [
                'commandLine' => '--testdox',
                'expectedParams' => [$phpunit, '--colors=always', '--testdox'],
                'expectedExitCode' => 127,
            ],
            [
                'commandLine' => '--group foo --exclude-group bar --testsuite '
                    . 'unit --filter "/mytest/" --list-groups --list-suites '
                    . '--list-tests --testdox all the things',
                'expectedParams' => [
                    $phpunit,
                    '--colors=always',
                    '--group',
                    'foo',
                    '--exclude-group',
                    'bar',
                    '--testsuite',
                    'unit',
                    '--filter',
                    '/mytest/',
                    '--list-groups',
                    '--list-suites',
                    '--list-tests',
                    '--testdox',
                ],
                'expectedExitCode' => 0,
            ],
        ];
    }
}
