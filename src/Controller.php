<?php

namespace AerysPlayground;

use Aerys\Websocket;
use Aerys\Request;
use Aerys\Response;
use Aerys\Websocket\Endpoint;
use Aerys\Websocket\Message;;
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
        $this->clients[$clientId] = bin2hex(random_bytes(56));

        $this->sendUserConnectedMessage($clientId);

        $this->sendWelcomeMessage($clientId);
    }

    private function sendUserConnectedMessage(int $clientId)
    {
        $this->endpoint->send(null, (new Multi('Player ' . $clientId . ' connected'))->buildMessage());
    }

    private function sendWelcomeMessage(int $clientId)
    {
        $message = new Single(
            $clientId,
            $this->clients[$clientId],
            'Welcome Player ' . $clientId . '. Use the `join` command to sign in or the `register` command to create an account.'
        );

        $this->endpoint->send($clientId, $message->buildMessage());
    }

    public function onData(int $clientId, Message $msg)
    {
        $response = new Single(
            $clientId,
            $this->clients[$clientId],
            $this->executor->runCommand(new Incoming($clientId, yield  $msg), $clientId)
        );

        $this->endpoint->send($response->getClientId(), $response->buildMessage());
    }

    public function onClose(int $clientId, int $code, string $reason)
    {
        $this->endpoint->send(null, 'Player ' . $clientId . ' disconnected');
    }

    public function onStop()
    {
    }
}