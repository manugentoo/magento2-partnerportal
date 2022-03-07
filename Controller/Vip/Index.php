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

		if($partner === false) {
			$redirectUrl = $this->getNoRouteUrl();
			return $this->getResponse()->setRedirect($redirectUrl);
		}

		if($partner) {

			// force redirect to partners product page if token is found
			if ($this->accessTokenHelper->isAccessTokenExpired() == false) {
				$redirectUrl = $this->getPartnerProductsUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			// if OTP has been requested redirect to OTP verification
			if ($this->helperOtp->isOtpExpired($partner) == false && $partner->getOtpCreatedAt()) {
				$redirectUrl = $this->getOtpVerifyUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}
		}
		return $this->_pageFactory->create();
	}
}