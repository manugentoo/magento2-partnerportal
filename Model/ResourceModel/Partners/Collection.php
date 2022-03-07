<?php

namespace Manugentoo\PartnerPortal\Model\ResourceModel\Partners;

use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\ResourceModel\Partners as PartnersResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Manugentoo\PartnerPortal\Model\ResourceModel\Partners
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Collection extends AbstractCollection
{
	/**
	 * @var string
	 */
	protected $_eventPrefix = 'partnerportal_partners_collection';

	/**
	 * @var string
	 */
	protected $_eventObject = 'partnerportal_partners_collection';

	/**
	 * Constructor
	 */
	protected function _construct()
	{
		$this->_init(Partners::class, PartnersResource::class);
	}
}