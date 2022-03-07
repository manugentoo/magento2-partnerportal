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
		$partnerUrl = $request->getParam('partner_url');
		$indexUrl = $this->getIndexUrl() . $partnerUrl;

		// make sure that this controller only accepts post request
		if (!$this->getRequest()->getPostValue()) {
			$this->getResponse()->setRedirect($indexUrl);
			return;
		}

		// load partner by email on post
		$partnerEmail = $request->getParam('email');
		$partner = $this->partnersRepository->loadPartnerByEmail($partnerEmail);

		if ($partner) {

			// force redirect to partners product page if token is found
			if($this->accessTokenHelper->isAccessTokenExpired() == false){
				$redirectUrl = $this->getPartnerProductsUrl(). $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			$this->renewPartnerRegistry($partner);

			// validate partner url if valid
			if ($partner->getUrl() != $request->getParam('partner_url')) {
				$this->messageManager->addErrorMessage(
					__(PartnersMessageInterface::EMAIL_TEXT_ERROR_EMAIL_NOT_MATCH)
				);
				$this->getResponse()->setRedirect($indexUrl);
				return;
			}

			// Send OTP to partner Email
			if ($this->helperOtp->isOtpExpired($partner)) {
				// Register OTP and Send to Partners email
				$this->helperOtp->registerOtpCode($partner);

				//redirect to Verify OTP Form Page
				// $this->messageManager->addNoticeMessage(__(PartnersMessageInterface::MESSAGE_EMAIL_TEXT_OTP_SENT));
				$redirectUrl = $this->getOtpVerifyUrl() . $partnerUrl;
				return $this->getResponse()->setRedirect($redirectUrl);
			} else {
				// $this->messageManager->addNoticeMessage(__(PartnersMessageInterface::MESSAGE_EMAIL_TEXT_OTP_SENT));
				$redirectUrl = $this->getOtpVerifyUrl() . $partnerUrl;
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