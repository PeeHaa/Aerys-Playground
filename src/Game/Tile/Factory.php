<?php declare(strict_types=1);

namespace AerysPlayground\Game\Tile;

class Factory
{
    public function build($type): Tile
    {
        switch ($type) {
            case 'x':
                return new Thicket();

            case 's':
                return new SpawnPoint();

            case 'p':
                return new Path();
        }
    }
}
