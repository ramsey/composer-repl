<?php

declare(strict_types=1);

namespace Ramsey\Test\Composer\Repl;

use Mockery\MockInterface;
use Ramsey\Composer\Repl\Example;

class ExampleTest extends RamseyTestCase
{
    public function testGreet(): void
    {
        /** @var Example & MockInterface $example */
        $example = $this->mockery(Example::class);
        $example->shouldReceive('greet')->passthru();

        $this->assertSame('Hello, Friends!', $example->greet('Friends'));
    }
}
