<?php

namespace Razoyo\CarProfile\ViewModel;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;
use Razoyo\CarProfile\Api\CarApiServiceInterface;
use Razoyo\CarProfile\Api\CarProfileRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Razoyo\CarProfile\Api\Data\CarProfileInterface;
use Psr\Log\LoggerInterface;

class MyCarData implements ArgumentInterface
{
    /**
     * @param CustomerSession $customerSession
     * @param CarApiServiceInterface $carApiService
     * @param CarProfileRepositoryInterface $carProfileRepository
     * @param UrlInterface $urlBuilder
     * @param FormKey $formKey
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly CarApiServiceInterface $carApiService,
        private readonly CarProfileRepositoryInterface $carProfileRepository,
        private readonly UrlInterface $urlBuilder,
        private readonly FormKey $formKey,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @return array|null
     */
    public function getAvailableCars(): ?array
    {
        try {
            return $this->carApiService->listCars();
        } catch (LocalizedException $e) {
            $this->logger->error('Failed to retrieve available cars: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getListCarMakes(): ?array
    {
        try {
            return $this->carApiService->listCarMakes();
        } catch (LocalizedException $e) {
            $this->logger->error('Failed to retrieve car makes: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $carId
     * @return CarProfileInterface|null
     */
    public function getCarDetails(string $carId): ?CarProfileInterface
    {
        try {
            return $this->carApiService->getCarDetails($carId);
        } catch (LocalizedException $e) {
            $this->logger->error('Failed to retrieve car details for car ID ' . $carId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @return CarProfileInterface|null
     */
    public function getCustomerCarProfile(): ?CarProfileInterface
    {
        try {
            $customerId = $this->customerSession->getCustomerId();
            return $this->carProfileRepository->getByCustomerId($customerId);
        } catch (LocalizedException $e) {
            $this->logger->error('Failed to retrieve customer car profile for customer ID ' . $customerId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @return string
     */
    public function getSaveActionUrl(): string
    {
        return $this->urlBuilder->getUrl('carprofile/account/save');
    }

    /**
     * @return string
     */
    public function getCarListApiUrl(): string
    {
        return $this->urlBuilder->getBaseUrl() . 'rest/V1/cars/list';
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getCsrfToken(): string
    {
        try {
            return $this->formKey->getFormKey();
        } catch (LocalizedException $e) {
            $this->logger->error('Failed to generate CSRF token: ' . $e->getMessage());
            throw $e;
        }
    }
}
