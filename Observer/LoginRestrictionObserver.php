<?php

namespace Test\LoginRestriction\Observer;

use \Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Test\LoginRestriction\Controller\Redirect;

class LoginRestrictionObserver implements ObserverInterface
{
    protected $redirectController;

    public function __construct(Redirect $redirectController)
    {
        $this->redirectController = $redirectController;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(Observer $observer)
    {
        /** @var $customerSession \Magento\Customer\Model\Session */
        $customerSession = $observer->getEvent()->getData('customer_session');

        // if user is logged in, every thing is fine
        if ($customerSession instanceof \Magento\Customer\Model\Session &&
            $customerSession->isLoggedIn()) {
            return;
        }

        // redirect
        $this->redirectController->execute();
    }
}
