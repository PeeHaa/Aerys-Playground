<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command;

use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Level\Ladder;
use AerysPlayground\Game\Character\Player;
use AerysPlayground\Game\Character\Player\User;
use AerysPlayground\Game\Position\Point;
use AerysPlayground\Message\Incoming;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Command\Collection\About;
use AerysPlayground\Game\Command\Collection\Command;
use AerysPlayground\Game\Command\Collection\HelpGuest;
use AerysPlayground\Game\Command\Collection\HelpUser;
use AerysPlayground\Game\Command\Collection\Register;
use AerysPlayground\Game\Command\Collection\Register2;
use AerysPlayground\Game\Command\Collection\Register3;
use AerysPlayground\Game\Command\Collection\Register4;
use AerysPlayground\Game\Command\Collection\Join;
use AerysPlayground\Game\Command\Collection\Join2;
use AerysPlayground\Game\Command\Collection\Join3;
use AerysPlayground\Game\Command\Collection\Info;
use AerysPlayground\Game\Command\Collection\Look;
use AerysPlayground\Game\Command\Collection\Walk;
use AerysPlayground\Game\Command\Collection\Attack;

class Executor
{
    private $map;

    private $ladder;

    private $commands = [];

    public function __construct(TrainingYard $map, Ladder $ladder)
    {
        $this->map    = $map;
        $this->ladder = $ladder;
    }

    public function registerCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    public function runCommand(Incoming $message, User $player): \Generator
    {
        $userCommand = new UserCommand($message);

        foreach ($this->commands as $command) {
            if ($command->doesMatch($userCommand, $player)) {
                $message = yield from $this->executeCommand($command, $userCommand, $player);

                return $message;
            }
        }

        return ['Unknown command. Type `help` for available commands.', []];
    }

    private function executeCommand(Command $command, UserCommand $userCommand, User $player)
    {
        if ($command instanceof About) {
            return $command->execute();
        }

        if ($command instanceof HelpGuest) {
            return $command->execute($userCommand);
        }

        if ($command instanceof HelpUser) {
            return $command->execute($userCommand);
        }

        if ($command instanceof Register) {
            return $command->execute();
        }

        if ($command instanceof Register2) {
            return yield from $command->execute($userCommand);
        }

        if ($command instanceof Register3) {
            return yield from $command->execute($userCommand);
        }

        if ($command instanceof Register4) {
            return yield from $command->execute($userCommand);
        }

        if ($command instanceof Join) {
            return $command->execute();
        }

        if ($command instanceof Join2) {
            return yield from $command->execute($userCommand);
        }

        if ($command instanceof Join3) {
            return yield from $command->execute($userCommand, $this->map, $player, $this->ladder);
        }

        if ($command instanceof Info) {
            return $command->execute($player);
        }

        if ($command instanceof Look) {
            return $command->execute($userCommand, $this->map, $player);
        }

        if ($command instanceof Walk) {
            return yield from $command->execute($userCommand, $this->map, $player);
        }

        if ($command instanceof Attack) {
            return $command->execute($this->map, $player);
        }
    }

    // @todo this really shouldn't be in here
    public function resurrectBots(): array
    {
        return $this->map->resurrectBots();
    }

    // @todo this really shouldn't be in here
    public function getPlayersAtPoint(Point $point): array
    {
        return $this->map->getPlayersAtPoint($point);
    }
}
