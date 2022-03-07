<?php

namespace Manugentoo\PartnerPortal\Controller\Adminhtml\Partners;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package Manugentoo\PartnerPortal\Controller\Adminhtml\Partners
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Edit extends \Magento\Framework\App\Action\Action
{

	/**
	 * @var PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * Index constructor.
	 * @param Context $context
	 * @param PageFactory $resultPageFactory
	 */
	public function __construct(Context $context, PageFactory $resultPageFactory)
	{
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 */
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Manugentoo_PartnerPortal::base');

		$id = $this->getRequest()->getParam('id');
		$title = $id ? __('Edit Partner') : __('Add Partner');
		$resultPage->getConfig()->getTitle()->prepend(($title));

		return $resultPage;
	}
}