<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;

class MyCar implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Razoyo_CarProfile::view_car';

    /**
     * @param RedirectFactory $redirectFactory
     * @param PageFactory $pageFactory
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private readonly RedirectFactory $redirectFactory,
        private readonly PageFactory $pageFactory,
        private readonly CustomerSession $customerSession
    ) {
    }

    /**
     * Executes the request to show the 'My Car' page or redirect if not logged in.
     *
     * @return Redirect|Page
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->getLoginRedirect();
        }

        return $this->getMyCarPage();
    }

    /**
     * Redirects to the login page.
     *
     * @return Redirect
     */
    private function getLoginRedirect(): Redirect
    {
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath('customer/account/login');
    }

    /**
     * Creates and returns the 'My Car' page.
     *
     * @return Page
     */
    private function getMyCarPage(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Car'));
        return $resultPage;
    }
}
