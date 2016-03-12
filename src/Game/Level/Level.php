<?php declare(strict_types=1);

namespace AerysPlayground\Game\Level;

interface Level
{
    public function getName(): string;

    public function getDescription(): string;

    public function getNumeric(): int;

    public function getExperiencePoints(): int;

    public function getAttackStrength(): int;

    public function getHitPoints(): int;
}
