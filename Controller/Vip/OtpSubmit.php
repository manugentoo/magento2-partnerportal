<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class OtpSubmit
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpSubmit extends Base
{

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function execute()
	{
		$request = $this->getRequest();
		$otpCode = $request->getParam('otp_code');

		// make sure that this controller only accepts post request
		if (!$this->getRequest()->getPostValue()) {
			$this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
			return;
		}

		// load partner by email on post
		$partner = $this->getPartner();

		if ($partner == false) {
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		// force redirect to partners product page if token is found
		if ($this->accessTokenHelper->isAccessTokenExpired() == false) {
			return $this->getResponse()->setRedirect(
				$this->getPartnerProductsUrl()
			);
		}

		// check OTP Expiration
		if ($this->helperOtp->isOtpExpired($partner)) {
			$this->messageManager->addErrorMessage(
				__(PartnersMessageInterface::ERROR_MESSAGE_OTP_SESSION_EXPIRED)
			);
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}

		// create partner session token and expire in 30min
		if ($partner->getOtpCode() == $otpCode) {

			$this->accessTokenHelper->registerAccessToken($partner);

			return $this->getResponse()->setRedirect(
				$this->getPartnerProductsUrl()
			);

		} else {
			$this->messageManager->addErrorMessage(
				__(PartnersMessageInterface::ERROR_MESSAGE_INVALID_OTP)
			);
			return $this->getResponse()->setRedirect(
				$this->getOtpVerifyUrl()
			);
		}
		dd(__FILE__ . __LINE__);
		return $this->_pageFactory->create();
	}
}