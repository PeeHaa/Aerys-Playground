<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character\Player;

use AerysPlayground\Game\Character\GenericCharacter;
use AerysPlayground\Game\Level\Newb;
use AerysPlayground\Game\Level\Level;
use AerysPlayground\Game\Character\Npc\Npc;
use AerysPlayground\Game\Position\Point;

class User extends GenericCharacter implements Player
{
    private $name;

    private $accessLevel;

    private $level;

    public function __construct(int $id, string $name, int $accessLevel)
    {
        parent::__construct($id);

        $this->name        = $name;
        $this->accessLevel = $accessLevel;
        $this->level       = new Newb();
    }

    public function logIn(string $name, int $accessLevel, Point $point)
    {
        $this->name        = $name;
        $this->accessLevel = $accessLevel;
        $this->point       = $point;

        $this->resurrect();
    }

    public function getName(): string
    {
        return 'Player ' . $this->getId();
    }

    public function getAccessLevel(): int
    {
        return $this->accessLevel;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function canMove(): bool
    {
        return true;
    }

    public function doesRespawn(): bool
    {
        return true;
    }

    public function respawnTime(): \DateInterval
    {
        return new \DateInterval('PT0S');
    }

    public function getTotalHitPoints(): int
    {
        return $this->getLevel()->getHitPoints();
    }

    public function hitByBot(Npc $bot)
    {
        $this->hit($bot->getAttackStrength());
    }

    public function getAttackStrength(): int
    {
        return random_int(
            floor(($this->getLevel()->getNumeric() / 2) * $this->getLevel()->getNumeric()),
            $this->getLevel()->getNumeric() * $this->getLevel()->getNumeric()
        );
    }
}
