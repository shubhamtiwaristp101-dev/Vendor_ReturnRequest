<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template\Context;
use Vendor\ReturnRequest\Model\ReturnRequest;
use Magento\Customer\Model\Session as CustomerSession;

class Form extends Template
{

    /**
     * @param Context $context
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     * @param ReturnRequest $returnRequest
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        protected UrlInterface $urlBuilder,
        protected RequestInterface $request,
        protected ReturnRequest $returnRequest,
        protected CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Form Action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->urlBuilder->getUrl('returnrequest/customer/save');
    }

    /**
     * Get Order Increment ID
     * @return string|null
     */
    public function getOrderIncrementId(): ?string
    {
        return $this->request->getParam('order_id');
    }

    /**
     * Get reason options
     *
     * @return array
     */
    public function getReasonOptions()
    {
        return $this->returnRequest->getAvailableReasons();
    }

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return (int)$this->customerSession->getCustomerId();
    }

}
