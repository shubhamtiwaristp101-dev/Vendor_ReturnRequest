<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Controller\Adminhtml\ReturnRequest;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Vendor\ReturnRequest\Model\ResourceModel\ReturnRequest\CollectionFactory;

class MassStatus extends Action
{

    /**
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Update product(s) status action
     *
     * @return Redirect
     * @throws LocalizedException
     */
    public function execute()
    {
        $fbbCollection = $this->collectionFactory->create();
        $collection = $this->filter->getCollection($fbbCollection);
        $status = $this->getRequest()->getParam('status');

        if ($collection->getSize() <= 0) {
            $this->messageManager->addErrorMessage(__('Please select requests(s).'));
        } elseif ($status) {
            try {
                $collection->walk('setStatus', [$status]);
                $collection->save();
                $this->messageManager->addSuccessMessage(__("Status changed Successfully"));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('%1', $e->getMessage()));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Can\'t process approval/reject for some request.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $url = $this->_redirect->getRefererUrl();
        return $resultRedirect->setPath($url);
    }

}
