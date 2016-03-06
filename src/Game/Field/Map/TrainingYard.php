<?php declare(strict_types=1);

namespace AerysPlayground\Game\Field\Map;

use AerysPlayground\Game\Field\Tile\Collection as TileCollection;
use AerysPlayground\Game\Field\Bot\Collection as BotCollection;
use AerysPlayground\Game\Field\Player\Collection as PlayerCollection;
use AerysPlayground\Game\Character\Player\Player;
use AerysPlayground\Game\Position\Point;
use AerysPlayground\Game\Tile\Tile;

class TrainingYard// implements Map
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
        ['x', 'd', 'd', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', 'd', 'd', 'x'],
        ['x', ' ', ' ', 'x'],
        ['x', 'x', 'x', 'x'],
    ];

    private $tiles;

    private $bots;

    private $players;

    public function __construct(TileCollection $tiles, BotCollection $bots, PlayerCollection $players)
    {
        $this->tiles   = $tiles;
        $this->bots    = $bots;
        $this->players = $players;

        $this->tiles->addTilesFromMap(self::MAP);
        $this->bots->addBotsFromMap(self::BOT_SPAWNING_POINTS);
    }

    public function addPlayer(Player $player)
    {
        $this->players->addPlayer($player);
    }

    public function getTileAtPoint(Point $point): Tile
    {
        return $this->tiles->getTileAtPoint($point);
    }

    public function getBotsAliveAtPoint(Point $point): array
    {
        return $this->bots->getBotsAliveAtPoint($point);
    }

    public function resurrectBots(): array
    {
        return $this->bots->resurrect();
    }

    public function getPlayersAtPoint(Point $point): array
    {
        return $this->players->getPlayersAtPoint($point);
    }
}
