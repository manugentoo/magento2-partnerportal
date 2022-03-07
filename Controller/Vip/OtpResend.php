<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class OtpResend
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpResend extends Base
{

	/**
	 * Maximum allowed attempt
	 * @var int
	 */
	const OTP_MAX_ATTEMPT = 5;
	/**
	 * Maximum waiting time for the next allowed attempt
	 * @var string
	 */
	const OTP_MAX_MIN_ATTEMPT_PAUSE = 5;

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @throws \Magento\Framework\Exception\MailException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function execute()
	{
		parent::execute();
		$partner = $this->getPartner();

		if($partner === false) {
			$redirectUrl = $this->getNoRouteUrl();
			return $this->getResponse()->setRedirect($redirectUrl);
		}


		if ($partner) {

			// force redirect to partners product page if token is found
			if ($this->accessTokenHelper->isAccessTokenExpired() == false) {
				$redirectUrl = $this->getPartnerProductsUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

			$partnerSession = $this->partnerSession;
			$otpAttempts = $partnerSession->getOtpAttempts();
			$now = $this->date->date('Y-m-d H:i:s');

			// prevent excessive otp request reset attempt
			if($otpAttempts) {
				$otpRequestCreatedAt = new \DateTime($otpAttempts['created_at']);
				$dateNow = new \DateTime($now);
				$interval = $otpRequestCreatedAt->diff($dateNow);
				$diffInMinutes = $interval->i;

				if($diffInMinutes >= self::OTP_MAX_MIN_ATTEMPT_PAUSE) {
					$partnerSession->setOtpAttempts(null);
				}

				if(is_array($otpAttempts) && $otpAttempts['attempts'] >= self::OTP_MAX_ATTEMPT) {
					$this->messageManager->addErrorMessage(__(PartnersMessageInterface::ERROR_MESSAGE_OTP_RESEND, self::OTP_MAX_MIN_ATTEMPT_PAUSE));
					$redirectUrl = $this->getOtpVerifyUrl() . $partner->getUrl();
					return $this->getResponse()->setRedirect($redirectUrl);
				}
			}

			// reset otp code
			$this->helperOtp->registerOtpCode($partner);

			// register otp attempts
			if($otpAttempts == null) {
				$otpAttemptsData = [
					'created_at' => $now,
					'attempts' => 1,
				];
				$partnerSession->setOtpAttempts($otpAttemptsData);
			} else {
				$otpAttempts['attempts'] = $otpAttempts['attempts'] + 1 ;
				$partnerSession->setOtpAttempts($otpAttempts);
			}

			$this->messageManager->addSuccessMessage(__(PartnersMessageInterface::MESSAGE_OTP_RESEND));
			$redirectUrl = $this->getOtpVerifyUrl() . $partner->getUrl();
			return $this->getResponse()->setRedirect($redirectUrl);
		}

		return $this->getResponse()->setRedirect($this->getIndexUrl());
	}
}