<?php

namespace Manugentoo\PartnerPortal\Plugin;

use Manugentoo\PartnerPortal\Helper\AccessToken;
use Manugentoo\PartnerPortal\Helper\Partner as PartnerHelper;

/**
 * Class ProductPartnerPrice
 * @package Manugentoo\PartnerPortal\Plugin
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class ProductPartnerPrice
{
	/**
	 * @var AccessToken
	 */
	private $accessToken;

	/**
	 * @var PartnerHelper
	 */
	private $partnerHelper;

	/**
	 * @param AccessToken $accessToken
	 * @param PartnerHelper $partnerHelper
	 */
	public function __construct(
		AccessToken $accessToken,
		PartnerHelper $partnerHelper
	) {
		$this->accessToken = $accessToken;
		$this->partnerHelper = $partnerHelper;
	}

	/**
	 * @param \Magento\Catalog\Model\Product $subject
	 * @param $result
	 * @return array|mixed|string|null
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
	{
		if ($this->accessToken->isAccessTokenExpired() == false) {

			$partner = $this->accessToken->getPartner();

			if($partner) {

				$productId = $subject->getId();
				$partnerHelper = $this->partnerHelper;

				if($partnerProduct = $partnerHelper->getProductInPartnerSessionById($productId)) {
					return $partnerProduct->getPartnerPrice();
				}
			}

		}

		return $result;
	}
}