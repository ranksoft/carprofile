<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Api\Data;

interface CarProfileInterface
{
    const PROFILE_ID = 'profile_id';
    const CUSTOMER_ID = 'customer_id';
    const CAR_ID = 'car_id';
    const CAR_MAKE = 'car_make';
    const CAR_MODEL = 'car_model';
    const CAR_YEAR = 'car_year';
    const CAR_PRICE = 'car_price';
    const CAR_SEATS = 'car_seats';
    const CAR_MPG = 'car_mpg';
    const CAR_IMAGE = 'car_image';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get Profile ID
     *
     * @return int|null
     */
    public function getProfileId(): ?int;

    /**
     * Set Profile ID
     *
     * @param int $profileId
     * @return $this
     */
    public function setProfileId(int $profileId): CarProfileInterface;

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): CarProfileInterface;

    /**
     * Get Car ID
     *
     * @return string
     */
    public function getCarId(): string;

    /**
     * Set Car ID
     *
     * @param string $carId
     * @return $this
     */
    public function setCarId(string $carId): CarProfileInterface;

    /**
     * Get Car Make
     *
     * @return string
     */
    public function getCarMake(): string;

    /**
     * Set Car Make
     *
     * @param string $carMake
     * @return $this
     */
    public function setCarMake(string $carMake): CarProfileInterface;

    /**
     * Get Car Model
     *
     * @return string
     */
    public function getCarModel(): string;

    /**
     * Set Car Model
     *
     * @param string $carModel
     * @return $this
     */
    public function setCarModel(string $carModel): CarProfileInterface;

    /**
     * Get Car Year
     *
     * @return int
     */
    public function getCarYear(): int;

    /**
     * Set Car Year
     *
     * @param int $carYear
     * @return $this
     */
    public function setCarYear(int $carYear): CarProfileInterface;

    /**
     * Get Car Price
     *
     * @return float|null
     */
    public function getCarPrice(): ?float;

    /**
     * Set Car Price
     *
     * @param float|null $carPrice
     * @return $this
     */
    public function setCarPrice(?float $carPrice): CarProfileInterface;

    /**
     * Get Car Seats
     *
     * @return int|null
     */
    public function getCarSeats(): ?int;

    /**
     * Set Car Seats
     *
     * @param int|null $carSeats
     * @return $this
     */
    public function setCarSeats(?int $carSeats): CarProfileInterface;

    /**
     * Get Car MPG
     *
     * @return int|null
     */
    public function getCarMpg(): ?int;

    /**
     * Set Car MPG
     *
     * @param int|null $carMpg
     * @return $this
     */
    public function setCarMpg(?int $carMpg): CarProfileInterface;

    /**
     * Get Car Image
     *
     * @return string|null
     */
    public function getCarImage(): ?string;

    /**
     * Set Car Image
     *
     * @param string|null $carImage
     * @return $this
     */
    public function setCarImage(?string $carImage): CarProfileInterface;

    /**
     * Get Created At timestamp
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set Created At timestamp
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): CarProfileInterface;

    /**
     * Get Updated At timestamp
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set Updated At timestamp
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): CarProfileInterface;
}
