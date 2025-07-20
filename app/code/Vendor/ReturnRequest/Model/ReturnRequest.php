<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model;

use Vendor\ReturnRequest\Api\Data\ReturnRequestInterface;

class ReturnRequest extends AbstractModel implements ReturnRequestInterface
{

    public const STATUS_NEW = 'new';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor_return_request';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest::class);
    }

    /**
     * Get return id
     * @return int
     */
    public function getReturnId()
    {
        return $this->getData(self::RETURN_ID);
    }

    /**
     * Get order id
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->getData(self::REASON);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Get date of request
     *
     * @return string
     */
    public function getDateOfRequest()
    {
        return $this->getData(self::DATE_OF_REQUEST);
    }

    /**
     * Get creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set return request
     *
     * @param $returnId
     * @return ReturnRequestInterface
     */
    public function setReturnId($returnId)
    {
        return $this->setData(self::RETURN_ID, $returnId);
    }

    /**
     * Set order id
     *
     * @param $orderId
     * @return ReturnRequestInterface
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Set customer id
     *
     * @param $customerId
     * @return ReturnRequestInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Set reason
     *
     * @param $reason
     * @return ReturnRequestInterface
     */
    public function setReason($reason)
    {
        return $this->setData(self::REASON, $reason);
    }

    /**
     * Set description
     *
     * @param $description
     * @return ReturnRequestInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Set image
     *
     * @param $image
     * @return ReturnRequestInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set status
     *
     * @param $status
     * @return ReturnRequestInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Set date of request
     *
     * @param $dateOfRequest
     * @return ReturnRequestInterface
     */
    public function setDateOfRequest($dateOfRequest)
    {
        return $this->setData(self::DATE_OF_REQUEST, $dateOfRequest);
    }

    /**
     * Set creation time
     *
     * @param $creationTime
     * @return ReturnRequestInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param $updateTime
     * @return ReturnRequestInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Prepare return request statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_NEW => __('New'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REJECTED => __('Rejected')
        ];
    }

    /**
     * Prepare return request reasons.
     *
     * @return array
     */
    public function getAvailableReasons()
    {
        return [
            ['value' => 'damaged', 'label' => __('Damaged Product')],
            ['value' => 'wrong_item', 'label' => __('Wrong Item Delivered')],
            ['value' => 'not_satisfied', 'label' => __('Not Satisfied')],
            ['value' => 'other', 'label' => __('Other')],
        ];
    }
}
