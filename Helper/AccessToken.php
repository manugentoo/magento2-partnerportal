<?php

namespace Manugentoo\PartnerPortal\Helper;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Manugentoo\PartnerPortal\Model\Session as PartnerSesssion;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class AccessToken
 * @package Manugentoo\PartnerPortal\Helper
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class AccessToken
{
	/**
	 * @var int
	 */
	const ACCESS_TOKEN_LENGTH = 12;

	/**
	 * @var DateTime
	 */
	private $date;
	/**
	 * @var PartnersRepository
	 */
	private $partnersRepository;
	/**
	 * @var Config
	 */
	private $config;
	/**
	 * @var PartnerSesssion
	 */
	protected $partnerSession;

	/**
	 * @param DateTime $date
	 * @param PartnersRepository $partnersRepository
	 * @param Config $config
	 * @param PartnerSesssion $partnerSession
	 */
	public function __construct(
		DateTime $date,
		PartnersRepository $partnersRepository,
		Config $config,
		PartnerSesssion $partnerSession
	) {
		$this->date = $date;
		$this->partnersRepository = $partnersRepository;
		$this->config = $config;
		$this->partnerSession = $partnerSession;
	}

	/**
	 * @return false|string
	 */
	public function generateAccessToken()
	{
		$str_result = 'abcdefghijklmnopqrstuyvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr(str_shuffle($str_result), 0, self::ACCESS_TOKEN_LENGTH);
	}

	/**
	 * @return Partners
	 */
	public function getPartner() {
		return $this->partnerSession->getPartner();
	}

	/**
	 * @return bool
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function isAccessTokenExpired()
	{
		$partner = $this->getPartner();
		$diffInMinutes = 0;

		if($partner) {
			if($partner->getAccessToken() == null &&  $partner->getAccessTokenCreatedAt() == null) {
				return true;
			}
			if ($partner->getAccessToken() && $partner->getAccessTokenCreatedAt()) {
				$otpCreatedAt = new \DateTime($partner->getAccessTokenCreatedAt());
				$dateNow = new \DateTime($this->date->date('Y-m-d H:i:s'));
				$interval = $otpCreatedAt->diff($dateNow);
				$diffInMinutes = $interval->i;
			}

		}

		// expire access token
		if($diffInMinutes > $this->config->getAccessTokenMinutesValidity()) {
			$this->expireAccessToken();
			return true;
		}
		return false;
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function expireAccessToken() {

		$partner = $this->getPartner();

		if($partner) {
			$partner->setAccessToken(null);
			$partner->setAccessTokenCreatedAt(null);
			$partner->setOtpCode(null);
			$partner->setOtpCreatedAt(null);
			$this->partnersRepository->save($partner);
			$this->partnerSession->unsPartner();
			$this->partnerSession->unsPartnerProducts();
		}
		return $this;
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function registerAccessToken(Partners $partner) {
		$accessToken = $this->generateAccessToken();
		$otpDateCreated = $this->date->date('Y-m-d H:i:s');
		$partner->setAccessToken($accessToken);
		$partner->setAccessTokenCreatedAt($otpDateCreated);
		$partner->setOtpCode(null);
		$partner->setOtpCreatedAt(null);
		$this->partnersRepository->save($partner);
		return $this;
	}
}