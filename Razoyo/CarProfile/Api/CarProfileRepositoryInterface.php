<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

use Razoyo\CarProfile\Api\Data\CarProfileInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface CarProfileRepositoryInterface
{
    /**
     * Save CarProfile data
     *
     * @param CarProfileInterface $carProfile
     * @return CarProfileInterface
     */
    public function save(CarProfileInterface $carProfile): CarProfileInterface;

    /**
     * Load CarProfile data by given Customer ID
     *
     * @param int $customerId
     * @return CarProfileInterface|null
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId): ?CarProfileInterface;

    /**
     * Delete CarProfile
     *
     * @param CarProfileInterface $carProfile
     * @return bool
     */
    public function delete(CarProfileInterface $carProfile): bool;

    /**
     * Delete CarProfile by Customer ID
     *
     * @param int $customerId
     * @return bool
     */
    public function deleteByCustomerId(int $customerId): bool;
}

