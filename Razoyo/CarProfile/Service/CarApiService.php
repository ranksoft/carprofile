<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Service;

use JetBrains\PhpStorm\ArrayShape;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Store\Model\ScopeInterface;
use Razoyo\CarProfile\Api\CarApiServiceInterface;
use Razoyo\CarProfile\Api\Data\CarProfileInterface;
use Razoyo\CarProfile\Model\CarProfileFactory;
use Razoyo\CarProfile\Api\TokenServiceInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CarApiService implements CarApiServiceInterface
{
    public const LIST_CARS_ENDPOINT = '/cars';
    private const CAR_DETAILS_ENDPOINT = '/cars/%s';
    private const STATUS_OK = 200;
    private const STATUS_UNAUTHORIZED = 403;

    /**
     * @param ClientInterface $apiClient
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param CarProfileFactory $carProfileFactory
     * @param TokenServiceInterface $tokenService
     */
    public function __construct(
        private readonly ClientInterface $apiClient,
        private readonly LoggerInterface $logger,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly CarProfileFactory $carProfileFactory,
        private readonly TokenServiceInterface $tokenService
    )
    {
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function listCarMakes(): array
    {
        return $this->fetchData(self::LIST_CARS_ENDPOINT, function ($data) {
            return $data['makes'] ?? [];
        }, 'Failed to fetch car makes list.');
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function listCars(?string $make = null): array
    {
        $endpoint = self::LIST_CARS_ENDPOINT . ($make ? '?make=' . urlencode($make) : '');
        return $this->fetchData($endpoint, function ($data) {
            return $this->addLabelToListCars($data['cars'] ?? []);
        }, 'Failed to fetch car list.');
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function getCarDetails(string $carId): CarProfileInterface
    {
        $endpoint = sprintf(self::CAR_DETAILS_ENDPOINT, urlencode($carId));
        $fetcher = function ($data) {
            return $this->mapArrayToCarProfile($data['car'] ?? []);
        };
        return $this->fetchData($endpoint, $fetcher, 'Failed to fetch car details.', true);
    }

    /**
     * @param string $endpoint
     * @param callable $dataExtractor
     * @param string $errorMessage
     * @param bool $retryOnAuthFail
     * @return mixed
     * @throws LocalizedException
     */
    private function fetchData(string $endpoint, callable $dataExtractor, string $errorMessage, bool $retryOnAuthFail = false): mixed
    {
        $url = $this->getApiUrl() . $endpoint;
        $this->apiClient->get($url);
        $status = $this->apiClient->getStatus();

        if ($status === self::STATUS_OK) {
            $this->tokenService->handleToken($this->apiClient->getHeaders());
            return $dataExtractor(json_decode($this->apiClient->getBody(), true));
        }

        if ($retryOnAuthFail && $status === self::STATUS_UNAUTHORIZED) {
            return $this->retryWithNewToken($url, $dataExtractor);
        }

        $this->logger->error('API error: ' . $this->apiClient->getBody());
        throw new LocalizedException(__($errorMessage));
    }

    /**
     * @param string $url
     * @param callable $dataExtractor
     * @return mixed
     * @throws LocalizedException
     */
    private function retryWithNewToken(string $url, callable $dataExtractor): mixed
    {
        $this->tokenService->fetchToken();
        $this->apiClient->addHeader('Authorization', 'Bearer ' . $this->tokenService->getToken());
        $this->apiClient->get($url);

        if ($this->apiClient->getStatus() === self::STATUS_OK) {
            return $dataExtractor(json_decode($this->apiClient->getBody(), true));
        }

        throw new LocalizedException(__('Failed to refresh token and fetch data.'));
    }

    /**
     * @param array $carData
     * @return CarProfileInterface
     */
    public function mapArrayToCarProfile(array $carData): CarProfileInterface
    {
        $carProfile = $this->carProfileFactory->create();
        $carProfile->setCarId((string)$carData['id'])
            ->setCarMake((string)$carData['make'])
            ->setCarModel((string)$carData['model'])
            ->setCarYear((int)$carData['year'])
            ->setCarPrice((float)$carData['price'] ?? null)
            ->setCarSeats((int)$carData['seats'] ?? null)
            ->setCarMpg((int)$carData['mpg'] ?? null)
            ->setCarImage((string)$carData['image'] ?? null);
        return $carProfile;
    }

    /**
     * @param CarProfileInterface $carProfile
     * @return array
     */
    #[ArrayShape(['id' => "mixed", 'make' => "string", 'model' => "string", 'year' => "int", 'price' => "float|null", 'seats' => "int|null", 'mpg' => "int|null", 'image' => "null|string"])]
    public function convertCarProfileToArray(CarProfileInterface $carProfile): array
    {
       return [
            'id' => $carProfile->getId(),
            'make' => $carProfile->getCarMake(),
            'model' => $carProfile->getCarModel(),
            'year' => $carProfile->getCarYear(),
            'price' => $carProfile->getCarPrice(),
            'seats' => $carProfile->getCarSeats(),
            'mpg' => $carProfile->getCarMpg(),
            'image' => $carProfile->getCarImage()
        ];
    }

    /**
     * @param array $listCars
     * @return array
     */
    private function addLabelToListCars(array $listCars): array
    {
        return array_map(fn($car) => array_merge($car, ['label' => "{$car['make']} {$car['model']} {$car['year']}"]), $listCars);
    }

    /**
     * @return string
     */
    private function getApiUrl(): string
    {
        return (string)$this->scopeConfig->getValue(self::API_URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
