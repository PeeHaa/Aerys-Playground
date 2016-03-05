<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command;

use AerysPlayground\Game\Character\Player\Player;

class Gate
{
    private $accessLevel;

    public function __construct(int $accessLevel)
    {
        $this->accessLevel = $accessLevel;
    }

    public function equalsAccessLevel(Player $player): bool
    {
        return $this->accessLevel === $player->getAccessLevel();
    }

    public function meetsAccessLevel(Player $player): bool
    {
        return $this->accessLevel <= $player->getAccessLevel();
    }
}
