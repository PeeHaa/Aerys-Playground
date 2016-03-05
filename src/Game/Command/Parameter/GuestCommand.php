<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Parameter;

class GuestCommand
{
    private $value;

    public function isParameterValid(string $parameter): bool
    {
        return in_array($parameter, ['help', 'register', 'join', 'clear'], true);
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

