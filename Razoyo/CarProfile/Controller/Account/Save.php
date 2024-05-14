<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\Account;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Razoyo\CarProfile\Api\CarProfileRepositoryInterface;
use Razoyo\CarProfile\Api\CarApiServiceInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    /**
     * @param RedirectFactory $redirectFactory
     * @param CustomerSession $customerSession
     * @param CarApiServiceInterface $carApiService
     * @param CarProfileRepositoryInterface $carProfileRepository
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param ManagerInterface $messageManager
     * @param FormKeyValidator $formKeyValidator
     */
    public function __construct(
        private readonly RedirectFactory $redirectFactory,
        private readonly CustomerSession $customerSession,
        private readonly CarApiServiceInterface $carApiService,
        private readonly CarProfileRepositoryInterface $carProfileRepository,
        private readonly LoggerInterface $logger,
        private readonly RequestInterface $request,
        private readonly ManagerInterface $messageManager,
        private readonly FormKeyValidator $formKeyValidator
    ) {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirect = $this->redirectFactory->create();

        if (!$this->customerSession->isLoggedIn()) {
            return $redirect->setPath('customer/account/login');
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form key. Please refresh the page.'));
            return $redirect->setPath('carprofile/account/mycar');
        }

        $carId = $this->request->getParam('car_id');
        return $carId ? $this->handleCarUpdate($carId, $redirect) : $this->handleNoCarSelected($redirect);
    }

    /**
     * @param string $carId
     * @param Redirect $redirect
     * @return Redirect
     */
    private function handleCarUpdate(string $carId, Redirect $redirect): Redirect
    {
        $customerId = (int) $this->customerSession->getCustomerId();

        try {
            $carDetails = $this->carApiService->getCarDetails($carId);
            $this->updateCarProfile($customerId, $carDetails);
            $this->messageManager->addSuccessMessage(__('Car profile updated successfully.'));
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Invalid car selection. Please try again.'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Unable to save your car profile. Please try again later.'));
        }

        return $redirect->setPath('carprofile/account/mycar');
    }

    /**
     * @param int $customerId
     * @param $carDetails
     * @return void
     * @throws NoSuchEntityException
     */
    private function updateCarProfile(int $customerId, $carDetails): void
    {
        $carProfile = $this->carProfileRepository->getByCustomerId($customerId);
        if($carProfile) {
            $this->carProfileRepository->delete($carProfile);
        }
        $carDetails->setCustomerId($customerId);
        $this->carProfileRepository->save($carDetails);
    }

    /**
     * @param Redirect $redirect
     * @return Redirect
     */
    private function handleNoCarSelected(Redirect $redirect): Redirect
    {
        $this->messageManager->addErrorMessage(__('No car selected. Please select a car.'));
        return $redirect->setPath('carprofile/account/mycar');
    }
}
