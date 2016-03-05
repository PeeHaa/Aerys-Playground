<?php declare(strict_types=1);

namespace AerysPlayground\Game\Field\Player;

use AerysPlayground\Game\Character\Player\Player;

class Collection
{
    private $players = [];

    public function addPlayer(Player $player)
    {
        $this->players[$player->getPoint()->getY()][$player->getPoint()->getX()] = $player;
    }
}
