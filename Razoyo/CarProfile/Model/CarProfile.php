<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\Model\AbstractModel;
use Razoyo\CarProfile\Api\Data\CarProfileInterface;

class CarProfile extends AbstractModel implements CarProfileInterface
{
    protected $_idFieldName = self::PROFILE_ID;

    protected function _construct()
    {
        $this->_init(ResourceModel\CarProfile::class);
    }

    /**
     * @inheritDoc
     */
    public function getProfileId(): ?int
    {
        return $this->getData(self::PROFILE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProfileId(int $profileId): CarProfileInterface
    {
        return $this->setData(self::PROFILE_ID, $profileId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $customerId): CarProfileInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getCarId(): string
    {
        return (string)$this->getData(self::CAR_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCarId(string $carId): CarProfileInterface
    {
        return $this->setData(self::CAR_ID, $carId);
    }

    /**
     * @inheritDoc
     */
    public function getCarMake(): string
    {
        return (string)$this->getData(self::CAR_MAKE);
    }

    /**
     * @inheritDoc
     */
    public function setCarMake(string $carMake): CarProfileInterface
    {
        return $this->setData(self::CAR_MAKE, $carMake);
    }

    /**
     * @inheritDoc
     */
    public function getCarModel(): string
    {
        return (string)$this->getData(self::CAR_MODEL);
    }

    /**
     * @inheritDoc
     */
    public function setCarModel(string $carModel): CarProfileInterface
    {
        return $this->setData(self::CAR_MODEL, $carModel);
    }

    /**
     * @inheritDoc
     */
    public function getCarYear(): int
    {
        return (int)$this->getData(self::CAR_YEAR);
    }

    /**
     * @inheritDoc
     */
    public function setCarYear(int $carYear): CarProfileInterface
    {
        return $this->setData(self::CAR_YEAR, $carYear);
    }

    /**
     * @inheritDoc
     */
    public function getCarImage(): ?string
    {
        return (string)$this->getData(self::CAR_IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setCarImage(?string $carImage): CarProfileInterface
    {
        return $this->setData(self::CAR_IMAGE, $carImage);
    }

    /**
     * @inheritDoc
     */
    public function getCarPrice(): ?float
    {
        //todo: It will be better to use a library for working with decimal numbers.
        return (float)$this->getData(self::CAR_PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setCarPrice(?float $carPrice): CarProfileInterface
    {
        return $this->setData(self::CAR_PRICE, $carPrice);
    }

    /**
     * @inheritDoc
     */
    public function getCarSeats(): ?int
    {
        return (int)$this->getData(self::CAR_SEATS);
    }

    /**
     * @inheritDoc
     */
    public function setCarSeats(?int $carSeats): CarProfileInterface
    {
        return $this->setData(self::CAR_SEATS, $carSeats);
    }

    /**
     * @inheritDoc
     */
    public function getCarMpg(): ?int
    {
        return (int)$this->getData(self::CAR_MPG);
    }

    /**
     * @inheritDoc
     */
    public function setCarMpg(?int $carMpg): CarProfileInterface
    {
        return $this->setData(self::CAR_MPG, $carMpg);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): CarProfileInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): CarProfileInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
