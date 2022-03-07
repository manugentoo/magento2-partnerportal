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
		$partnerEmail = $request->getParam('partner_email');
		$partnerUrl = $request->getParam('partner_url');
		$otpCode = $request->getParam('otp_code');
		$indexUrl = $this->url->getUrl('*/*/index/') . $partnerUrl;

		// make sure that this controller only accepts post request
		if (!$this->getRequest()->getPostValue()) {
			$this->getResponse()->setRedirect($indexUrl);
			return;
		}

		// load partner by email on post
		$partner = $this->partnersRepository->loadPartnerByEmail($partnerEmail);

		if ($partner) {

			// force redirect to partners product page if token is found
			if ($this->accessTokenHelper->isAccessTokenExpired() == false) {
				$redirectUrl = $this->getPartnerProductsUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			// validate partner url if valid
			if ($partner->getUrl() != $request->getParam('partner_url')) {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::ERROR_MESSAGE_EMAIL_DONT_MATCH)
				);
				$this->getResponse()->setRedirect($indexUrl);
				return;
			}

			// check OTP Expiration
			if ($this->helperOtp->isOtpExpired($partner)) {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::ERROR_MESSAGE_OTP_SESSION_EXPIRED)
				);
				$indexUrl = $this->getIndexUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($indexUrl);
			}

			// create partner session token and expire in 30min
			if ($partner->getOtpCode() == $otpCode) {
				$this->accessTokenHelper->registerAccessToken($partner);
				$redirectUrl = $this->getIndexUrl() . $partnerUrl;
				return $this->getResponse()->setRedirect($redirectUrl);
			} else {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::ERROR_MESSAGE_INVALID_OTP)
				);
				$redirectUrl = $this->getOtpVerifyUrl() . $partnerUrl;
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			$this->renewPartnerRegistry($partner);
		}

		return $this->_pageFactory->create();
	}
}