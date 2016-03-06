<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character;

use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Position\Point;

abstract class GenericCharacter
{
    protected $id;

    protected $isAlive = false;

    protected $point;

    protected $hitPoints;

    protected $hits = [];

    protected $timeOfDeath;

    public function __construct(int $id)
    {
        $this->id    = $id;
        $this->point = new Point(0, 0);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAlive(): bool
    {
        return $this->isAlive;
    }

    public function getPoint(): Point
    {
        return $this->point;
    }

    public function moveNorth()
    {
        $this->point->moveNorth();
    }

    public function moveEast()
    {
        $this->point->moveEast();
    }

    public function moveSouth()
    {
        $this->point->moveSouth();
    }

    public function moveWest()
    {
        $this->point->moveWest();
    }

    public function moveTo(Point $point)
    {
        $this->point = $point;
    }

    public function resurrect()
    {
        $this->hitPoints = $this->getTotalHitPoints();
        $this->isAlive   = true;
        $this->hits      = [];
    }

    public function hitByPlayer(Player $player): int
    {
        $hitPoints = $player->getLevel()->getNumeric() * random_int(0, 2);

        $this->registerPlayerHit($player, $hitPoints);
        $this->hit($hitPoints);

        return $hitPoints;
    }

    protected function hit(int $hitPoints)
    {
        $this->hitPoints -= $hitPoints;

        if ($this->getHitPoints() < 0) {
            $this->isAlive     = false;
            $this->timeOfDeath = new \DateTimeImmutable();
        }
    }

    protected function registerPlayerHit(Player $player, int $hitPoints)
    {
        if (!isset($this->hits[$player->getId()])) {
            $this->hits[$player->getId()] = 0;
        }

        if ($hitPoints > $this->hitPoints) {
            $this->hits[$player->getId()] += $this->hitPoints;

            return;
        }

        $this->hits[$player->getId()] += $hitPoints;
    }

    public function getHitPoints(): int
    {
        return $this->hitPoints;
    }
}
