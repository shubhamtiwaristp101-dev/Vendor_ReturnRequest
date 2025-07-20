<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public const XML_PATH_MODULE_ENABLED     = 'return_request/general/enabled';
    public const XML_PATH_EMAIL_ENABLED      = 'return_request/email/enabled';
    public const XML_PATH_EMAIL_IDENTITY      = 'return_request/email/identity';
    public const XML_PATH_TEMPLATE_APPROVED  = 'return_request/email/template_approved';
    public const XML_PATH_TEMPLATE_REJECTED  = 'return_request/email/template_rejected';
    public const XML_PATH_TEMPLATE_COPY_TO  = 'return_request/email/copy_to';
    public const XML_PATH_TEMPLATE_COPY_METHOD  = 'return_request/email/copy_method';

    public const XML_PATH_ADMIN_EMAIL_ENABLED      = 'return_request/admin_email/enabled';
    public const XML_PATH_ADMIN_EMAIL_RECIPIENT     = 'return_request/admin_email/recipient';
    public const XML_PATH_ADMIN_EMAIL_IDENTITY      = 'return_request/admin_email/identity';
    public const XML_PATH_ADMIN_TEMPLATE_COPY_TO  = 'return_request/admin_email/copy_to';
    public const XML_PATH_ADMIN_TEMPLATE_COPY_METHOD  = 'return_request/admin_email/copy_method';
    public const XML_PATH_ADMIN_TEMPLATE_NEW       = 'return_request/admin_email/template_new';

    /**
     * Get config value
     *
     * @param $path
     * @param $storeId
     * @return string|bool|null
     */
    public function getConfigValue($path, $storeId = null): string|bool|null
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if return request module is enabled
     */
    public function isModuleEnabled($storeId = null): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

}
