<?php declare(strict_types=1);

namespace AerysPlayground\Game\Field\Bot;

use AerysPlayground\Game\Character\Npc\Factory;
use AerysPlayground\Game\Position\Point;

class Collection
{
    private $factory;

    private $bots = [];

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    // @todo move this to the constructor where it belongs
    public function addBotsFromMap(array $map)
    {
        // @todo once properly constructed we don't need this hack anymore
        if (!empty($this->tiles)) {
            throw new \Exception('Collection already initialized.');
        }

        foreach ($this->getBotsFromMap($map) as $point => $type) {
            if ($type === ' ' || $type === 'x') {
                continue;
            }

            if (!array_key_exists($point->getY(), $this->bots)) {
                $this->bots[$point->getY()] = [];
            }

            $this->bots[$point->getY()][$point->getX()] = $this->factory->build($type, $point);
        }
    }

    private function getBotsFromMap(array $map): \Generator
    {
        foreach ($map as $y => $row) {
            foreach ($row as $x => $type) {
                yield new Point($x, $y) => $type;
            }
        }
    }

    public function getBotsAtPoint(Point $point): array
    {
        if (array_key_exists($point->getY(), $this->bots) && array_key_exists($point->getX(), $this->bots[$point->getY()])) {
            return [$this->bots[$point->getY()][$point->getX()]];
        }

        return [];
    }

    public function getBotsAliveAtPoint(Point $point): array
    {
        $bots = $this->getBotsAtPoint($point);

        if (!$bots || !$bots[0]->isAlive()) {
            return [];
        }

        return $bots;
    }

    public function resurrect(): array
    {
        $resurrectedBots = [];

        foreach ($this->bots as $y => $row) {
            foreach ($row as $bot) {
                if (!$bot->mustRespawn()) {
                    continue;
                }

                $bot->resurrect();

                $resurrectedBots[] = $bot;
            }
        }

        return $resurrectedBots;
    }
}
