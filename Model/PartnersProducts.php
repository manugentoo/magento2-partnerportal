<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Api\Data\PartnersProductsInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class PartnersProducts
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class PartnersProducts extends AbstractModel implements PartnersProductsInterface, IdentityInterface
{
	/**
	 * @var string
	 */
	const CACHE_TAG = 'partners_products';

	/**
	 * @var string
	 */
	protected $_cacheTag = self::CACHE_TAG;

	/**
	 * Object constructor
	 */
	public function _construct()
	{
		$this->_init(ResourceModel\PartnersProducts::class);
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
	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	/**
	 * @return array|int|mixed|null
	 */
	public function getPartnerId()
	{
		return $this->getData(self::PARTNER_ID);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getPosition()
	{
		return $this->getData(self::POSITION);
	}

	/**
	 * @return array|mixed|string|null
	 */
	public function getPartnerPrice()
	{
		return $this->getData(self::PARTNER_PRICE);
	}

	/**
	 * @param $id
	 * @return array|PartnersProductsInterface|PartnersProducts|mixed|null
	 */
	public function setId($id)
	{
		return $this->getData(self::ID, $id);
	}

	/**
	 * @param $productId
	 * @return array|PartnersProductsInterface|mixed|null
	 */
	public function setProductId($productId)
	{
		return $this->getData(self::PRODUCT_ID);
	}

	/**
	 * @param $partnerId
	 * @return PartnersProductsInterface|PartnersProducts
	 */
	public function setPartnerId($partnerId)
	{
		return $this->setData(self::PARTNER_ID, $partnerId);
	}

	/**
	 * @param $position
	 * @return PartnersProductsInterface|PartnersProducts
	 */
	public function setPosition($position)
	{
		return $this->setData(self::POSITION, $position);
	}

	/**
	 * @param $partnerPrice
	 * @return PartnersProductsInterface|PartnersProducts
	 */
	public function setPartnerPrice($partnerPrice)
	{
		return $this->setData(self::PARTNER_PRICE, $partnerPrice);
	}
}