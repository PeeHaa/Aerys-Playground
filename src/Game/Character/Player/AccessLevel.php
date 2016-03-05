<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Player;

class AccessLevel
{
    const GUEST = 1;
    const USER  = 2;
    const MOD   = 4;
    const ADMIN = 8;
}
