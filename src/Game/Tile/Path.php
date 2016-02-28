<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

class Path implements Tile
{
    const IDENTIFIER = 'p';

    public function getName(): string
    {
        return 'A dark path';
    }

    public function getDescription(): string
    {
        return 'A dark path. You here something in the bushes, but you cannot see who or what is following you.';
    }

    public function canBeWalkedOn(): bool
    {
        return true;
    }
}
