<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;

class Help implements Command
{
    public function doesMatch(UserCommand $command): bool
    {
        if ($command->getCommand() !== 'help') {
            return false;
        }

        $validParameters = [
            'help',
            'register',
            'join',
            'look',
            'walk'
        ];

        if ($command->hasParameters() && !in_array($command->getFirstParameter(), $validParameters, true)) {
            return false;
        }

        return true;
    }

    public function execute(UserCommand $command): string
    {
        if ($command->hasParameters()) {
            return $this->getHelpWithCommand($command->getFirstParameter());
        }

        return $this->getCommands();
    }

    private function getCommands(): string
    {
        return 'The available commands are: help, join, look and walk. If you want help with a specific command please type `help {command}`.';
    }

    private function getHelpWithCommand(string $command): string
    {
        switch ($command) {
            case 'help':
                return 'You\'re looking at it...';

            case 'register':
                return 'Create an account so you can join a game.';

            case 'join':
                return 'Used to log you into the game. You will be prompted for a username and a password.';

            case 'look':
                return 'Gives information about the place you currently are. Use `look {direction} to look at a specific direction. E.g. `look north`.';

            case 'walk':
                return 'Walk towards a specific direction. E.g. `walk north`.';
        }
    }
}
