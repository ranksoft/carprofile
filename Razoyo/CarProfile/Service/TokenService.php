<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Razoyo\CarProfile\Api\CarApiServiceInterface;
use Razoyo\CarProfile\Api\TokenServiceInterface;
use Magento\Customer\Model\Session;

class TokenService implements TokenServiceInterface
{
    /**
     * @param ClientInterface $apiClient
     * @param Session $session
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ClientInterface $apiClient,
        private readonly Session $session,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function fetchToken(): void
    {
        $url = $this->getApiUrl();
        $this->apiClient->get($url);

        $statusCode = $this->apiClient->getStatus();
        $responseBody = json_decode($this->apiClient->getBody(), true);

        if ($statusCode !== 200) {
            $this->logger->error('Failed to fetch token', ['status' => $statusCode, 'response' => $responseBody]);
            throw new LocalizedException(__('Failed to fetch token'));
        }

        $this->handleToken($this->apiClient->getHeaders());
    }

    /**
     * @param array $data
     * @return void
     */
    public function handleToken(array $data): void
    {
        $token = $data[self::API_TOKEN_HEADER] ?? null;
        if ($token) {
            $this->session->setApiToken($token);
            $this->logger->info('API token updated from response');
        }
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return (string)$this->session->getApiToken();
    }

    /**
     * @return string
     */
    private function getApiUrl(): string
    {
        return $this->scopeConfig->getValue(
            CarApiServiceInterface::API_URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE
            ) . CarApiService::LIST_CARS_ENDPOINT;
    }
}
