<?php

namespace Manugentoo\PartnerPortal\Helper;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Model\OtpMailer;
use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Otp
 * @package Manugentoo\PartnerPortal\Helper
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Otp
{
	/**
	 * @var int
	 */
	const OTP_LENGTH = 6;

	/**
	 * @var DateTime
	 */
	private $date;
	/**
	 * @var PartnersRepository
	 */
	private $partnersRepository;
	/**
	 * @var OtpMailer
	 */
	private $otpMailer;
	/**
	 * @var AccessToken
	 */
	private $accessTokenHelper;
	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @param DateTime $date
	 * @param PartnersRepository $partnersRepository
	 * @param OtpMailer $otpMailer
	 * @param AccessToken $accessTokenHelper
	 */
	public function __construct(
		DateTime $date,
		PartnersRepository $partnersRepository,
		OtpMailer $otpMailer,
		AccessToken $accessTokenHelper,
		Config $config
	) {
		$this->date = $date;
		$this->partnersRepository = $partnersRepository;
		$this->otpMailer = $otpMailer;
		$this->accessTokenHelper = $accessTokenHelper;
		$this->config = $config;
	}

	/**
	 * @return false|string
	 */
	public function generateOtpCode()
	{
		$str_result = '0123456789';
		return substr(str_shuffle($str_result), 0, self::OTP_LENGTH);
	}

	/**
	 * @param Partners $partner
	 * @return bool
	 * @throws \Exception
	 */
	public function isOtpExpired(Partners $partner)
	{
		$diffInMinutes = 0;

		if($this->accessTokenHelper->isAccessTokenExpired() == true) {

			if($partner->getOtpCode() == null &&  $partner->getOtpCreatedAt() == null) {
				return true;
			}

			if ($partner->getOtpCode() && $partner->getOtpCreatedAt()) {
				$otpCreatedAt = new \DateTime($partner->getOtpCreatedAt());
				$dateNow = new \DateTime($this->date->date('Y-m-d H:i:s'));
				$interval = $otpCreatedAt->diff($dateNow);
				$diffInMinutes = $interval->i;
			}

			// expire otp
			if($diffInMinutes > $this->config->getOtpMinutesValidity()) {
				$this->expireOtp($partner);
				return true;
			}
		}

		return false;
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function expireOtp(Partners $partner) {
		if($partner) {
			$partner->setAccessToken(null);
			$partner->setAccessTokenCreatedAt(null);
			$partner->setOtpCode(null);
			$partner->setOtpCreatedAt(null);
			$this->partnersRepository->save($partner);
		}
		return $this;
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @throws \Magento\Framework\Exception\MailException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function registerOtpCode(Partners $partner){

		$otpCode = $this->generateOtpCode();

		// Register OTP and created date
		$otpDateCreated = $this->date->date('Y-m-d H:i:s');
		$partner->setAccessToken(null);
		$partner->setAccessTokenCreatedAt(null);
		$partner->setOtpCode($otpCode);
		$partner->setOtpCreatedAt($otpDateCreated);
		$this->partnersRepository->save($partner);

		// send otp to customer
		$this->otpMailer->sendOtp($partner, $otpCode);

		return $this;
	}

}