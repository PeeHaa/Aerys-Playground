<?php declare(strict_types=1);

namespace AerysPlayground\Game\Level;

class Newb implements Level
{
    const EXPERIENCE_POINTS = 0;

    const ATTACK_POINTS     = [5, 10];

    const HIT_POINTS     = 5;

    public function getName(): string
    {
        return 'Newb';
    }

    public function getDescription(): string
    {
        return 'Has no idea what is going on.';
    }

    public function getNumeric(): int
    {
        return 1;
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
