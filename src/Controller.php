<?php

namespace AerysPlayground;

use Aerys\Websocket;
use Aerys\Request;
use Aerys\Response;
use Aerys\Websocket\Endpoint;
use Aerys\Websocket\Message;
use AerysPlayground\Game\Character\Player\AccessLevel;
use AerysPlayground\Game\Character\Player\User;
use AerysPlayground\Game\Command\Executor;
use AerysPlayground\Message\Incoming;
use AerysPlayground\Message\Single;
use AerysPlayground\Message\Multi;

class Controller implements Websocket
{
    private $executor;

    private $endpoint;

    private $clients = [];

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
    }

    public function onStart(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
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