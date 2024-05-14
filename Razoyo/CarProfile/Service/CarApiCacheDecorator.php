<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Razoyo\CarProfile\Api\CarApiServiceInterface;
use Magento\Framework\App\CacheInterface;
use Razoyo\CarProfile\Api\Data\CarProfileInterface;

class CarApiCacheDecorator implements CarApiServiceInterface
{
    /**
     * @param CarApiService $carApiService
     * @param CacheInterface $cache
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly CarApiService $carApiService,
        private readonly CacheInterface $cache,
        private readonly ScopeConfigInterface $scopeConfig
    )
    {
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function listCarMakes(): array
    {
        $cacheKey = 'list_car_makes';
        return $this->fetchFromCache($cacheKey, fn() => $this->carApiService->listCarMakes());
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function listCars(?string $make = null): array
    {
        $cacheKey = 'list_cars_' . ($make ?: 'all');
        return $this->fetchFromCache($cacheKey, fn() => $this->carApiService->listCars($make));
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function getCarDetails(string $carId): CarProfileInterface
    {
        $cacheKey = 'car_details_' . $carId;
        $cached = $this->cache->load($cacheKey);
        if ($cached) {
            $data = json_decode($cached, true);
            return $this->carApiService->mapArrayToCarProfile($data);
        }

        $carDetails = $this->carApiService->getCarDetails($carId);
        $this->cache->save(json_encode(
            $this->carApiService->convertCarProfileToArray($carDetails)
        ), $cacheKey, [], $this->getCacheLifetime());
        return $carDetails;
    }

    /**
     * @param string $cacheKey
     * @param callable $callback
     * @return mixed
     */
    private function fetchFromCache(string $cacheKey, callable $callback): mixed
    {
        $cached = $this->cache->load($cacheKey);
        if ($cached) {
            return json_decode($cached, true);
        }

        $data = $callback();
        $this->cache->save(json_encode($data), $cacheKey, [], $this->getCacheLifetime());
        return $data;
    }

    /**
     * @return int
     */
    private function getCacheLifetime(): int
    {
        return (int)$this->scopeConfig->getValue(self::CACHE_LIFETIME_CONFIG_PATH, ScopeInterface::SCOPE_STORE) ?: 3600;
    }
}
