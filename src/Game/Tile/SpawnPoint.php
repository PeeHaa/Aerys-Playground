<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

class SpawnPoint implements Tile
{
    const IDENTIFIER = 's';

    public function getName(): string
    {
        return 'A strange glowing bench';
    }

    public function getDescription(): string
    {
        return 'A strange glowing bench. You feel the sense of power coming from it.';
    }

    public function canBeWalkedOn(): bool
    {
        return true;
    }
}
