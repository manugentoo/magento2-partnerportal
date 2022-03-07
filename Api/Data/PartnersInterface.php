<?php

namespace Manugentoo\PartnerPortal\Api\Data;

/**
 * Interface Partner
 * @package Manugentoo\PartnerPortal\Api\Data
 */
interface PartnersInterface
{
	/**
	 * @var string
	 */
	const MAIN_TABLE = 'manugentoo_partnerportal_partners';

	/**
	 * @var int
	 */
	const ID = 'id';
	/**
	 * @var string
	 */
	const NAME = 'name';
	/**
	 * @var string
	 */
	const EMAIL = 'email';
	/**
	 * @var string
	 */
	const URL = 'url';
	/**
	 * @var string
	 */
	const GPT_REFERENCE_NUMBER = 'gpt_reference_number';
	/**
	 * @var string
	 */
	const PARTNER_LOGO = 'partner_logo';
	/**
	 * @var string
	 */
	const CLIENT_LOGO = 'client_logo';
	/**
	 * @var string
	 */
	const PAGE_TITLE = 'page_title';
	/**
	 * @var string
	 */
	const INTRO_TEXT = 'intro_text';
	/**
	 * @var string
	 */
	const CREATED_AT = 'created_at';
	/**
	 * @var string
	 */
	const OTP_CODE = 'otp_code';
	/**
	 * @var mixed
	 */
	const OTP_CREATED_AT = 'otp_created_at';
	/**
	 * @var string
	 */
	const ACCESS_TOKEN = 'access_token';
	/**
	 * @var string
	 */
	const ACCESS_TOKEN_CREATED_AT = 'access_token_created_at';
	/**
	 * @var int
	 */
	const DEFAULT_OTP_EXPIRATION_MIN = 1;
	/**
	 * @var int
	 */
	const PARTNER_ACCESS_TOKEN_EXPIRATION_MIN = 1;
	/**
	 * @var string
	 */
	const STATUS= 'status';
	/**
	 * @var string
	 */
	const BASE_IMAGE_PATH = 'manugentoo/partnerportal/images';
	/**
	 * @var string
	 */
	const DATA_PERSISTOR_KEY = 'manugentoo_partnerportal_partners';
	/**
	 * @var string
	 */
	const PARTNER_LOGO_UPLOAD_DIR = 'manugentoo/partnerportal/images/partner/logo';
	/**
	 * @var string
	 */
	const CLIENT_LOGO_UPLOAD_DIR = 'manugentoo/partnerportal/images/partner/client/logo';
	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getUrl();

	/**
	 * @return string
	 */
	public function getEmail();

	/**
	 * @return string
	 */
	public function getGptRereferenceNumber();

	/**
	 * @return string
	 */
	public function getPartnerLogo();

	/**
	 * @return string
	 */
	public function getClientLogo();

	/**
	 * @return string
	 */
	public function getPageTitle();

	/**
	 * @return string
	 */
	public function getIntroText();

	/**
	 * @return string
	 */
	public function getCreatedAt();

	/**
	 * @return string
	 */
	public function getOtpCode();

	/**
	 * @return string
	 */
	public function getOtpCreatedAt();

	/**
	 * @return int
	 */
	public function getStatus();
	/**
	 * @return string
	 */
	public function getAccessToken();
	/**
	 * @return string
	 */
	public function getAccessTokenCreatedAt();
	/**
	 * @param $id
	 * @return PartnersInterface
	 */
	public function setId($id);

	/**
	 * @param $name
	 * @return PartnersInterface
	 */
	public function setName($name);

	/**
	 * @param $url
	 * @return PartnersInterface
	 */
	public function setUrl($url);

	/**
	 * @param $url
	 * @return PartnersInterface
	 */
	public function setEmail($email);

	/**
	 * @param $gptRefereenceNum
	 * @return PartnersInterface
	 */
	public function setGptRereferenceNumber($gptRefereenceNum);

	/**
	 * @param $partnerLogo
	 * @return PartnersInterface
	 */
	public function setPartnerLogo($partnerLogo);

	/**
	 * @param $logo
	 * @return mixed
	 */
	public function setClientLogo($logo);

	/**
	 * @param $pageTitle
	 * @return PartnersInterface
	 */
	public function setPageTitle($pageTitle);

	/**
	 * @param $introText
	 * @return PartnersInterface
	 */
	public function setIntroText($introText);

	/**
	 * @param $createdAt
	 * @return mixed
	 */
	public function setCreatedAt($createdAt);

	/**
	 * @param $otpCode
	 * @return PartnersInterface
	 */
	public function setOtpCode($otpCode);

	/**
	 * @param $otpCreatedAt
	 * @return PartnersInterface
	 */
	public function setOtpCreatedAt($otpCreatedAt);

	/**
	 * @param $status
	 * @return PartnersInterface
	 */
	public function setStatus($status);

	/**
	 * @param $accessToken
	 * @return PartnersInterface
	 */
	public function setAccessToken($accessToken);

	/**
	 * @param $accessTokenCreatedAt
	 * @return PartnersInterface
	 */
	public function setAccessTokenCreatedAt($accessTokenCreatedAt);
}