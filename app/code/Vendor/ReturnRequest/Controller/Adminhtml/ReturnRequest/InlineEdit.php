<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Controller\Adminhtml\ReturnRequest;

use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Vendor_ReturnRequest::returnrequest';

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ReturnRequestRepositoryInterface $returnRequestRepository
     */
    public function __construct(
        Context $context,
        protected JsonFactory $jsonFactory,
        protected ReturnRequestRepositoryInterface $returnRequestRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Inline edit template
     *
     * @return Json
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $manufacturerId) {
                    try {
                        $manufacturer = $this->returnRequestRepository->getById($manufacturerId);
                        $manufacturer->setData(array_merge($manufacturer->getData(), $postItems[$manufacturerId]));
                        $this->returnRequestRepository->save($manufacturer);
                    } catch (\Exception $exception) {
                        $messages[] = __($exception->getMessage());
                        $error = true;
                    }
                }
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
