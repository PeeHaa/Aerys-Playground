<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;

interface Command
{
    public function doesMatch(UserCommand $command): bool;
}
