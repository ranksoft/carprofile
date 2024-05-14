<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model\ResourceModel\CarProfile;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Razoyo\CarProfile\Model\CarProfile as CarProfileModel;
use Razoyo\CarProfile\Model\ResourceModel\CarProfile as CarProfileResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(CarProfileModel::class, CarProfileResource::class);
    }
}
