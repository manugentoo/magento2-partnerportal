<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class OtpVerify
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpVerify extends Base
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Exception
	 */
	public function execute()
	{
		parent::execute();

		// Check if otp expired
		$partner = $this->getPartner();

		if($partner == false) {
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		if($this->accessTokenHelper->isAccessTokenExpired() == false){
			return $this->getResponse()->setRedirect(
				$this->getPartnerProductsUrl()
			);
		}

		if($this->helperOtp->isOtpExpired($partner)) {
			$this->messageManager->addErrorMessage(
				__(PartnersMessageInterface::ERROR_MESSAGE_OTP_SESSION_EXPIRED)
			);
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}
		return $this->_pageFactory->create();
	}
}