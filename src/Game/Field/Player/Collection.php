<?php declare(strict_types=1);

namespace AerysPlayground\Game\Field\Player;

use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Position\Point;

class Collection
{
    private $players = [];

    public function addPlayer(Player $player)
    {
        $this->players[$player->getPoint()->getY()][$player->getPoint()->getX()] = $player;
    }

    public function getPlayersAtPoint(Point $point): array
    {
        $players = [];

        foreach ($this->players as $row) {
            foreach ($row as $player) {
                if ($player->getPoint()->getX() !== $point->getX() || $player->getPoint()->getY() !== $point->getY()) {
                    continue;
                }

                $players[] = $player;
            }
        }

        return $players;
    }
}
