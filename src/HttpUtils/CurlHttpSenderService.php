<?php

namespace MaCroTux\HttpUtils;

class CurlHttpSenderService
{
    public function __invoke(string $url): string
    {
        $ch = curl_init();

        $optArray = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];

        curl_setopt_array($ch, $optArray);
        $httpResponse = curl_exec($ch);
        curl_close($ch);

        return $httpResponse;
    }
}