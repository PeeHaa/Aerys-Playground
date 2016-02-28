<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Map\Park;
use AerysPlayground\Game\Character\Player\Player;

class Look implements Command
{
    public function doesMatch(UserCommand $command): bool
    {
        if ($command->getCommand() !== 'look') {
            return false;
        }

        if ($command->hasParameters() && !in_array($command->getFirstParameter(), ['north', 'east', 'south', 'west'], true)) {
            return false;
        }

        return true;
    }

    public function execute(UserCommand $command, Park $map, Player $player): string
    {
        if ($command->hasParameters()) {
            return $this->lookAtCoordinates(
                $map,
                $command->getFirstParameter(),
                $player->getPositionX(),
                $player->getPositionY()
            );
        }

        return $this->lookLocally($map, $player->getPositionX(), $player->getPositionY());
    }

    private function lookAtCoordinates(Park $map, string $direction, int $x, int $y)
    {
        switch (strtolower($direction)) {
            case 'north':
                return 'To the north you see ' . $map->getTileAtPosition($x, $y - 1)->getName() . "\n";

            case 'east':
                return 'To the east you see ' . $map->getTileAtPosition($x + 1, $y)->getName() . "\n";

            case 'south':
                return 'To the south you see ' . $map->getTileAtPosition($x, $y + 1)->getName() . "\n";

            case 'west':
                return 'To the west you see ' . $map->getTileAtPosition($x - 1, $y)->getName() . "\n";
        }
    }

    private function lookLocally(Park $map, int $x, int $y)
    {
        $currentTile = $map->getTileAtPosition($x, $y);
        $northTile   = $map->getTileAtPosition($x, $y - 1);
        $eastTile    = $map->getTileAtPosition($x + 1, $y);
        $southTile   = $map->getTileAtPosition($x, $y + 1);
        $westTile    = $map->getTileAtPosition($x - 1, $y);

        $result = $currentTile->getDescription() . "\n\n";

        if ($northTile->canBeWalkedOn()) {
            $result .= "To the north you see " . $northTile->getName() . "\n";
        }

        if ($eastTile->canBeWalkedOn()) {
            $result .= "To the east you see " . $eastTile->getName() . "\n";
        }

        if ($southTile->canBeWalkedOn()) {
            $result .= "To the south you see " . $southTile->getName() . "\n";
        }

        if ($westTile->canBeWalkedOn()) {
            $result .= "To the west you see " . $westTile->getName() . "\n";
        }

        return $result;
    }
}
