<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{

    /**
     * Constructor
     *
     * @param \Vendor\ReturnRequest\Model\ReturnRequest $returnRequest
     */
    public function __construct(protected \Vendor\ReturnRequest\Model\ReturnRequest $returnRequest)
    {
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->returnRequest->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

}
