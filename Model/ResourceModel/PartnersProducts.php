<?php

namespace Manugentoo\PartnerPortal\Model\ResourceModel;

use Manugentoo\PartnerPortal\Api\Data\PartnersProductsInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;


/**
 * Class PartnersProducts
 * @package Manugentoo\PartnerPortal\Model\ResourceModel
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class PartnersProducts extends AbstractDb
{

	/**
	 * @var Context
	 */
	private $context;
	/**
	 * @var DateTime
	 */
	private $date;

	/**
	 * Partners constructor.
	 * @param Context $context
	 * @param DateTime $date
	 */
	public function __construct(
		Context $context,
		DateTime $date
	) {
		$this->_date = $date;
		parent::__construct($context);
	}

	/**
	 * construct
	 */
	protected function _construct()
	{
		$this->_init(PartnersProductsInterface::MAIN_TABLE, PartnersProductsInterface::ID);
	}

}