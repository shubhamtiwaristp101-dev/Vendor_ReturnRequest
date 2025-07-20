<?php
declare(strict_types=1);

namespace Vendor\ReturnRequestGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;
use Vendor\ReturnRequest\Api\Data\ReturnRequestInterfaceFactory;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\Exception\LocalizedException;

class SubmitReturnRequest implements ResolverInterface
{
    /**
     * @param GetCustomer $getCustomer
     * @param ReturnRequestInterfaceFactory $requestFactory
     * @param ReturnRequestRepositoryInterface $returnRequestRepository
     */
    public function __construct(
        protected GetCustomer $getCustomer,
        protected ReturnRequestInterfaceFactory $requestFactory,
        protected ReturnRequestRepositoryInterface $returnRequestRepository
    ) {}

    /**
     * Resolve GraphQL mutation to create new return request for the logged-in customer
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        try {
            // Authenticated customer
            $customer = $this->getCustomer->execute($context);

            $input = $args['input'] ?? [];

            // Validate input
            if (empty($input['order_id']) || empty($input['reason'])) {
                throw new GraphQlInputException(__('Missing required fields.'));
            }

            // Create new return request
            $request = $this->requestFactory->create();
            $request->setOrderId((int)$input['order_id']);
            $request->setCustomerId((int)$customer->getId());
            $request->setReason($input['reason']);
            $request->setDescription($input['description'] ?? '');
            $request->setImage($input['image'] ?? '');
            $request->setStatus('new'); // default status

            $this->returnRequestRepository->save($request);

            return [
                'success' => true,
                'message' => __('Your return request has been submitted successfully.')
            ];

        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('An unexpected error occurred. Please try again.'));
        }
    }
}
