<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

class Thicket implements Tile
{
    const IDENTIFIER = 'x';

    public function getName(): string
    {
        return 'Impenetrable thicket';
    }

    public function getDescription(): string
    {
        return '...';
    }

    public function canBeWalkedOn(): bool
    {
        return false;
    }
}
