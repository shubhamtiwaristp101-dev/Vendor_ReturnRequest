<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest;

use Vendor\ReturnRequest\Api\Data\ReturnRequestSearchResultsInterface;
use Magento\Sales\Model\ResourceModel\Collection\AbstractCollection;

/**
 * ReturnRequest collection
 */
class Collection extends AbstractCollection implements ReturnRequestSearchResultsInterface
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Vendor\ReturnRequest\Model\ReturnRequest::class,
            \Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest::class
        );
    }
}
