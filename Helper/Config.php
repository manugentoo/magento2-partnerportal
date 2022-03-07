<?php
namespace Manugentoo\PartnerPortal\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Manugentoo\PartnerPortal\Helper
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Config extends AbstractHelper
{

	/**
	 * @var string
	 */
	const GENERAL_CONFIG = 'partnerportal_config/general/';

	/**
	 * @param Context $context
	 */
	public function __construct(
		Context $context

    ) {
		parent::__construct($context);
	}

	/**
	 * @param $field
	 * @param $store
	 * @return mixed
	 */
	public function getConfig($field, $store = null)
	{
		return $this->scopeConfig->getValue(
			self::GENERAL_CONFIG . $field,
			ScopeInterface::SCOPE_STORE,
			$store
		);
	}

	/**
	 * @return mixed
	 */
	public function isEnabled()
	{
		return $this->getConfig('enable');
	}

	/**
	 * @return mixed
	 */
	public function getOtpMinutesValidity()
	{
		return $this->getConfig('otp_validity');
	}

	/**
	 * @return mixed
	 */
	public function getAccessTokenMinutesValidity(){
		return $this->getConfig('access_token_validity');
	}
}
