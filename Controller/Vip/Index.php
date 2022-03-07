<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

/**
 * Class Index
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Index extends Base
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Exception
	 */
	public function execute()
	{
		parent::execute();

		$partner = $this->getPartner();

		if($partner) {

			// force redirect to partners product page if token is found
			if ($this->accessTokenHelper->isAccessTokenExpired() == false) {
				return $this->getResponse()->setRedirect(
					$this->getPartnerProductsUrl()
				);
			}

			// if OTP has been requested redirect to OTP verification
			if ($this->helperOtp->isOtpExpired($partner) == false && $partner->getOtpCreatedAt()) {
				return $this->getResponse()->setRedirect(
					$redirectUrl = $this->getOtpVerifyUrl()
				);
			}
		}

		return $this->_pageFactory->create();
	}
}