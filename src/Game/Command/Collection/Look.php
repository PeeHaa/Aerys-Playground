<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Parameter\Direction;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Position\Point;


class Look implements Command
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
        return $command->getCommand() === 'look'
            && (!$command->hasParameters() || $this->parameter->isParameterValid($command->getFirstParameter()))
            && $this->gate->meetsAccessLevel($player);
    }

    public function execute(UserCommand $command, TrainingYard $map, Player $player): array
    {
        if ($command->hasParameters()) {
            $this->parameter->setValue($command->getFirstParameter());

            return [$this->lookAtPoint($map, $player->getPoint()), []];
        }

        return [$this->lookLocally($map, $player->getPoint()), []];
    }

    private function lookAtPoint(TrainingYard $map, Point $point)
    {
        $newPoint = clone $point;

        $newPoint->{$this->parameter->getMovementMethod()}();

        return 'To the ' . $this->parameter->getValue() . ' you see #ff0' . $map->getTileAtPoint($newPoint)->getName() . ' #fff.' . "\n";
    }

    private function lookLocally(TrainingYard $map, Point $currentPoint)
    {
        $northPoint   = clone $currentPoint;
        $eastPoint    = clone $currentPoint;
        $southPoint   = clone $currentPoint;
        $westPoint    = clone $currentPoint;

        $northPoint->moveNorth();
        $eastPoint->moveEast();
        $southPoint->moveSouth();
        $westPoint->moveWest();

        $currentTile = $map->getTileAtPoint($currentPoint);
        $northTile   = $map->getTileAtPoint($northPoint);
        $eastTile    = $map->getTileAtPoint($eastPoint);
        $southTile   = $map->getTileAtPoint($southPoint);
        $westTile    = $map->getTileAtPoint($westPoint);

        $result = $currentTile->getDescription() . "\n\n";

        if ($northTile->canBeWalkedOn()) {
            $result .= 'To the north you see #ff0' . $northTile->getName() . '#fff. ' . "\n";
        }

        if ($eastTile->canBeWalkedOn()) {
            $result .= 'To the east you see #ff0' . $eastTile->getName() . '#fff. ' . "\n";
        }

        if ($southTile->canBeWalkedOn()) {
            $result .= 'To the south you see #ff0' . $southTile->getName() . '#fff. ' . "\n";
        }

        if ($westTile->canBeWalkedOn()) {
            $result .= 'To the west you see #ff0' . $westTile->getName() . '#fff. ' . "\n";
        }

        if ($bots = $this->getBotsLocally($map, $currentPoint)) {
            $result .= '#f30' . $bots[0]->getName() . '#fff is here to #f00attack#fff. ' . "\n";
        }

        return $result;
    }

    private function getBotsLocally(TrainingYard $map, Point $point): array
    {
        return $map->getBotsAliveAtPoint($point);
    }
}
