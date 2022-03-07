<?php
namespace Manugentoo\PartnerPortal\Block\Vip;

/**
 * Class OtpResendForm
 * @package Manugentoo\PartnerPortal\Block\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpResendForm extends Base
{
	/**
	 * @return string
	 */
	public function getActionUrl() {
		return $this->getUrl('*/*/otpresend/');
	}
}