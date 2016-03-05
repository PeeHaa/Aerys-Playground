<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command;

use AerysPlayground\Message\Incoming;

class Command
{
    private $command;

    private $parameters = [];

    public function __construct(Incoming $message)
    {
        $commandData = explode(' ', trim($message->getContent()));

        $this->command = $commandData[0];

        if (count($commandData) > 1) {
            $this->parameters = array_slice($commandData, 1);
        }
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function hasParameters(): bool
    {
        return (bool) count($this->parameters);
    }

    public function getNumberOfParameters(): int
    {
        return count($this->parameters);
    }

    public function getFirstParameter(): string
    {
        return $this->getParameterByIndex(0);
    }

    public function getParameterByIndex(int $index): string
    {
        if (!isset($this->parameters[$index])) {
            return '';
        }

        return $this->parameters[$index];
    }
}
