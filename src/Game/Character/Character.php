<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character;

use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Position\Point;

interface Character
{
    public function getId(): int;

    public function getName(): string;

    public function canMove(): bool;

    public function doesRespawn(): bool;

    public function isAlive(): bool;

    public function getPoint(): Point;

    public function moveNorth();

    public function moveEast();

    public function moveSouth();

    public function moveWest();

    public function moveTo(Point $point);

    public function resurrect();

    public function getTotalHitPoints(): int;

    public function hitByPlayer(Player $player): int;

    public function getHitPoints(): int;

    public function getAttackStrength(): int;
}
