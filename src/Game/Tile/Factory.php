<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

use AerysPlayground\Game\Position\Point;

class Factory
{
    public function build(string $type, Point $point): Tile
    {
        switch ($type) {
            case 'x':
                return new Thicket($point);

            case 's':
                return new SpawnPoint($point);

            case 'p':
                return new Path($point);
        }
    }
}
