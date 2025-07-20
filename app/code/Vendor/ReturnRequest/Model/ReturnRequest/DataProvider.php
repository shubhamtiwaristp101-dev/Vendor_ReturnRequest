<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model\ReturnRequest;

use Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;
use Magento\Customer\Model\Session as CustomerSession;

class DataProvider extends SearchResult
{
    /**
     * @param CustomerSession $customerSession
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     * @param string $identifierName
     * @throws LocalizedException
     */
    public function __construct(
        protected CustomerSession $customerSession,
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
                      $mainTable = 'vendor_return_request',
                      $resourceModel = ReturnRequest::class,
                      $identifierName = 'return_id'
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel,
            $identifierName
        );
    }

    /**
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            [
                'customer_entity' => $this->getTable('customer_entity'),
            ],
            "main_table.customer_id = customer_entity.entity_id",
            [
                'customer_email' => 'customer_entity.email'
            ]
        );

        $this->addFilterToMap(
            'customer_email',
            'customer_entity.email'
        );

        // Apply filter for current logged-in customer
        if ($this->customerSession->isLoggedIn()) {
            $customerId = (int)$this->customerSession->getCustomerId();
            $this->getSelect()->where('main_table.customer_id = ?', $customerId);
        }

        return $this;
    }
}
