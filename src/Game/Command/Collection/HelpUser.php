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
        return 'The available commands are: #f00help#fff, #f00info#fff, #f00look#fff, #f00walk#fff and #f00attack#fff. If you want help with a specific command please type #f00help {command}#fff.';
    }

    private function getHelpWithCommand(): string
    {
        switch ($this->parameter->getValue()) {
            case 'help':
                return 'You\'re looking at it...';

            case 'info':
                return 'Gives information about your level and experience points earned.';

            case 'look':
                return 'Gives information about the place you currently are. Use #f00look {direction}#fff to look at a specific direction. E.g. #f00look north#fff.';

            case 'walk':
                return 'Walk towards a specific direction. E.g. #f00walk north#fff.';

            case 'attack':
                return 'Attacks a bot in the current position.';
        }
    }
}
