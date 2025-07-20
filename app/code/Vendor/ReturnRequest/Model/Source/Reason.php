<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Vendor\ReturnRequest\Model\ReturnRequest;

class Reason implements OptionSourceInterface
{
    /**
     * Constructor
     *
     * @param ReturnRequest $returnRequest
     */
    public function __construct(protected ReturnRequest $returnRequest)
    {}

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->returnRequest->getAvailableReasons();
    }
}
