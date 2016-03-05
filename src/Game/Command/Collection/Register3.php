<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Register3 implements Command
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
        return $command->getCommand() === 'register3'
            && $command->getNumberOfParameters() > 1
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command): \Generator
    {
        if ($command->getNumberOfParameters() > 2) {
            return ['Password cannot contain spaces.', [
                'nextPrefix' => 'register3 ' . $command->getFirstParameter(),
            ]];
        }

        if (yield from $this->storage->exists($command->getFirstParameter())) {
            return ['Username is already taken. Please choose another name.', [
                'nextPrefix' => 'register2 ',
            ]];
        }

        return ['Retype your password.', [
            'nextPrefix' => 'register4 ' . $command->getParameterByIndex(0) . ' ' . $command->getParameterByIndex(1) . ' ',
        ]];
    }
}
