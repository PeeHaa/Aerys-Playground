<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Join2 implements Command
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
        return $command->getCommand() === 'join2'
            && $command->hasParameters()
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command): \Generator
    {
        if ($command->getNumberOfParameters() > 1) {
            return ['Username cannot contain spaces. Please enter your username.', [
                'nextPrefix' => 'join2 ',
            ]];
        }

        if (!yield from $this->storage->exists($command->getFirstParameter())) {
            return ['Unknown username. Please enter your username.', [
                'nextPrefix' => 'join2 ',
            ]];
        }

        return ['Please enter your password.', [
            'nextPrefix' => 'join3 ' . $command->getFirstParameter() . ' ',
        ]];
    }
}
