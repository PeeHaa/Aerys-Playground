<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Npc;

use AerysPlayground\Game\Character\Character;

interface Npc extends Character
{
    public function getDescription(): string;

    public function getRespawnTime(): \DateInterval;
}
