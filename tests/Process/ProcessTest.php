<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Process;

use Ramsey\Dev\Repl\Process\Process;
use Ramsey\Dev\Tools\TestCase;

use function strcasecmp;
use function substr;

use const PHP_OS;

class ProcessTest extends TestCase
{
    public function testUseCorrectCommand(): void
    {
        $process = $this->mockery(Process::class);
        $process->shouldAllowMockingProtectedMethods();
        $process->shouldReceive('useCorrectCommand')->passthru();
        $process->expects()->getProcessClassName()->andReturn(ProcessMock::class);

        // @phpstan-ignore-next-line
        $commandLine = $process->useCorrectCommand(['foo', '--bar', '--baz']);

        if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
            $this->assertSame('foo "--bar" "--baz"', $commandLine);
        } else {
            $this->assertSame("foo '--bar' '--baz'", $commandLine);
        }
    }
}
