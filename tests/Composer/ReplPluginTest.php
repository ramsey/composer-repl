<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Mockery;
use Mockery\MockInterface;
use Ramsey\Dev\Repl\Composer\ReplCommand;
use Ramsey\Dev\Repl\Composer\ReplPlugin;
use Ramsey\Dev\Tools\TestCase;

class ReplPluginTest extends TestCase
{
    public function testGetCapabilities(): void
    {
        $plugin = new ReplPlugin();

        $this->assertSame(
            [
                CommandProvider::class => ReplPlugin::class,
            ],
            $plugin->getCapabilities(),
        );
    }

    public function testGetCommands(): void
    {
        /** @var Composer & MockInterface $composer */
        $composer = Mockery::mock(Composer::class);

        /** @var IOInterface & MockInterface $io */
        $io = Mockery::spy(IOInterface::class);

        $plugin = new ReplPlugin();
        $plugin->activate($composer, $io);

        $commands = $plugin->getCommands();

        $this->assertIsArray($commands);
        $this->assertCount(1, $commands);
        $this->assertInstanceOf(ReplCommand::class, $commands[0]);
    }

    public function testActivate(): void
    {
        /** @var Composer & MockInterface $composer */
        $composer = Mockery::mock(Composer::class);

        /** @var IOInterface & MockInterface $io */
        $io = Mockery::spy(IOInterface::class);

        $plugin = new ReplPlugin();
        $plugin->activate($composer, $io);

        $composer->shouldNotHaveBeenCalled();
        $io->shouldNotHaveBeenCalled();
    }

    public function testDeactivate(): void
    {
        /** @var Composer & MockInterface $composer */
        $composer = Mockery::mock(Composer::class);

        /** @var IOInterface & MockInterface $io */
        $io = Mockery::spy(IOInterface::class);

        $plugin = new ReplPlugin();
        $plugin->deactivate($composer, $io);

        $composer->shouldNotHaveBeenCalled();
        $io->shouldNotHaveBeenCalled();
    }

    public function testUninstall(): void
    {
        /** @var Composer & MockInterface $composer */
        $composer = Mockery::mock(Composer::class);

        /** @var IOInterface & MockInterface $io */
        $io = Mockery::spy(IOInterface::class);

        $plugin = new ReplPlugin();
        $plugin->uninstall($composer, $io);

        $composer->shouldNotHaveBeenCalled();
        $io->shouldNotHaveBeenCalled();
    }
}
