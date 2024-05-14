<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Razoyo\CarProfile\Api\CarProfileRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CarProfileInterface;
use Razoyo\CarProfile\Model\ResourceModel\CarProfile as CarProfileResource;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class CarProfileRepository implements CarProfileRepositoryInterface
{
    /**
     * @param CarProfileFactory $carProfileFactory
     * @param CarProfileResource $carProfileResource
     */
    public function __construct(
        private readonly CarProfileFactory  $carProfileFactory,
        private readonly  CarProfileResource $carProfileResource
    )
    {
    }

    /**
     * @inheritDoc
     * @throws CouldNotSaveException
     */
    public function save(CarProfileInterface $carProfile): CarProfileInterface
    {
        try {
            $this->carProfileResource->save($carProfile);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $carProfile;
    }

    /**
     * @inheritDoc
     */
    public function getByCustomerId(int $customerId): ?CarProfileInterface
    {
        $carProfile = $this->carProfileFactory->create();
        $this->carProfileResource->load($carProfile, $customerId, 'customer_id');

        if (!$carProfile->getId()) {
            return null;
        }

        return $carProfile;
    }

    /**
     * @inheritDoc
     * @throws CouldNotDeleteException
     */
    public function delete(CarProfileInterface $carProfile): bool
    {
        try {
            $this->carProfileResource->delete($carProfile);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteByCustomerId(int $customerId): bool
    {
        $carProfile = $this->getByCustomerId($customerId);
        return $this->delete($carProfile);
    }
}
