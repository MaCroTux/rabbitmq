<?php

namespace MaCroTux\Telegram;

use MaCroTux\HttpUtils\CurlHttpSenderService;

class SendMessageService
{
    private const API_TELEGRAM = 'https://api.telegram.org/bot%s/';
    private const ENDPOINT_SEND_MESSAGE = 'sendMessage?chat_id=%s&text=%s';

    /** @var Config */
    private $config;
    /** @var CurlHttpSenderService */
    private $httpSenderService;

    public function __construct(
        Config $telegramConfig,
        CurlHttpSenderService $httpSenderService
    ) {
        $this->config = $telegramConfig;
        $this->httpSenderService = $httpSenderService;
    }

    public function __invoke(string $message): void
    {
        $url = $this->buildUrl(
            $this->config->token(),
            $this->config->chatId(),
            $message
        );

        $this->httpSenderService->__invoke($url);
    }

    private function buildUrl(string $token, string $chatID, string $message): string
    {
        return sprintf(self::API_TELEGRAM, $token) .
            sprintf(
                self::ENDPOINT_SEND_MESSAGE,
                $chatID,
                urlencode($message)
            );
    }
}