<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command;

use AerysPlayground\Game\Map\Park;
use AerysPlayground\Game\Character\Player;
use AerysPlayground\Message\Incoming;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Command\Collection\Command;
use AerysPlayground\Game\Command\Collection\Help;
use AerysPlayground\Game\Command\Collection\Join;
use AerysPlayground\Game\Command\Collection\Register;
use AerysPlayground\Game\Command\Collection\Look;
use AerysPlayground\Game\Command\Collection\Walk;

class Executor
{
    private $map;

    private $commands = [];

    public function __construct(Park $map)
    {
        $this->map = $map;
    }

    public function registerCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    public function runCommand(Incoming $message, int $clientId): string
    {
        $userCommand = new UserCommand($message);

        foreach ($this->commands as $command) {
            if ($command->doesMatch($userCommand)) {
                return $this->executeCommand($command, $userCommand, $clientId);
            }
        }

        return 'Unknown command. Type `help` for available commands.';
    }

    private function executeCommand(Command $command, UserCommand $userCommand, int $clientId): string
    {
        if ($command instanceof Help) {
            return $command->execute($userCommand);
        }

        if ($command instanceof Join) {
            return $command->execute($this->map, $clientId);
        }

        if ($command instanceof Look) {
            return $command->execute($userCommand, $this->map, $this->map->getPlayers()[$clientId]);
        }

        if ($command instanceof Walk) {
            return $command->execute($userCommand, $this->map, $this->map->getPlayers()[$clientId]);
        }
    }
}
