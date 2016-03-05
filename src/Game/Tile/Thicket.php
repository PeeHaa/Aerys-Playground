<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

use AerysPlayground\Game\Position\Point;

class Thicket implements Tile
{
    const IDENTIFIER = 'x';

    private $point;

    public function __construct(Point $point)
    {
        $this->point = $point;
    }

    public function getName(): string
    {
        return 'Impenetrable thicket';
    }

    public function getDescription(): string
    {
        return '...';
    }

    public function getPoint(): Point
    {
        return $this->point;
    }

    public function canBeWalkedOn(): bool
    {
        return false;
    }
}
