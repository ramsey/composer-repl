<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Psy;

use Mockery\MockInterface;
use Ramsey\Dev\Repl\Psy\ElephpantCommand;
use Ramsey\Test\Dev\Repl\RamseyTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class ElephpantCommandTest extends RamseyTestCase
{
    public function testRun(): void
    {
        $input = new StringInput('');

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);
        $output->shouldReceive('writeln')->once();

        $command = new ElephpantCommand();

        $this->assertSame('🐘', $command->getName());
        $this->assertSame(['elephpant'], $command->getAliases());

        $command->run($input, $output);
    }
}
