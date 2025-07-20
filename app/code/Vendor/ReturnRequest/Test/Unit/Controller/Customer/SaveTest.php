<?php
declare(strict_types=1);

namespace Vendor\ReturnRequest\Test\Unit\Controller\Customer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Vendor\ReturnRequest\Controller\Customer\Save;
use Vendor\ReturnRequest\Model\ReturnRequestFactory;
use Vendor\ReturnRequest\Model\ReturnRequest;
use Vendor\ReturnRequest\Model\ReturnRequest\ImageUploader;
use Vendor\ReturnRequest\Api\ReturnRequestRepositoryInterface;

class SaveTest extends TestCase
{
    /**
     * @var (Context&MockObject)|MockObject
     */
    private $contextMock;
    /**
     * @var Http|(Http&object&MockObject)|(Http&MockObject)|(object&MockObject)|MockObject
     */
    private $requestMock;
    /**
     * @var RedirectFactory|(RedirectFactory&object&MockObject)|(RedirectFactory&MockObject)|(object&MockObject)|MockObject
     */
    private $redirectFactoryMock;
    /**
     * @var Redirect|(Redirect&object&MockObject)|(Redirect&MockObject)|(object&MockObject)|MockObject
     */
    private $redirectMock;
    /**
     * @var ManagerInterface|(ManagerInterface&object&MockObject)|(ManagerInterface&MockObject)|(object&MockObject)|MockObject
     */
    private $messageManagerMock;
    /**
     * @var DataPersistorInterface|(DataPersistorInterface&object&MockObject)|(DataPersistorInterface&MockObject)|(object&MockObject)|MockObject
     */
    private $dataPersistorMock;
    /**
     * @var EventManager|(EventManager&object&MockObject)|(EventManager&MockObject)|(object&MockObject)|MockObject
     */
    private $eventManagerMock;
    /**
     * @var (object&MockObject)|MockObject|ImageUploader|(ImageUploader&object&MockObject)|(ImageUploader&MockObject)
     */
    private $imageUploaderMock;
    /**
     * @var (object&MockObject)|MockObject|ReturnRequestFactory|(ReturnRequestFactory&object&MockObject)|(ReturnRequestFactory&MockObject)
     */
    private $returnRequestFactoryMock;
    /**
     * @var MockObject|(ReturnRequest&MockObject)
     */
    private $returnRequestMock;
    /**
     * @var (object&MockObject)|MockObject|ReturnRequestRepositoryInterface|(ReturnRequestRepositoryInterface&object&MockObject)|(ReturnRequestRepositoryInterface&MockObject)
     */
    private $returnRequestRepositoryMock;

    /**
     * @var Save
     */
    private Save $controller;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Http::class);
        $this->redirectMock = $this->createMock(Redirect::class);
        $this->redirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->dataPersistorMock = $this->createMock(DataPersistorInterface::class);
        $this->eventManagerMock = $this->createMock(EventManager::class);
        $this->imageUploaderMock = $this->createMock(ImageUploader::class);
        $this->returnRequestFactoryMock = $this->createMock(ReturnRequestFactory::class);
        $this->returnRequestMock = $this->getMockBuilder(ReturnRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setOrderId', 'setReason', 'setDescription', 'setImage', 'setStatus'])
            ->getMock();
        $this->returnRequestRepositoryMock = $this->createMock(ReturnRequestRepositoryInterface::class);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequest', 'getMessageManager'])
            ->getMock();

        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->redirectFactoryMock->method('create')->willReturn($this->redirectMock);

        $this->controller = new Save(
            $this->contextMock,
            $this->imageUploaderMock,
            $this->redirectFactoryMock,
            $this->createMock(\Magento\MediaStorage\Model\File\UploaderFactory::class),
            $this->createMock(\Magento\Framework\Filesystem::class),
            $this->returnRequestRepositoryMock,
            $this->returnRequestFactoryMock,
            $this->dataPersistorMock,
            $this->eventManagerMock
        );
    }

    /**
     * @return void
     */
    public function testExecuteWithValidData()
    {
        $postData = [
            'order_id' => '100000123',
            'reason' => 'damaged',
            'description' => 'Product damaged',
        ];

        $this->requestMock->method('getPostValue')->willReturn($postData);

        $this->returnRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->returnRequestMock);

        $this->returnRequestMock->expects($this->once())->method('setOrderId')->with('100000123')->willReturnSelf();
        $this->returnRequestMock->expects($this->once())->method('setReason')->with('damaged')->willReturnSelf();
        $this->returnRequestMock->expects($this->once())->method('setDescription')->with('Product damaged')->willReturnSelf();
        $this->returnRequestMock->expects($this->once())->method('setImage')->with(null)->willReturnSelf();
        $this->returnRequestMock->expects($this->once())->method('setStatus')->with(ReturnRequest::STATUS_NEW)->willReturnSelf();

        $this->returnRequestRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->returnRequestMock);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->with(
                'vendor_returnrequest_after_save',
                $this->arrayHasKey('return_request')
            );

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('Return request submitted successfully.'));

        $this->redirectMock->expects($this->once())
            ->method('setPath')
            ->with('returnrequest/customer/index');

        $this->controller->execute();
    }

    /**
     * @return void
     */
    public function testExecuteWithMissingData()
    {
        $this->requestMock->method('getPostValue')->willReturn([]);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Missing required data.'));

        $this->redirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/form');

        $this->controller->execute();
    }

    /**
     * @return void
     */
    public function testExecuteWithRepositoryException()
    {
        $postData = [
            'order_id' => '100000123',
            'reason' => 'wrong_item',
            'description' => 'Wrong item',
        ];

        $this->requestMock->method('getPostValue')->willReturn($postData);

        $this->returnRequestFactoryMock->method('create')->willReturn($this->returnRequestMock);
        $this->returnRequestMock->method('setOrderId')->willReturnSelf();
        $this->returnRequestMock->method('setReason')->willReturnSelf();
        $this->returnRequestMock->method('setDescription')->willReturnSelf();
        $this->returnRequestMock->method('setImage')->willReturnSelf();
        $this->returnRequestMock->method('setStatus')->willReturnSelf();

        $this->returnRequestRepositoryMock->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Something failed'));

        $this->messageManagerMock->expects($this->once())
            ->method('addExceptionMessage');

        $this->redirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/form', ['order_id' => '100000123']);

        $this->controller->execute();
    }
}
