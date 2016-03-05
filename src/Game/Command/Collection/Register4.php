<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Register4 implements Command
{
    private $gate;

    private $storage;

    public function __construct(Gate $gate, Storage $storage)
    {
        $this->gate    = $gate;
        $this->storage = $storage;
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'register4'
            && $command->getNumberOfParameters() > 2
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command): \Generator
    {
        if ($command->getNumberOfParameters() > 3) {
            return ['Password cannot contain spaces.', [
                'nextPrefix' => 'register3 ' . $command->getFirstParameter(),
            ]];
        }

        if ($command->getParameterByIndex(1) !== $command->getParameterByIndex(2)) {
            return ['Passwords don\'t match.', [
                'nextPrefix' => 'register3 ' . $command->getFirstParameter(),
            ]];
        }

        if (yield from $this->storage->exists($command->getFirstParameter())) {
            return ['Username is already taken. Please choose another name.', [
                'nextPrefix' => 'register2 ',
            ]];
        }

        yield from $this->storage->add($command->getParameterByIndex(0), $command->getParameterByIndex(1));

        return ['Account created you can now log in.', []];
    }
}
