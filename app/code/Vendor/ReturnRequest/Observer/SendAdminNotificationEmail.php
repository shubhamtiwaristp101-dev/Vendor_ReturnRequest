<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\MailException;
use Vendor\ReturnRequest\Helper\Data;

class SendAdminNotificationEmail implements ObserverInterface
{
    /**
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     * @param Data $data
     */
    public function __construct(
        protected TransportBuilder $transportBuilder,
        protected StoreManagerInterface $storeManager,
        protected ScopeConfigInterface $scopeConfig,
        protected LoggerInterface $logger,
        protected Data $data
    ) {}

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $returnRequest = $observer->getData('return_request');

        try {
            $adminEmail = $this->data->getConfigValue(Data::XML_PATH_ADMIN_EMAIL_RECIPIENT);
            $fromEmail = $this->data->getConfigValue(Data::XML_PATH_ADMIN_EMAIL_IDENTITY);
            $copyTo = $this->data->getConfigValue(Data::XML_PATH_ADMIN_TEMPLATE_COPY_TO);
            $copyMethod = $this->data->getConfigValue(Data::XML_PATH_ADMIN_TEMPLATE_COPY_METHOD);

            $imageUrl = '';
            if ($returnRequest->getImage()) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = $mediaUrl . 'returnrequest/returns/' . ltrim($returnRequest->getImage(), '/');
            }

            $this->transportBuilder
                ->setTemplateIdentifier('return_request_admin_email_template_new')
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ])
                ->setTemplateVars([
                    'order_id' => $returnRequest->getOrderId(),
                    'reason' => $returnRequest->getReason(),
                    'description' => $returnRequest->getDescription(),
                    'image_url' => $imageUrl
                ])
                ->setFromByScope($fromEmail);

            $emails = explode(',', $copyTo);
            $emails[] = $adminEmail;
            $emails = array_filter($emails);
            if ($copyMethod == 'bcc') {
                foreach ($emails as $email) {
                    $this->transportBuilder->addBcc($email);
                }
                $this->transportBuilder->addTo($adminEmail);
                $transport = $this->transportBuilder->getTransport();
                $transport->sendMessage();
            } else {
                foreach ($emails as $email) {
                    $this->transportBuilder->addTo($email);
                    $transport = $this->transportBuilder->getTransport();
                    $transport->sendMessage();
                }
            }
        } catch (MailException|\Exception $e) {
            $this->logger->error(__('Unable to send return request email to admin: ') . $e->getMessage());
        }
    }
}
