<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Npc;

use AerysPlayground\Game\Character\GenericCharacter;

class Dummy extends GenericCharacter// implements Npc
{
    public function getDescription(): string
    {
        return 'Just a test dummy...';
    }

    public function canMove(): bool
    {
        return false;
    }

    public function doesRespawn(): bool
    {
        return true;
    }

    public function respawnTime(): \DateInterval
    {
        return new \DateInterval('PT0S');
    }

    public function getTotalHitPoints(): int
    {
        return 10;
    }
}
