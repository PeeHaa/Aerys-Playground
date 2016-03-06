<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Info implements Command
{
    private $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'info'
            && !$command->hasParameters()
            && $this->gate->meetsAccessLevel($player);
    }

    public function execute(Player $player): array
    {
        return ['Level ' . $player->getLevel()->getName() . '. Experience ' . $player->getExperience() . '. Position: ' . $player->getPoint()->getX() . 'x' . $player->getPoint()->getY() , []];
    }
}
