<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Psy;

use Exception;
use InvalidArgumentException;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psy\Context;
use Psy\Input\ShellInput;
use Psy\Shell;
use Ramsey\Dev\Repl\Psy\PhpunitTestCommand;
use Ramsey\Test\Dev\Repl\RamseyTestCase;
use RuntimeException;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class PhpunitTestCommandTest extends RamseyTestCase
{
    private Context $context;

    public function setUp(): void
    {
        /** @var Context & MockInterface $context */
        $context = $this->mockery(Context::class);
        $context->allows()->get('phpunit')->andReturn(new class extends TestCase {
        });

        $this->context = $context;
    }

    public function testGetApplication(): void
    {
        /** @var Shell & MockInterface $application */
        $application = $this->mockery(Shell::class, [
            'getHelperSet' => $this->mockery(HelperSet::class),
        ]);

        $command = new PhpunitTestCommand();
        $command->setApplication($application);

        $this->assertSame($application, $command->getApplication());
    }

    public function testGetApplicationThrowsException(): void
    {
        $command = new PhpunitTestCommand();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Application is not set');

        $command->getApplication();
    }

    public function testGetContext(): void
    {
        /** @var Context & MockInterface $context */
        $context = $this->mockery(Context::class);

        $command = new PhpunitTestCommand();
        $command->setContext($context);

        $this->assertSame($context, $command->getContext());
    }

    public function testGetContextThrowsException(): void
    {
        $command = new PhpunitTestCommand();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Context is not set');

        $command->getContext();
    }

    /**
     * @dataProvider provideInvalidAssertions
     */
    public function testCommandThrowsExceptionForInvalidAssertions(
        string $invalidAssertion,
        string $exceptionMessage
    ): void {
        $input = new ShellInput($invalidAssertion);

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);

        $command = new PhpunitTestCommand();
        $command->setContext($this->context);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $command->run($input, $output);
    }

    /**
     * @return array<array<string, string>>
     */
    public function provideInvalidAssertions(): array
    {
        return [
            [
                'invalidAssertion' => 'foo()',
                'exceptionMessage' => 'The assertion provided is not valid',
            ],
            [
                'invalidAssertion' => 'foo($bar)',
                'exceptionMessage' => 'The assertion provided is not valid',
            ],
            [
                'invalidAssertion' => 'assertFoo($bar)',
                'exceptionMessage' => 'assertFoo is not a PHPUnit assertion method',
            ],
            [
                'invalidAssertion' => 'assertFoo()',
                'exceptionMessage' => 'The assertion provided is not valid',
            ],
            [
                'invalidAssertion' => 'assert($foo)',
                'exceptionMessage' => 'The assertion provided is not valid',
            ],
        ];
    }

    public function testCommandRunsWithPassingTest(): void
    {
        $input = new ShellInput('assertSame(2, $bar)');

        /** @var Shell & MockInterface $application */
        $application = $this->mockery(Shell::class, [
            'getHelperSet' => $this->mockery(HelperSet::class),
            'getDefinition' => $this->mockery(InputDefinition::class, [
                'getArguments' => [],
                'getOptions' => [],
            ]),
        ]);
        $application->expects()->execute('$phpunit->assertSame(2, $bar)', true);

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);
        $output->expects()->writeln('<fg=cyan>Test passed!</>');

        $command = new PhpunitTestCommand();
        $command->setApplication($application);
        $command->setContext($this->context);

        $this->assertSame(0, $command->run($input, $output));
    }

    public function testCommandRunsWithFailingTest(): void
    {
        $input = new ShellInput('assertIsArray($bar);');

        /** @var Shell & MockInterface $application */
        $application = $this->mockery(Shell::class, [
            'getHelperSet' => $this->mockery(HelperSet::class),
            'getDefinition' => $this->mockery(InputDefinition::class, [
                'getArguments' => [],
                'getOptions' => [],
            ]),
        ]);
        $application
            ->expects()
            ->execute('$phpunit->assertIsArray($bar);', true)
            ->andThrow(new Exception('something bad happened'));

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);
        $output->expects()->writeln('<error>Test failed: something bad happened</error>');

        $command = new PhpunitTestCommand();
        $command->setApplication($application);
        $command->setContext($this->context);

        $this->assertSame(0, $command->run($input, $output));
    }
}
