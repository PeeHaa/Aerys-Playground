<?php declare(strict_types=1);

namespace AerysPlayground\Game\Position;

class Point
{
    private $x;

    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function moveTo(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function moveNorth()
    {
        $this->y--;
    }

    public function moveEast()
    {
        $this->x++;
    }

    public function moveSouth()
    {
        $this->y++;
    }

    public function moveWest()
    {
        $this->x--;
    }
}
