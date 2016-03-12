<?php

namespace AerysPlayground;

use Aerys\Websocket;
use Aerys\Request;
use Aerys\Response;
use Aerys\Websocket\Endpoint;
use Aerys\Websocket\Message;
use AerysPlayground\Game\Command\Executor;
use AerysPlayground\Storage\User as Storage;
use AerysPlayground\Game\Character\Player\AccessLevel;
use AerysPlayground\Game\Character\Player\User;
use AerysPlayground\Message\Incoming;
use AerysPlayground\Message\Single;
use AerysPlayground\Message\Multi;
use function Amp\repeat;

class Controller implements Websocket
{
    private $executor;

    private $storage;

    private $endpoint;

    private $clients = [];

    public function __construct(Executor $executor, Storage $storage)
    {
        $this->executor = $executor;
        $this->storage  = $storage;
    }

    public function onStart(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;

        repeat(function() {
            yield from $this->handleAttacks();
        }, 2000);

        repeat(function() {
            $this->resurrectBots();
        }, 1000);
    }

    private function handleAttacks()
    {
        foreach ($this->clients as $clientId => $client) {
            if (!$client['player']->isAttacking()) {
                continue;
            }

            $opponent  = $client['player']->getAttacker();
            $hitPoints = $opponent->hitByPlayer($client['player']);

            if ($hitPoints === 0) {
                $this->endpoint->send(null, (new Single($clientId, $client['token'], 'Your attack misses #f30' . $opponent->getName() . '#fff.'))->buildMessage());
            } else {
                $this->endpoint->send(null, (new Single($clientId, $client['token'], 'You hit #f30' . $opponent->getName() . '#fff (' . $hitPoints . ' HP)'))->buildMessage());
            }

            if (!$opponent->isAlive()) {
                $experienceGained = $opponent->getEarnedExperience($client['player']);

                $this->endpoint->send(null, (new Single($clientId, $client['token'], 'You killed #f30' . $opponent->getName() . '#fff and gained ' . $experienceGained . ' xp.'))->buildMessage());

                $client['player']->stopAttack($experienceGained);

                yield from $this->storage->setExperiencePoints($client['player']);
            }
        }
    }

    private function resurrectBots()
    {
        $resurrectedBots = $this->executor->resurrectBots();

        foreach ($resurrectedBots as $bot) {
            $players = $this->executor->getPlayersAtPoint($bot->getPoint());

            foreach ($players as $player) {
                $this->endpoint->send(null, (new Single($player->getid(), $this->clients[$player->getid()]['token'], '#f30' . $bot->getName() . '#fff entered the scene.'))->buildMessage());
            }
        }
    }

    public function onHandshake(Request $request, Response $response)
    {
        return $request->getConnectionInfo()['client_addr'];
    }

    public function onOpen(int $clientId, $handshakeData)
    {
        $this->clients[$clientId] = [
            'token'  => bin2hex(random_bytes(56)),
            'player' => new User($clientId, 'Player ' . $clientId, AccessLevel::GUEST),
        ];

        $this->sendUserConnectedMessage($clientId);

        $this->sendWelcomeMessage($clientId);
    }

    private function sendUserConnectedMessage(int $clientId)
    {
        $this->endpoint->send(null, (new Multi('#0f0Player ' . $clientId . ' connected'))->buildMessage());
    }

    private function sendWelcomeMessage(int $clientId)
    {
        $message = new Single(
            $clientId,
            $this->clients[$clientId]['token'],
            'Welcome ' . $this->clients[$clientId]['player']->getName() . '. Use the #f00join #fffcommand to sign in or the #f00register #fffcommand to create an account.'
        );

        $this->endpoint->send($clientId, $message->buildMessage());
    }

    public function onData(int $clientId, Message $msg)
    {
        $responseData = yield from $this->executor->runCommand(new Incoming($clientId, yield  $msg), $this->clients[$clientId]['player']);

        $response = new Single(
            $clientId,
            $this->clients[$clientId]['token'],
            $responseData[0],
            $responseData[1]
        );

        $this->endpoint->send($response->getClientId(), $response->buildMessage());
    }

    public function onClose(int $clientId, int $code, string $reason)
    {
        $this->endpoint->send(null, '#0f0' . $this->clients[$clientId]['player']->getName() . ' disconnected');
    }

    public function onStop()
    {
    }
}