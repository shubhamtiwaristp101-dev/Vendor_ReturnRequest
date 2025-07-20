<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;
use Vendor\ReturnRequest\Helper\Data;

class ReturnConfig implements ArgumentInterface
{

    /**
     * @param Data $data
     * @param ReturnRequestRepositoryInterface $returnRequestRepository
     */
    public function __construct(
        protected Data $data,
        protected ReturnRequestRepositoryInterface $returnRequestRepository
    ) {}

    /**
     * Is module enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->data->isModuleEnabled();
    }

    /**
     * Is return created
     *
     * @param $orderId
     * @return bool
     */
    public function isReturnCreated($orderId)
    {
        return (bool)$this->returnRequestRepository->getByOrderId($orderId);
    }

}
