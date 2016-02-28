<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character;

use AerysPlayground\Game\Character\Player\Player;

interface Character
{
    public function getId(): int;

    public function getName(): string;

    public function canMove(): bool;

    public function doesRespawn(): bool;

    public function respawnTime(): \DateInterval;

    public function isAlive(): bool;

    public function getPositionX(): int;

    public function getPositionY(): int;

    public function moveTo(int $x, int $y);

    public function resurrect();

    public function getTotalHitPoints(): int;

    public function hitByPlayer(Player $player);

    public function getHitPoints(): int;

    public function getAttackStrength(): int;
}
