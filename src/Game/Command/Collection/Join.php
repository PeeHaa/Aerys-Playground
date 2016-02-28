<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Map\Park;

class Join implements Command
{
    public function doesMatch(UserCommand $command): bool
    {
        return $command->getCommand() === 'join';
    }

    public function execute(Park $park, int $clientId): string
    {
        $park->addPlayer($clientId);

        return 'You successfully joined the realm of Aerys. Use the `look` command to look around.';
    }
}
