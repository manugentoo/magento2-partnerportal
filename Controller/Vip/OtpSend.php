<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class OtpSend
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpSend extends Base
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Exception
	 */
	public function execute()
	{

		$request = $this->getRequest();
		$indexUrl = $this->getIndexUrl();

		// make sure that this controller only accepts post request
		if (!$this->getRequest()->getPostValue()) {
			$this->getResponse()->setRedirect($indexUrl);
			return;
		}

		// load partner by email on post
		$partnerEmail = $request->getParam('email');
		$partner = $this->partnersRepository->loadPartnerByEmail($partnerEmail);

		if ($partner) {

			$this->accessTokenHelper->expireAccessToken();

			$this->registerPartner($partner);

			// Send OTP to partner Email
			if ($this->helperOtp->isOtpExpired($partner)) {
				// Register OTP and Send to Partners email
				$this->helperOtp->registerOtpCode($partner);

				//redirect to Verify OTP Form Page
				$redirectUrl = $this->getOtpVerifyUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			} else {
				$redirectUrl = $this->getOtpVerifyUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}
		} else {
			$this->messageManager->addErrorMessage(
				__(PartnersMessageInterface::EMAIL_TEXT_ERROR_EMAIL_NOT_MATCH)
			);
		}

		return $this->getResponse()->setRedirect($indexUrl);
	}
}