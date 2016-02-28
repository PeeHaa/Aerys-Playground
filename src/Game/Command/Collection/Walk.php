<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Map\Park;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Tile\Tile;

class Walk implements Command
{
    public function doesMatch(UserCommand $command): bool
    {
        if ($command->getCommand() !== 'walk') {
            return false;
        }

        if ($command->hasParameters() && !in_array($command->getFirstParameter(), ['north', 'east', 'south', 'west'], true)) {
            return false;
        }

        return true;
    }

    public function execute(UserCommand $command, Park $map, Player $player): string
    {
        return $this->move($map, $player, $command->getFirstParameter(), $player->getPositionX(), $player->getPositionY());
    }

    private function move(Park $map, Player $player, string $direction, int $x, int $y)
    {
        $newTile = $this->getNewTile($map, $direction, $x, $y);

        if (!$newTile->canBeWalkedOn()) {
            return 'You cannot go that way.';
        }

        switch (strtolower($direction)) {
            case 'north':
                $player->moveTo($x, $y - 1);
                break;

            case 'east':
                $player->moveTo($x + 1, $y);
                break;

            case 'south':
                $player->moveTo($x, $y + 1);
                break;

            case 'west':
                $player->moveTo($x - 1, $y);
                break;
        }

        return 'You walk to the ' . strtolower($direction) . ' and find ' . $newTile->getName();
    }

    private function getNewTile(Park $map, string $direction, int $x, int $y): Tile
    {
        switch (strtolower($direction)) {
            case 'north':
                return $map->getTileAtPosition($x, $y - 1);

            case 'east':
                return $map->getTileAtPosition($x + 1, $y);

            case 'south':
                return $map->getTileAtPosition($x, $y + 1);

            case 'west':
                return $map->getTileAtPosition($x - 1, $y);
        }
    }
}
