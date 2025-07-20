<?php
declare(strict_types=1);

namespace Vendor\ReturnRequestGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;

class CustomerReturnRequests implements ResolverInterface
{

    /**
     * @param ReturnRequestRepositoryInterface $returnRequestRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GetCustomer $getCustomer
     */
    public function __construct(
        protected ReturnRequestRepositoryInterface $returnRequestRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected GetCustomer $getCustomer
    ) {}

    /**
     * Resolve GraphQL query to get return requests for the logged-in customer
     *
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     * @throws GraphQlAuthenticationException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        try {
            // Get current customer from GraphQL context
            $customer = $this->getCustomer->execute($context);
            $customerId = (int) $customer->getId();

            if (!$customerId) {
                throw new GraphQlNoSuchEntityException(__('Customer not found.'));
            }

            // Apply filter by customer_id
            $this->searchCriteriaBuilder->addFilter('customer_id', $customerId, 'eq');
            $searchCriteria = $this->searchCriteriaBuilder->create();

            // Get return requests using repository
            $result = $this->returnRequestRepository->getList($searchCriteria);

            $items = [];

            foreach ($result->getItems() as $request) {
                $items[] = [
                    'return_id' => (int) $request->getReturnId(),
                    'order_id' => (int) $request->getOrderId(),
                    'reason' => $request->getReason(),
                    'description' => $request->getDescription(),
                    'status' => $request->getStatus(),
                    'created_at' => $request->getCreationTime(),
                ];
            }

            return [
                'items' => $items,
                'total_count' => $result->getTotalCount()
            ];

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('An unexpected error occurred. Please try again later.'));
        }
    }
}
