<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\DataObject;
use Vendor\ReturnRequest\Api\Data\ReturnRequestSearchResultsInterfaceFactory;
use Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest as ReturnRequestResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\ReturnRequest\Api\Data;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;
use Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest\CollectionFactory;

class ReturnRequestRepository implements ReturnRequestRepositoryInterface
{

    /**
     * @param ReturnRequestResource $resource
     * @param ReturnRequestFactory $returnRequestFactory
     * @param CollectionFactory $returnRequestCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ReturnRequestSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        protected ReturnRequestResource                      $resource,
        protected ReturnRequestFactory                       $returnRequestFactory,
        protected CollectionFactory                          $returnRequestCollectionFactory,
        protected CollectionProcessorInterface               $collectionProcessor,
        protected ReturnRequestSearchResultsInterfaceFactory $searchResultsFactory,
    ){}

    /**
     * Save return request data
     *
     * @param Data\ReturnRequestInterface $returnRequest
     * @return Data\ReturnRequestInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\ReturnRequestInterface $returnRequest)
    {

        try {
            $this->resource->save($returnRequest);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $returnRequest;
    }

    /**
     * Load return request by given id
     *
     * @param $returnId
     * @return Data\ReturnRequestInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($returnId)
    {
        $block = $this->returnRequestFactory->create();
        $this->resource->load($block, $returnId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The return request with the "%1" ID doesn\'t exist.', $returnId));
        }
        return $block;
    }

    /**
     * Load return request collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vendor\ReturnRequest\Api\Data\ReturnRequestSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest\Collection $collection */
        $collection = $this->returnRequestCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var Data\ReturnRequestSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete return request
     *
     * @param Data\ReturnRequestInterface $returnRequest
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\ReturnRequestInterface $returnRequest)
    {
        try {
            $this->resource->delete($returnRequest);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete return request by given id
     *
     * @param $returnId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($returnId)
    {
        return $this->delete($this->getById($returnId));
    }

    /**
     * Get return by order id
     *
     * @param $orderId
     * @return DataObject|null
     */
    public function getByOrderId($orderId)
    {
        $collection = $this->returnRequestCollectionFactory->create()
            ->addFieldToFilter('order_id', $orderId)
            ->setPageSize(1);

        return $collection->getFirstItem()->getId() ? $collection->getFirstItem() : null;
    }
}
