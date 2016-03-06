<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Position\Point;

class Attack implements Command
{
    private $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'attack'
            && $this->gate->meetsAccessLevel($player);
    }

    public function execute(TrainingYard $map, Player $player): array
    {
        if ($bots = $this->getBotsAtPoint($map, $player->getPoint())) {
            $player->startAttack($bots[0]);

            return ['You are attacking #f30' . $bots[0]->getName() . '#fff.', []];
        }

        return ['Nothing to attack here', []];
    }

    private function getBotsAtPoint(TrainingYard $map, Point $point): array
    {
        return $map->getBotsAliveAtPoint($point);
    }
}
