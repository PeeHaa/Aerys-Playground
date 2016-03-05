<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Parameter\UserCommand as Parameter;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class HelpUser implements Command
{
    private $gate;

    private $parameter;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
        $this->parameter = new Parameter();
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'help'
        && (!$command->hasParameters() || $this->parameter->isParameterValid($command->getFirstParameter()))
        && $this->gate->meetsAccessLevel($player);
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
        return 'The available commands are: help, look and walk. If you want help with a specific command please type `help {command}`.';
    }

    private function getHelpWithCommand(): string
    {
        switch ($this->parameter->getValue()) {
            case 'help':
                return 'You\'re looking at it...';

            case 'look':
                return 'Gives information about the place you currently are. Use `look {direction} to look at a specific direction. E.g. `look north`.';

            case 'walk':
                return 'Walk towards a specific direction. E.g. `walk north`.';
        }
    }
}
