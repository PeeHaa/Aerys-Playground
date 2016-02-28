<?php declare(strict_types=1);

namespace AerysPlayground\Message;

class Incoming
{
    private $clientId;

    private $token;

    private $content;

    public function __construct(int $clientId, string $message)
    {
        $this->clientId = $clientId;

        $decodedMessage = json_decode($message, true);

        if (!$this->isValid($decodedMessage)) {
            throw new InvalidMessageException();
        }

        $this->token   = $decodedMessage['token'];
        $this->content = $decodedMessage['content'];
    }

    private function isValid(array $message): bool
    {
        return json_last_error() === JSON_ERROR_NONE && isset($message['content']) && isset($message['token']);
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
