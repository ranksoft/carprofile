<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

use Razoyo\CarProfile\Api\Data\CarProfileInterface;

interface CarApiServiceInterface
{
    public const API_URL_CONFIG_PATH = 'car_profile/api/url';
    public const CACHE_LIFETIME_CONFIG_PATH = 'car_profile/cache/lifetime';

    /**
     * Fetch the list of car makes.
     *
     * @return array
     */
    public function listCarMakes(): array;

    /**
     * Fetch the list of cars.
     *
     * @param string|null $make
     * @return array
     */
    public function listCars(?string $make = null): array;

    /**
     * Fetch car details by ID.
     *
     * @param string $carId
     * @return CarProfileInterface
     */
    public function getCarDetails(string $carId): CarProfileInterface;
}
