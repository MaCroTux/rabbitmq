<?php

namespace MaCroTux\Telegram;

class Config
{
    /** @var string */
    private $token;
    /** @var string */
    private $chatId;

    public function __construct(string $token, string $chatId)
    {
        $this->token = $token;
        $this->chatId = $chatId;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function chatId(): string
    {
        return $this->chatId;
    }


}