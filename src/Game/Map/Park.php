<?php declare(strict_types=1);

namespace AerysPlayground\Game\Map;

use AerysPlayground\Game\Character\Player\User;
use AerysPlayground\Game\Tile\Factory as TileFactory;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Character\Npc\Npc;
use AerysPlayground\Game\Character\Npc\Dummy;

class Park
{
    const MAP = [
        ['x', 'x', 'x', 'x'],
        ['x', 's', 'p', 'x'],
        ['x', 'p', 'p', 'x'],
        ['x', 'p', 'p', 'x'],
        ['x', 'p', 'p', 'x'],
        ['x', 'p', 'p', 'x'],
        ['x', 'x', 'x', 'x'],
    ];

    const BOT_SPAWNING_POINTS = [
        ['x', 'x', 'x', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', 'd', 'd', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', 'x', 'x', 'x'],
    ];

    private $tileFactory;

    private $tiles = [];

    private $players = [];

    private $bots    = [];

    public function __construct(TileFactory $tileFactory)
    {
        $this->tileFactory = $tileFactory;

        $this->buildScenery();
        $this->placeBots();
    }

    private function buildScenery()
    {
        $this->tiles = [];

        foreach (self::MAP as $x => $yRow) {
            $row = [];

            foreach ($yRow as $y => $type) {
                $row[] = $this->tileFactory->build($type);
            }

            $this->tiles[] = $row;
        }
    }

    private function placeBots()
    {
        $dummy = new Dummy(1);

        $dummy->resurrect();
        $dummy->moveTo(4, 1);

        $this->bots[] = $dummy;

        $dummy = new Dummy(2);

        $dummy->resurrect();
        $dummy->moveTo(4, 2);

        $this->bots[] = $dummy;
    }

    public function addPlayer(int $clientId)
    {
        $newPlayer = new User($clientId);

        $newPlayer->moveTo(1, 1);
        $newPlayer->resurrect();

        $this->spawnPlayer($newPlayer);
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getTileAtPosition(int $x, int $y)
    {
        return $this->tiles[$x][$y];
    }

    public function spawnPlayer(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }

    public function updateBots()
    {
        foreach ($this->bots as $bot) {
            $this->moveBot($bot);
            $this->respawnBot($bot);
        }

        $this->cleanUp();
    }

    private function moveBot(Npc $bot)
    {
        if (!$bot->canMove()) {
            return;
        }

        // @todo move bots based on probability
    }

    private function respawnBot(Npc $bot)
    {
        if ($bot->isAlive() || !$bot->doesRespawn() || !$bot->mustRespawn()) {
            return;
        }

        $possibleSpawningPoints = $this->getValidBotSpawningPositions($bot);

        $spawnPoint = $possibleSpawningPoints[random_int(0, count($possibleSpawningPoints))];

        $bot->moveTo($spawnPoint[0], $spawnPoint[1]);
        $bot->resurrect();
    }

    private function getValidBotSpawningPositions(Npc $bot): array
    {
        $validSpawningPoints = [];

        foreach (self::BOT_SPAWNING_POINTS as $x => $ySpawningPositions) {
            foreach ($ySpawningPositions as $y => $type) {
                if ($type !== $bot::SPAWNING_POINTS || $this->hasBot($x, $y)) {
                    continue;
                }

                $validSpawningPoints[] = [$x, $y];
            }
        }

        return $validSpawningPoints;
    }

    private function hasBot(int $x, int $y): bool
    {
        foreach ($this->bots as $bot) {
            if ($bot->isAlive() || $bot->getPositionX() === $x || $bot->getPositionY() === $y) {
                return true;
            }
        }

        return false;
    }

    private function cleanUp()
    {
        foreach ($this->bots as $bot) {
            if ($bot->isAlive()) {
                continue;
            }

            if (!$bot->isAlive() && !$bot->doesRespawn()) {
                continue;
            }

            unset($this->bots[$bot->getId()]);
        }
    }

    public function getPlayersInRange(int $x, int $y, int $range): array
    {
        return array_filter($this->players, function(Player $player) use ($x, $y, $range) {
            return $this->isPlayerInRange($x, $y, $range, $player);
        });
    }

    private function isPlayerInRange(int $x, int $y, int $range, Player $player): bool
    {
        $range--;

        if ($player->getPositionX() < $x - $range) {
            return false;
        }

        if ($player->getPositionX() > $x + $range) {
            return false;
        }

        if ($player->getPositionY() < $y - $range) {
            return false;
        }

        if ($player->getPositionY() > $y + $range) {
            return false;
        }

        return true;
    }
}
