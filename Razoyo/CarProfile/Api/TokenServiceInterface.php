<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

interface TokenServiceInterface
{
    public const API_TOKEN_HEADER = 'your-token';

    /**
     * @return void
     */
    public function fetchToken(): void;

    /**
     * @param array $data
     * @return void
     */
    public function handleToken(array $data): void;

    /**
     * @return string
     */
    public function getToken(): string;
}
