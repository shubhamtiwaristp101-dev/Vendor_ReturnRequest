<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Api;

use Magento\Cms\Api\Data\BlockSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\ReturnRequest\Api\Data\ReturnRequestInterface;

interface ReturnRequestRepositoryInterface
{
    /**
     * Save return request.
     *
     * @param ReturnRequestInterface $returnRequest
     * @return ReturnRequestInterface
     * @throws LocalizedException
     */
    public function save(Data\ReturnRequestInterface $returnRequest);

    /**
     * Retrieve return request.
     *
     * @param string $returnId
     * @return ReturnRequestInterface
     * @throws LocalizedException
     */
    public function getById($returnId);

    /**
     * Retrieve return request matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BlockSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete return request.
     *
     * @param ReturnRequestInterface $returnRequest
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(Data\ReturnRequestInterface $returnRequest);

    /**
     * Delete return request by ID.
     *
     * @param string $returnId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($returnId);

    /**
     * Get Return Request by Order ID
     *
     * @param string $orderId
     * @return ReturnRequestInterface|null
     */
    public function getByOrderId($orderId);

}
