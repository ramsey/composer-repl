<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Process;

use Mockery\MockInterface;
use Ramsey\Dev\Repl\Process\Process;
use Ramsey\Dev\Tools\TestCase;

class ProcessTest extends TestCase
{
    public function testUseCorrectCommand(): void
    {
        /** @var Process & MockInterface $process */
        $process = $this->mockery(Process::class);
        $process->shouldAllowMockingProtectedMethods();
        $process->shouldReceive('useCorrectCommand')->passthru();
        $process->expects()->getProcessClassName()->andReturn(ProcessMock::class);

        // @phpstan-ignore-next-line
        $commandLine = $process->useCorrectCommand(['foo', '--bar', '--baz']);

        $this->assertSame("foo '--bar' '--baz'", $commandLine);
    }
}
