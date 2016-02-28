<?php declare(strict_types=1);

namespace AerysPlayground;

use AerysPlayground\Game\Command\Executor;
use AerysPlayground\Game\Command\Collection\Help;
use AerysPlayground\Game\Command\Collection\Join;
use AerysPlayground\Game\Command\Collection\Look;
use AerysPlayground\Game\Command\Collection\Walk;
use AerysPlayground\Game\Map\Park;
use AerysPlayground\Game\Tile\Factory;
use function Aerys\router;
use function Aerys\websocket;

require_once __DIR__ . '/vendor/autoload.php';

$map = new Park(new Factory());
$executor = new Executor($map);

$executor->registerCommand(new Help);
$executor->registerCommand(new Join);
$executor->registerCommand(new Look);
$executor->registerCommand(new Walk);

$router = router()
    ->route('GET', '/ws', websocket(new Controller($executor)))
;
