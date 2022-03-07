<?php

namespace Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts;

use Manugentoo\PartnerPortal\Model\PartnersProducts;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts as PartnersProductsResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Collection extends AbstractCollection
{
	/**
	 * @var string
	 */
	protected $_eventPrefix = 'partnerportal_partnersproduct_collection';

	/**
	 * @var string
	 */
	protected $_eventObject = 'partnerportal_partnersproduct_collection';

	/**
	 * Constructor
	 */
	protected function _construct()
	{
		$this->_init(PartnersProducts::class, PartnersProductsResource::class);
	}

	/**
	 * Retrieve products from partners
	 * @param $partnerId
	 * @return $this
	 */
	public function addPartnersFilter($partnerId) {
		$this->getSelect()
			->where('partner_id = ?', $partnerId);
		return $this;
	}

	/**
	 * @param $productId
	 * @return $this
	 */
	public function addProductIdFilter($productId) {
		$this->getSelect()
			->where('product_id = ?', $productId);
		return $this;
	}
}