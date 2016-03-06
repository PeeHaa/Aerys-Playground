<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Character\Player\AccessLevel;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Position\Point;

class Join3 implements Command
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
        return $command->getCommand() === 'join3'
            && $command->hasParameters()
            && $this->gate->equalsAccessLevel($player);
    }

    public function execute(UserCommand $command, TrainingYard $map, Player $player): \Generator
    {
        if ($command->getNumberOfParameters() > 2) {
            return ['Password cannot contain spaces. Please enter your password.', [
                'nextPrefix' => 'join3 ' . $command->getFirstParameter() . ' ',
            ]];
        }

        if (!yield from $this->storage->exists($command->getFirstParameter())) {
            return ['Unknown username. Please enter your username.', [
                'nextPrefix' => 'join2 ',
            ]];
        }

        if (!yield from $this->storage->logIn($command->getParameterByIndex(0), $command->getParameterByIndex(1))) {
            return ['Invalid credentials. Please enter your username', [
                'nextPrefix' => 'join2 ',
            ]];
        }

        $user = yield from $this->storage->get($command->getFirstParameter());

        $player->logIn($user['username'], AccessLevel::USER, $user['xp'], new Point($user['positionX'], $user['positionY']));

        $map->addPlayer($player);

        return ['Welcome #ff0' . $user['username'] . '#fff. You successfully joined the realm of Aerys. Use the #f00look#fff command to look around.', []];
    }
}
