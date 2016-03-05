<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Parameter;

class Direction
{
    private $value;

    public function isParameterValid(string $parameter): bool
    {
        return in_array($parameter, ['north', 'east', 'south', 'west'], true);
    }

    public function setValue(string $parameter)
    {
        $this->value = strtolower($parameter);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getMovementMethod(): string
    {
        return 'move' . ucfirst($this->value);
    }
}

