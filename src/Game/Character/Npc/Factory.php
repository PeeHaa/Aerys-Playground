<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Npc;

use AerysPlayground\Game\Position\Point;

class Factory
{
    private $lastId = 0;

    public function build(string $type, Point $point): Npc
    {
        switch ($type) {
            case 'd':
                $bot = new Dummy(++$this->lastId);
                break;

            default:
                throw new \Exception('Trying to build an unknown bot (`' . $type . '`).');
        }

        $bot->moveTo($point);
        $bot->resurrect();

        return $bot;
    }
}
