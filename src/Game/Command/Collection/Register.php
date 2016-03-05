<?php declare(strict_types=1);

namespace AerysPlayground\Game\Command\Collection;

use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Command as UserCommand;
use AerysPlayground\Game\Character\Player\Player;

class Register implements Command
{
    private $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function doesMatch(UserCommand $command, Player $player): bool
    {
        return $command->getCommand() === 'register' && $this->gate->equalsAccessLevel($player);
    }

    public function execute(): array
    {
        return ['Please enter the username you would like to use.', [
            'nextPrefix' => 'register2 ',
        ]];
    }
}
