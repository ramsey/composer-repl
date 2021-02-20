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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psy\Input\CodeArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function method_exists;
use function preg_match;
use function trim;

/**
 * PsySH command to provide PHPUnit testing features in the interactive console
 */
class PhpunitTestCommand extends ContextAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('phpunit:test')
            ->setAliases(['t'])
            ->setDefinition([
                new CodeArgument(
                    'assertion',
                    CodeArgument::REQUIRED,
                    'The PHPUnit assertion to evaluate.',
                ),
            ])
            ->setDescription('Use PHPUnit assertions to write tests in the console.')
            ->setHelp(
                <<<'HELP'
                Use PHPUnit assertions to write tests in the development console.

                The assertion must be a valid PHPUnit assertion method call. Any
                variables set in the console are available for use in the assertion.

                e.g.
                <return>>>> $value = 1 + 1</return>
                <return>>>> $date = new DateTimeImmutable()</return>
                <return>>>> phpunit:test assertTrue(false)</return>
                <return>>>> t assertSame(2, $value)</return>
                <return>>>> t assertInstanceOf(DateTimeInterface::class, $date)</return>
                HELP,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var TestCase $phpunitTestCase */
        $phpunitTestCase = $this->getContext()->get('phpunit');

        $assertion = $this->getAssertion($input);

        if (!preg_match('/^(assert[a-zA-Z0-9_\x80-\xff]+)\(.+\);?$/', $assertion, $matches)) {
            throw new InvalidArgumentException('The assertion provided is not valid');
        }

        $functionName = $matches[1] ?? '';

        if (!method_exists($phpunitTestCase, $functionName)) {
            throw new InvalidArgumentException("{$functionName} is not a PHPUnit assertion method");
        }

        try {
            $code = '$phpunit->' . $assertion;
            $this->getApplication()->execute($code, true);
            $output->writeln('<fg=cyan>Test passed!</>');
        } catch (Throwable $exception) {
            $output->writeln("<error>Test failed: {$exception->getMessage()}</error>");
        }

        return 0;
    }

    private function getAssertion(InputInterface $input): string
    {
        /** @var string $assertion */
        $assertion = $input->getArgument('assertion') ?? '';

        return trim($assertion);
    }
}
