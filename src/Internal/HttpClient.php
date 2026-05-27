<?php

declare(strict_types=1);

namespace Twsms\Internal;

interface HttpClient
{
    /**
     * @param array<string, scalar> $payload
     */
    public function post(string $url, array $payload): string;
}
