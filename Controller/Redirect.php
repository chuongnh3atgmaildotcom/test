<?php

namespace Test\LoginRestriction\Controller;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Session\SessionManager;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\Response\Http as ResponseHttp;

class Redirect extends Action
{
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var ResponseHttp
     */
    protected $response;

    public function __construct(
        Context $context,
        SessionManager $session,
        ResponseHttp $response
    ) {
        $this->session = $session;
        $this->response = $response;
        parent::__construct($context);
    }

    /**
     * Manages redirect
     */
    public function execute()
    {
        $url = $this->_url->getCurrentUrl();
        $path = \parse_url($url, PHP_URL_PATH);
        $targetUrl = 'customer/account/login';
        if (strpos($path, $targetUrl) || strpos($path, 'customer/account/create')) {
            return;
        }
        $this->session->setAfterLoginReferer($path);
        if (!strpos($path, 'customer/account/logout')) {
            $this->messageManager->addWarning(__('Restricted for logged in customer only. Please log in.'));
        }

        $this->response->setNoCacheHeaders();
        $this->response->setRedirect($this->getRedirectUrl($targetUrl));
        $this->response->sendResponse();
    }

    /**
     * @param string $targetUrl
     * @return string
     */
    protected function getRedirectUrl($targetUrl)
    {
        return \sprintf(
            '%s%s',
            $this->_url->getBaseUrl(),
            $targetUrl
        );
    }
}
