<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Manugentoo\PartnerPortal\Helper\AccessToken as AccessTokenHelper;

/**
 * Class NoRoute
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class NoRoute extends Action
{

	/**
	 * @var PageFactory
	 */
	protected $_pageFactory;
	/**
	 * @var UrlInterface
	 */
	protected $url;
	/**
	 * @var AccessTokenHelper
	 */
	protected $accessTokenHelper;

	/**
	 * @param Context $context
	 * @param PageFactory $pageFactory
	 * @param UrlInterface $url
	 * @param AccessTokenHelper $accessTokenHelper
	 */
	public function __construct(
		Context $context,
		PageFactory $pageFactory,
		UrlInterface $url,
		AccessTokenHelper $accessTokenHelper
	) {
		$this->_pageFactory = $pageFactory;
		$this->url = $url;
		$this->accessTokenHelper = $accessTokenHelper;
		return parent::__construct($context);
	}

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		if($this->accessTokenHelper->isAccessTokenExpired() == false){
			return $this->getResponse()->setRedirect(
				$this->url->getUrl('*/*/products')
			);
		}

		return $this->_pageFactory->create();
	}
}