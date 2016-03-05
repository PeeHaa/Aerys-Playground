<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

use AerysPlayground\Game\Position\Point;

class Path implements Tile
{
    const IDENTIFIER = 'p';

    private $point;

    public function __construct(Point $point)
    {
        $this->point = $point;
    }

    public function getName(): string
    {
        return 'A dark path';
    }

    public function getDescription(): string
    {
        return 'A dark path. You here something in the bushes, but you cannot see who or what is following you.';
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
