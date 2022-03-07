<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class Products
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Products extends Base
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Exception
	 */
	public function execute()
	{
		parent::execute();
		$partner = $this->getPartner();

		if ($partner == null) {
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		// validate access token
		if ($this->accessTokenHelper->isAccessTokenExpired() == true) {
			if($this->helperOtp->isOtpExpired($partner) == true) {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::ERROR_MESSAGE_TOKEN_EXPIRED)
				);
			}
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		// validate access token
		if($this->helperOtp->isOtpExpired($partner) == true) {
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		return $this->_pageFactory->create();
	}
}