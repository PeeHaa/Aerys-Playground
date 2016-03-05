<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Register2 implements Command
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
        return $command->getCommand() === 'register2'
            && $command->hasParameters()
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command): \Generator
    {
        if ($command->getNumberOfParameters() === 0) {
            return ['Please type a username.', [
                'nextPrefix' => 'register2 ',
            ]];
        }

        if ($command->getNumberOfParameters() > 1) {
            return ['Username cannot contain spaces.', [
                'nextPrefix' => 'register2 ',
            ]];
        }

        if (yield from $this->storage->exists($command->getFirstParameter())) {
            return ['Username is already taken. Please choose another name.', [
                'nextPrefix' => 'register2 ',
            ]];
        }

        return ['Please enter the password you would like to use.', [
            'nextPrefix' => 'register3 ' . $command->getFirstParameter() . ' ',
        ]];
    }
}

