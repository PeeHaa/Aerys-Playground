<?php declare(strict_types=1);

namespace AerysPlayground\Game\Level;

class Apprentice implements Level
{
    const EXPERIENCE_POINTS = 10;

    const ATTACK_POINTS     = [6, 11];

    const HIT_POINTS     = 10;

    public function getName(): string
    {
        return 'Apprentice';
    }

    public function getDescription(): string
    {
        return 'Still too fresh to go out there, but at least can handle a sword.';
    }

    public function getNumeric(): int
    {
        return 2;
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
