<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class About implements Command
{
    private $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'about'
            && !$command->hasParameters()
            && $this->gate->meetsAccessLevel($player);
    }

    public function execute(): array
    {
        return ['Aerys\' Playground: A multi-player text based RPG powered by Aerys (https://github.com/amphp/aerys). The source can be found at: https://github.com/PeeHaa/Aerys-Playground' , []];
    }
}
