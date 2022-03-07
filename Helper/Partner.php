<?php

namespace Manugentoo\PartnerPortal\Helper;

use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersProducts;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\Collection as PartnerProductsCollection;
use Manugentoo\PartnerPortal\Model\Session as PartnerSession;

/**
 * Class Partner
 * @package Manugentoo\PartnerPortal\Helper
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Partner
{
	/**
	 * @var PartnerSession
	 */
	protected $partnerSession;
	/**
	 * @var
	 */
	protected $partner;
	/**
	 * @var
	 */
	protected $partnerProducts;

	/**
	 * @param PartnerSession $partnerSession
	 */
	public function __construct(
		PartnerSession $partnerSession
	) {
		$this->partnerSession = $partnerSession;
	}

	/**
	 * @return mixed
	 */
	public function getPartner() {
		return $this->partnerSession->getPartner();
	}

	/**
	 * @return mixed
	 */
	public function getPartnerProducts() {
		return $this->partnerSession->getPartnerProducts();
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 */
	public function setPartner(Partners $partner)
	{
		$this->partnerSession->setPartner($partner);
		return $this;
	}

	/**
	 * @param PartnerProductsCollection $partnerProducts
	 * @return $this
	 */
	public function setPartnerProducts(PartnerProductsCollection $partnerProducts) {
		$this->partnerSession->setPartnerProducts($partnerProducts);
		return $this;
	}

	/**
	 * @param $productId
	 * @return false|PartnersProducts
	 */
	public function getProductInPartnerSessionById($productId) {
		$partnerProducts = $this->getPartnerProducts();
		if ($partnerProducts) {
			/** @var PartnersProducts $product */
			foreach($partnerProducts as $product) {
				if ($productId == $product->getProductId()) {
					return $product;
				}
			}
		}

		return false;
	}
}