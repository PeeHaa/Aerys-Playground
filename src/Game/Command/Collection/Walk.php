<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Parameter\Direction;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Position\Point;
use AerysPlayground\Game\Tile\Tile;

class Walk implements Command
{
    private $gate;

    private $parameter;

    public function __construct(Gate $gate)
    {
        $this->gate      = $gate;
        $this->parameter = new Direction();
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() !== 'walk'
            && $command->hasParameters()
            && $this->gate->meetsAccessLevel($player)
            && $this->parameter->isParameterValid($command->getFirstParameter());
    }

    public function execute(UserCommand $command, TrainingYard $map, Player $player): array
    {
        $this->parameter->setValue($command->getFirstParameter());

        return [$this->move($map, $player), []];
    }

    private function move(TrainingYard $map, Player $player)
    {
        $newTile = $this->getNewTile($map, $player->getPoint());

        if (!$newTile->canBeWalkedOn()) {
            return 'You cannot go that way.';
        }

        call_user_func([$player, $this->parameter->getMovementMethod()]);

        return 'You walk to the ' . $this->parameter->getValue() . ' and find #ff0' . $newTile->getName() . '#fff.';
    }

    private function getNewTile(TrainingYard $map, Point $point): Tile
    {
        $newPoint = clone $point;

        call_user_func([$newPoint, $this->parameter->getMovementMethod()]);

        return $map->getTileAtPoint($newPoint);
    }
}
