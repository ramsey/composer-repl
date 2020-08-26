<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Psy;

use Mockery\MockInterface;
use Ramsey\Dev\Repl\Psy\🐘Command;
use Ramsey\Test\Dev\Repl\RamseyTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class 🐘CommandTest extends RamseyTestCase
{
    public function testRun(): void
    {
        $input = new StringInput('');

        /** @var OutputInterface & MockInterface $output */
        $output = $this->mockery(OutputInterface::class);
        $output->shouldReceive('writeln')->once();

        $command = new 🐘Command();

        $this->assertSame('🐘', $command->getName());
        $this->assertSame(['elephpant'], $command->getAliases());

        $command->run($input, $output);
    }
}
