<?php declare(strict_types=1);

namespace AerysPlayground\Game\Level;

class Master implements Level
{
    const EXPERIENCE_POINTS = 30;

    const ATTACK_POINTS     = [8, 15];

    const HIT_POINTS     = 20;

    public function getName(): string
    {
        return 'Master';
    }

    public function getDescription(): string
    {
        return 'Knows how to handle a sword.';
    }

    public function getNumeric(): int
    {
        return 3;
    }

    public function getExperiencePoints(): int
    {
        return self::EXPERIENCE_POINTS;
    }

    public function getAttackStrength(): int
    {
        return random_int(self::ATTACK_POINTS[0], self::ATTACK_POINTS[1]);
    }

    public function getHitPoints(): int
    {
        return self::HIT_POINTS;
    }
}
