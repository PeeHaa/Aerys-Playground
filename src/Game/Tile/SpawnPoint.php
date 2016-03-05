<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

use AerysPlayground\Game\Position\Point;

class SpawnPoint implements Tile
{
    const IDENTIFIER = 's';

    private $point;

    public function __construct(Point $point)
    {
        $this->point = $point;
    }

    public function getName(): string
    {
        return 'A strange glowing bench';
    }

    public function getDescription(): string
    {
        return 'A strange glowing bench. You feel the sense of power coming from it.';
    }

    public function getPoint(): Point
    {
        return $this->point;
    }

    public function canBeWalkedOn(): bool
    {
        return true;
    }
}
