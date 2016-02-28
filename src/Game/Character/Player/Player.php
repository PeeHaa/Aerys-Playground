<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Player;

use AerysPlayground\Game\Character\Character;
use AerysPlayground\Game\Level\Level;
use AerysPlayground\Game\Character\Npc\Npc;

interface Player extends Character
{
    public function getLevel(): Level;

    public function hitByBot(Npc $bot);
}
