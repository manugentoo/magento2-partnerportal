<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Partners
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Partners extends AbstractModel implements PartnersInterface, IdentityInterface
{
	/**
	 * @var string
	 */
	const CACHE_TAG = 'partners';

	/**
	 * @var string
	 */
	protected $_cacheTag = self::CACHE_TAG;

	/**
	 * @return void
	 */
	public function _construct()
	{
		$this->_init(\Manugentoo\PartnerPortal\Model\ResourceModel\Partners::class);
	}

	/**
	 * @return string[]|void
	 */
	public function getIdentities()
	{
	}

	/**
	 * @return array
	 */
	public function getDefaultValues()
	{
		$values = [];
		return $values;
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getName()
	{
		return $this->getData(self::NAME);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getUrl()
	{
		return $this->getData(self::URL);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getEmail()
	{
		return $this->getData(self::EMAIL);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getGptRereferenceNumber()
	{
		return $this->getData(self::GPT_REFERENCE_NUMBER);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getPartnerLogo()
	{
		return $this->getData(self::PARTNER_LOGO);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getClientLogo()
	{
		return $this->getData(self::CLIENT_LOGO);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getPageTitle()
	{
		return $this->getData(self::PAGE_TITLE);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getIntroText()
	{
		return $this->getData(self::INTRO_TEXT);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getCreatedAt()
	{
		return $this->getData(self::CREATED_AT);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getOtpCode()
	{
		return $this->getData(self::OTP_CODE);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getOtpCreatedAt()
	{
		return $this->getData(self::OTP_CREATED_AT);
	}

	/**
	 * @return array|int|mixed|null
	 */
	public function getStatus()
	{
		return $this->getData(self::STATUS);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getAccessToken()
	{
		return $this->getData(self::ACCESS_TOKEN);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getAccessTokenCreatedAt()
	{
		return $this->getData(self::ACCESS_TOKEN_CREATED_AT);
	}

	/**
	 * @param $name
	 * @return PartnersInterface|Partners
	 */
	public function setName($name)
	{
		return $this->setData(self::NAME, $name);
	}

	/**
	 * @param $url
	 * @return PartnersInterface|Partners
	 */
	public function setUrl($url)
	{
		return $this->setData(self::URL, $url);
	}

	/**
	 * @param $email
	 * @return PartnersInterface|Partners
	 */
	public function setEmail($email)
	{
		return $this->setData(self::EMAIL, $email);
	}

	/**
	 * @param $gptRefereenceNum
	 * @return PartnersInterface|Partners
	 */
	public function setGptRereferenceNumber($gptRefereenceNum)
	{
		return $this->setData(self::GPT_REFERENCE_NUMBER, $gptRefereenceNum);
	}

	/**
	 * @param $partnerLogo
	 * @return PartnersInterface|Partners
	 */
	public function setPartnerLogo($partnerLogo)
	{
		return $this->setData(self::PARTNER_LOGO, $partnerLogo);
	}

	/**
	 * @param $logo
	 * @return Partners|mixed
	 */
	public function setClientLogo($logo)
	{
		return $this->setData(self::CLIENT_LOGO, $logo);
	}

	/**
	 * @param $pageTitle
	 * @return PartnersInterface|Partners
	 */
	public function setPageTitle($pageTitle)
	{
		return $this->setData(self::PAGE_TITLE, $pageTitle);
	}

	/**
	 * @param $introText
	 * @return PartnersInterface|Partners
	 */
	public function setIntroText($introText)
	{
		return $this->setData(self::INTRO_TEXT, $introText);
	}

	/**
	 * @param $createdAt
	 * @return Partners|mixed
	 */
	public function setCreatedAt($createdAt)
	{
		return $this->setData(self::CREATED_AT, $createdAt);
	}

	/**
	 * @param $otpCode
	 * @return PartnersInterface|Partners
	 */
	public function setOtpCode($otpCode)
	{
		return $this->setData(self::OTP_CODE, $otpCode);
	}

	/**
	 * @param $otpCreatedAt
	 * @return PartnersInterface|Partners
	 */
	public function setOtpCreatedAt($otpCreatedAt)
	{
		return $this->setData(self::OTP_CREATED_AT, $otpCreatedAt);
	}
	/**
	 * @param $status
	 * @return PartnersInterface|Partners
	 */
	public function setStatus($status)
	{
		return $this->setData(self::STATUS, $status);
	}

	/**
	 * @param $accessToken
	 * @return PartnersInterface|Partners
	 */
	public function setAccessToken($accessToken)
	{
		return $this->setData(self::ACCESS_TOKEN, $accessToken);
	}

	/**
	 * @param $accessTokenCreatedAt
	 * @return PartnersInterface|Partners
	 */
	public function setAccessTokenCreatedAt($accessTokenCreatedAt)
	{
		return $this->setData(self::ACCESS_TOKEN_CREATED_AT, $accessTokenCreatedAt);
	}
}