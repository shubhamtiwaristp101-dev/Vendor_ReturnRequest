<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Api\Data;
interface ReturnRequestInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const RETURN_ID       = 'return_id';
    public const ORDER_ID        = 'order_id';
    public const CUSTOMER_ID     = 'customer_id';
    public const REASON          = 'reason';
    public const DESCRIPTION     = 'description';
    public const IMAGE           = 'image';
    public const STATUS          = 'status';
    public const DATE_OF_REQUEST = 'date_of_request';
    public const CREATION_TIME   = 'creation_time';
    public const UPDATE_TIME     = 'update_time';
    /**#@-*/

    /**
     * Get Return ID
     *
     * @return int|null
     */
    public function getReturnId();

    /**
     * Get Order ID
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Get Reason
     *
     * @return string|null
     */
    public function getReason();

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get Image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Get Return ID
     *
     * @return string|null
     */
    public function getDateOfRequest();

    /**
     * Get Return ID
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get Update Time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Set Return ID
     *
     * @param int $returnId
     * @return ReturnRequestInterface
     */
    public function setReturnId($returnId);

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return ReturnRequestInterface
     */
    public function setOrderId($orderId);

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return ReturnRequestInterface
     */
    public function setCustomerId($customerId);

    /**
     * Set Reason
     *
     * @param int $reason
     * @return ReturnRequestInterface
     */
    public function setReason($reason);

    /**
     * Set Description
     *
     * @param string $description
     * @return ReturnRequestInterface
     */
    public function setDescription($description);

    /**
     * Set image
     *
     * @param string $image
     * @return ReturnRequestInterface
     */
    public function setImage($image);

    /**
     * Set status
     *
     * @param int $status
     * @return ReturnRequestInterface
     */
    public function setStatus($status);

    /**
     * Set date of request
     *
     * @param string $dateOfRequest
     * @return ReturnRequestInterface
     */
    public function setDateOfRequest($dateOfRequest);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return ReturnRequestInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return ReturnRequestInterface
     */
    public function setUpdateTime($updateTime);

}
