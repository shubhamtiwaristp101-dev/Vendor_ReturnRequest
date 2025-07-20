<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Block\Customer;

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Framework\View\Element\Html\Link\Current;

class ReturnsLink extends Current implements SortLinkInterface
{

    /**
     * Render block HTML
     *
     * @inheritdoc
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
