<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Controller\Customer;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Phrase;
use Magento\Framework\UrlFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;
use Vendor\ReturnRequest\Model\ReturnRequest;
use Vendor\ReturnRequest\Model\ReturnRequestFactory;
use Vendor\ReturnRequest\Model\ReturnRequest\ImageUploader;
use Magento\Framework\Event\ManagerInterface;

class Save extends AbstractAccount implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param RedirectFactory $redirectFactory
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param ReturnRequestRepositoryInterface $returnRequestRepository
     * @param ReturnRequestFactory $returnRequestFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ManagerInterface $eventManager
     * @param UrlFactory $urlFactory
     */
    public function __construct(
        Context $context,
        protected ImageUploader $imageUploader,
        protected RedirectFactory $redirectFactory,
        protected UploaderFactory $uploaderFactory,
        protected Filesystem $filesystem,
        protected ReturnRequestRepositoryInterface $returnRequestRepository,
        protected ReturnRequestFactory $returnRequestFactory,
        protected DataPersistorInterface $dataPersistor,
        protected ManagerInterface $eventManager,
        protected UrlFactory $urlFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->redirectFactory->create();

        if (!$data || !isset($data['order_id'], $data['reason'], $data['description'])) {
            $this->messageManager->addErrorMessage(__('Missing required data.'));
            return $resultRedirect->setPath('*/*/form');
        }

        try {
            $imageName = null;
            if (isset($_FILES['image']['name']) && $_FILES['image']['name']) {
                // Save to tmp and move to permanent location
                $uploadResult = $this->imageUploader->saveFileToTmpDir('image');
                $imageName = $uploadResult['name'];
                $this->imageUploader->moveFileFromTmp($imageName);
            }

            // Validate order ID format
            if (!preg_match('/^\d+$/', $data['order_id'])) {
                throw new LocalizedException(__('Invalid Order ID.'));
            }

            // Save the return request via Repository
            $returnRequest = $this->returnRequestFactory->create();
            $returnRequest->setOrderId($data['order_id']);
            $returnRequest->setCustomerId($data['customer_id']);
            $returnRequest->setReason($data['reason']);
            $returnRequest->setDescription($data['description']);
            $returnRequest->setImage($imageName);
            $returnRequest->setStatus(ReturnRequest::STATUS_NEW);
            $returnRequest->setCreatedAt(date('Y-m-d H:i:s'));

            $this->returnRequestRepository->save($returnRequest);

            $this->eventManager->dispatch(
                'vendor_returnrequest_after_save',
                [
                    'return_request' => $returnRequest,
                    'request_data' => $data
                ]
            );

            $this->messageManager->addSuccessMessage(__('Return request submitted successfully.'));
            return $resultRedirect->setPath('returnrequest/customer/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while submitting your request.'));
        }

        $this->dataPersistor->set('return_request_form', $data);
        return $resultRedirect->setPath('*/*/form', ['order_id' => $data['order_id']]);
    }

    /**
     * Create Csrf validation exception
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $url = $this->urlFactory->create()->getUrl('*/*/create', ['_secure' => true]);
        $resultRedirect->setUrl($this->_redirect->error($url));

        return new InvalidRequestException(
            $resultRedirect,
            [new Phrase('Invalid Form Key. Please refresh the page.')]
        );
    }

    /**
     * Validate Csrf
     *
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }
}
