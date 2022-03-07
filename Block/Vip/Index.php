<?php
namespace Manugentoo\PartnerPortal\Block\Vip;

/**
 * Class Index
 * @package Manugentoo\PartnerPortal\Block\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Index extends Base
{
	/**
	 * @return string
	 */
	public function getOtpActionUrl() {
		return $this->getUrl('*/*/otpsend/');
	}
}