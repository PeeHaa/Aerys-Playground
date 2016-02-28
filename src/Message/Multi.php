<?php declare(strict_types=1);

namespace AerysPlayground\Message;

class Multi implements Outgoing
{
    private $message;

    private $extraData = [];

    public function __construct(string $message, array $extraData = [])
    {
        $this->message   = $message;
        $this->extraData = $extraData;
    }

    public function buildMessage(): string
    {
        return json_encode([
            'message'   => $this->message,
            'extraData' => $this->extraData,
        ]);
    }
}
