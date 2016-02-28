<?php declare(strict_types=1);

namespace AerysPlayground\Message;

interface Outgoing
{
    public function buildMessage(): string;
}
