<?php declare(strict_types=1);

namespace AerysPlayground\Game\Character;

use AerysPlayground\Game\Character\Player\Player;

abstract class GenericCharacter
{
    protected $id;

    protected $isAlive = false;

    protected $positionX = 0;

    protected $positionY = 0;

    protected $hitPoints;

    protected $hits = [];

    protected $timeOfDeath;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAlive(): bool
    {
        return $this->isAlive;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function moveTo(int $x, int $y)
    {
        if (!$this->canMove()) {
            return;
        }

        $this->positionX = $x;
        $this->positionY = $y;
    }

    public function resurrect()
    {
        $this->hitPoints = $this->getTotalHitPoints();
        $this->isAlive   = true;
        $this->hits      = [];
    }

    public function hitByPlayer(Player $player)
    {
        $hitPoints = $player->getLevel()->getNumeric() * random_int(0, 2);

        $this->registerPlayerHit($player, $hitPoints);
        $this->hit($hitPoints);
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
