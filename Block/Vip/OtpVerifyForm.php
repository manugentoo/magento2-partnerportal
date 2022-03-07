<?php
namespace Manugentoo\PartnerPortal\Block\Vip;

/**
 * Class OtpVerifyForm
 * @package Manugentoo\PartnerPortal\Block\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpVerifyForm extends Base
{
	/**
	 * @return string
	 */
	public function getActionUrl() {
		return $this->getUrl('*/*/otpsubmit/') . $this->getPartner()->getUrl();;
	}

	public function getResendOtpUrl() {
		return $this->getUrl('*/*/otpresend/') . $this->getPartner()->getUrl();
	}
}