<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Npc;

use AerysPlayground\Game\Character\GenericCharacter;
use AerysPlayground\Game\Character\Player\Player;

class Dummy extends GenericCharacter implements Npc
{
    public function getName(): string
    {
        return 'A Dummy';
    }

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

    public function getRespawnTime(): \DateInterval
    {
        return new \DateInterval('PT10S');
    }

    public function mustRespawn(): bool
    {
        if ($this->isAlive() || !$this->doesRespawn()) {
            return false;
        }

        return (new \DateTimeImmutable()) > $this->timeOfDeath->add($this->getRespawnTime());
    }

    public function getTotalHitPoints(): int
    {
        return 10;
    }

    public function getAttackStrength(): int
    {
        return 0;
    }

    public function getEarnedExperience(Player $player): int
    {
        if (!array_key_exists($player->getId(), $this->hits) || $this->hits[$player->getId()] === 0) {
            return 0;
        }

        return (int) ceil($this->hits[$player->getId()] / $this->getTotalHitPoints()) * 2;
    }
}
