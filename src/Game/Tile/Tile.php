<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

use AerysPlayground\Game\Position\Point;

interface Tile
{
    public function getName(): string;

    public function getDescription(): string;

    public function getPoint(): Point;

    public function canBeWalkedOn(): bool;
}
