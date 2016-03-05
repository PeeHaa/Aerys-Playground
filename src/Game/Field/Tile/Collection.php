<?php declare(strict_types=1);

namespace AerysPlayground\Game\Field\Tile;

use AerysPlayground\Game\Position\Point;
use AerysPlayground\Game\Tile\Factory;
use AerysPlayground\Game\Tile\Tile;

class Collection
{
    private $factory;

    private $tiles = [];

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    // @todo move this to the constructor where it belongs
    public function addTilesFromMap(array $map)
    {
        // @todo once properly constructed we don't need this hack anymore
        if (!empty($this->tiles)) {
            throw new \Exception('Collection already initialized.');
        }

        foreach ($this->getTilesFromMap($map) as $point => $type) {
            if (!array_key_exists($point->getY(), $this->tiles)) {
                $this->tiles[$point->getY()] = [];
            }

            $this->tiles[$point->getY()][$point->getX()] = $this->factory->build($type, $point);
        }
    }

    private function getTilesFromMap(array $map): \Generator
    {
        foreach ($map as $y => $row) {
            foreach ($row as $x => $type) {
                yield new Point($x, $y) => $type;
            }
        }
    }

    public function getTileAtPoint(Point $point): Tile
    {
        return $this->tiles[$point->getY()][$point->getX()];
    }
}
