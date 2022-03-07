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

		if($partner === false) {
			$redirectUrl = $this->getNoRouteUrl();
			return $this->getResponse()->setRedirect($redirectUrl);
		}


		if($partner) {

			if($this->accessTokenHelper->isAccessTokenExpired() == false){
				$redirectUrl = $this->getPartnerProductsUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			if($this->helperOtp->isOtpExpired($partner)) {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::ERROR_MESSAGE_OTP_SESSION_EXPIRED)
				);
				$redirectUrl = $this->getIndexUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}
		}
		return $this->_pageFactory->create();
	}
}