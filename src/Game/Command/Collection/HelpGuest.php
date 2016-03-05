<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Parameter\GuestCommand;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class HelpGuest implements Command
{
    private $gate;

    private $parameter;

    public function __construct(Gate $gate)
    {
        $this->gate      = $gate;
        $this->parameter = new GuestCommand();
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'help'
            && (!$command->hasParameters() || $this->parameter->isParameterValid($command->getFirstParameter()))
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command): array
    {
        if ($command->hasParameters()) {
            $this->parameter->setValue($command->getFirstParameter());

            return [$this->getHelpWithCommand(), []];
        }

        return [$this->getCommands(), []];
    }

    private function getCommands(): string
    {
        return 'The available commands are: #f00help#fff, #f00register#fff and #f00join#fff. If you want help with a specific command please type #f00help {command}#fff.';
    }

    private function getHelpWithCommand(): string
    {
        switch ($this->parameter->getValue()) {
            case 'help':
                return 'You\'re looking at it...';

            case 'register':
                return 'Create an account so you can join a game.';

            case 'join':
                return 'Used to log you into the game. You will be prompted for a username and a password.';
        }
    }
}
