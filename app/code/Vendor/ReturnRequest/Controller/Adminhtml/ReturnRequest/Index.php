<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Controller\Adminhtml\ReturnRequest;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Vendor_ReturnRequest::returnrequest';

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_ReturnRequest::returnrequest');
        $resultPage->addBreadcrumb(__('Return Requests'), __('Return Requests'));
        $resultPage->getConfig()->getTitle()->prepend(__('Return Requests'));

        return $resultPage;
    }
}
