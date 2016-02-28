<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;

class Register implements Command
{
    public function doesMatch(UserCommand $command): bool
    {
        return $command->getCommand() === 'register';
    }

    public function execute(int $clientId): string
    {
        return 'You successfully joined the realm of Aerys. Use the `look` command to look around.';
    }
}
