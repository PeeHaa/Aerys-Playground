<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

interface Tile
{
    public function getName(): string;

    public function getDescription(): string;

    public function canBeWalkedOn(): bool;
}
