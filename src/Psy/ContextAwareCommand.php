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

use Psy\Command\Command;
use Psy\Context;
use Psy\ContextAware;
use Psy\Shell;
use RuntimeException;

abstract class ContextAwareCommand extends Command implements ContextAware
{
    protected ?Context $context = null;

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    public function getApplication(): Shell
    {
        /** @var Shell | null $application */
        $application = parent::getApplication();

        if ($application === null) {
            throw new RuntimeException('Application is not set');
        }

        return $application;
    }

    public function getContext(): Context
    {
        if ($this->context === null) {
            throw new RuntimeException('Context is not set');
        }

        return $this->context;
    }
}
