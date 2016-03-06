<?php declare(strict_types=1);

namespace AerysPlayground;

use AerysPlayground\Storage\User;
use AerysPlayground\Game\Command\Executor;
use AerysPlayground\Game\Field\Map\TrainingYard;
use AerysPlayground\Game\Tile\Factory as TileFactory;
use AerysPlayground\Game\Field\Tile\Collection as TileCollection;
use AerysPlayground\Game\Character\Npc\Factory as BotFactory;
use AerysPlayground\Game\Field\Bot\Collection as BotCollection;
use AerysPlayground\Game\Field\Player\Collection as PlayerCollection;
use AerysPlayground\Game\Character\Player\AccessLevel;
use AerysPlayground\Game\Command\Gate;
use AerysPlayground\Game\Command\Collection\About;
use AerysPlayground\Game\Command\Collection\Clear;
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
use function Aerys\router;
use function Aerys\websocket;


require_once __DIR__ . '/vendor/autoload.php';

$storage = new User(__DIR__ . '/data/users.json');

$executor = new Executor(new TrainingYard(
    new TileCollection(new TileFactory()),
    new BotCollection(new BotFactory()),
    new PlayerCollection()
));

$executor->registerCommand(new About(new Gate(AccessLevel::GUEST)));
$executor->registerCommand(new Clear(new Gate(AccessLevel::GUEST)));
$executor->registerCommand(new HelpGuest(new Gate(AccessLevel::GUEST)));
$executor->registerCommand(new HelpUser(new Gate(AccessLevel::USER)));
$executor->registerCommand(new Register(new Gate(AccessLevel::GUEST)));
$executor->registerCommand(new Register2(new Gate(AccessLevel::GUEST), $storage));
$executor->registerCommand(new Register3(new Gate(AccessLevel::GUEST), $storage));
$executor->registerCommand(new Register4(new Gate(AccessLevel::GUEST), $storage));
$executor->registerCommand(new Join(new Gate(AccessLevel::GUEST)));
$executor->registerCommand(new Join2(new Gate(AccessLevel::GUEST), $storage));
$executor->registerCommand(new Join3(new Gate(AccessLevel::GUEST), $storage));
$executor->registerCommand(new Info(new Gate(AccessLevel::USER)));
$executor->registerCommand(new Look(new Gate(AccessLevel::USER)));
$executor->registerCommand(new Walk(new Gate(AccessLevel::USER), $storage));
$executor->registerCommand(new Attack(new Gate(AccessLevel::USER)));

$router = router()->route('GET', '/ws', websocket(new Controller($executor, $storage)));
