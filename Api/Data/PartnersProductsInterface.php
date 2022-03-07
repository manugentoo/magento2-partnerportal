<?php

namespace Manugentoo\PartnerPortal\Api\Data;

/**
 * Interface PartnersProductsInterface
 * @package Manugentoo\PartnerPortal\Api\Data
 */
interface PartnersProductsInterface
{
	/**
	 * @var string
	 */
	const MAIN_TABLE = 'manugentoo_partnerportal_partners_products';

	/**
	 * @var string
	 */
	const ID = 'id';

	/**
	 * @var int
	 */
	const PRODUCT_ID = 'product_id';

	/**
	 * @var int
	 */
	const PARTNER_ID = 'partner_id';

	/**
	 * @var int
	 */
	const POSITION = 'position';

	/**
	 * @var double
	 */
	const PARTNER_PRICE = 'partner_price';

	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getProductId();

	/**
	 * @return int
	 */
	public function getPartnerId();

	/**
	 * @return string
	 */
	public function getPosition();

	/**
	 * @return string
	 */
	public function getPartnerPrice();

	/**
	 * @param $id
	 * @return PartnersProductsInterface
	 */
	public function setId($id);

	/**
	 * @param $productId
	 * @return PartnersProductsInterface
	 */
	public function setProductId($productId);

	/**
	 * @param $partnerId
	 * @return PartnersProductsInterface
	 */
	public function setPartnerId($partnerId);

	/**
	 * @param $position
	 * @return PartnersProductsInterface
	 */
	public function setPosition($position);

	/**
	 * @param $partnerPrice
	 * @return PartnersProductsInterface
	 */
	public function setPartnerPrice($partnerPrice);
}