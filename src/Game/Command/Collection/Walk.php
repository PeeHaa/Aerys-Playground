<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Parameter\Direction;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Position\Point;
use AerysPlayground\Game\Tile\Tile;

class Walk implements Command
{
    private $gate;

    private $storage;

    private $parameter;

    public function __construct(Gate $gate, Storage $storage)
    {
        $this->gate      = $gate;
        $this->storage   = $storage;
        $this->parameter = new Direction();
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'walk'
            && $command->hasParameters()
            && $this->gate->meetsAccessLevel($player)
            && $this->parameter->isParameterValid($command->getFirstParameter());
    }

    public function execute(UserCommand $command, TrainingYard $map, Player $player): \Generator
    {
        $this->parameter->setValue($command->getFirstParameter());

        $message = yield from $this->move($map, $player);

        return [$message, []];
    }

    private function move(TrainingYard $map, Player $player): \Generator
    {
        $newTile = $this->getTileAtNewPoint($map, $player->getPoint());

        if (!$newTile->canBeWalkedOn()) {
            return 'You cannot go that way.';
        }

        $player->{$this->parameter->getMovementMethod()}();

        yield from $this->storage->setPosition($player);

        $lines = [
            'You walk to the ' . $this->parameter->getValue() . ' and find #ff0' . $newTile->getName() . '#fff.'
        ];

        if ($bots = $this->getBotsAtNewPoint($map, $player->getPoint())) {
            $lines[] = 'You find #f30' . $bots[0]->getName() . '#fff.';
        }

        return implode("\n", $lines);
    }

    private function getTileAtNewPoint(TrainingYard $map, Point $point): Tile
    {
        $newPoint = clone $point;

        $newPoint->{$this->parameter->getMovementMethod()}();

        return $map->getTileAtPoint($newPoint);
    }

    private function getBotsAtNewPoint(TrainingYard $map, Point $point): array
    {
        return $map->getBotsAliveAtPoint($point);
    }
}
