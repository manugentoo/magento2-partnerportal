<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

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
	 * @param Context $context
	 * @param PageFactory $pageFactory
	 */
	public function __construct(
		Context $context,
		PageFactory $pageFactory
	) {
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		return $this->_pageFactory->create();
	}
}