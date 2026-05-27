<?php

declare(strict_types=1);

namespace Twsms\Internal;

use Twsms\Exception\TransportException;

final class CurlHttpClient implements HttpClient
{
    public function post(string $url, array $payload): string
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new TransportException('Failed to initialize cURL.');
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new TransportException('TWsms request failed: ' . $error);
        }

        if (!is_string($response)) {
            throw new TransportException('Unexpected cURL response type.');
        }

        if ($httpCode !== 200) {
            throw new TransportException('Unexpected HTTP status from TWsms: ' . $httpCode);
        }

        return $response;
    }
}
