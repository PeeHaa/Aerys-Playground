<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Parameter;

class UserCommand
{
    private $value;

    public function isParameterValid(string $parameter): bool
    {
        return in_array($parameter, ['help', 'clear', 'look', 'walk'], true);
    }

    public function setValue(string $parameter)
    {
        $this->value = strtolower($parameter);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

