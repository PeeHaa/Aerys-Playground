<?php declare(strict_types=1);

namespace AerysPlayground\Message;

class Single implements Outgoing
{
    private $clientId;

    private $token;

    private $message;

    private $extraData = [];

    public function __construct(int $clientId, string $token, string $message, array $extraData = [])
    {
        $this->clientId  = $clientId;
        $this->token     = $token;
        $this->message   = $message;
        $this->extraData = $extraData;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function buildMessage(): string
    {
        return json_encode([
            'token'     => $this->token,
            'message'   => $this->message,
            'extraData' => $this->extraData,
        ]);
    }
}
