<?php

namespace Manugentoo\PartnerPortal\Model\ResourceModel;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Api\Data\PartnersProductsInterface;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\CollectionFactory as PartnersProductsCollectionFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Partners
 * @package Manugentoo\PartnerPortal\Model\ResourcesModel
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Partners extends AbstractDb
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
	 * @var PartnersProductsCollectionFactory
	 */
	protected $partnersProductsCollectionFactory;

	/**
	 * Partners constructor.
	 * @param Context $context
	 * @param DateTime $date
	 * @param PartnersProductsCollectionFactory $partnersProductsCollectionFactory
	 */
	public function __construct(
		Context $context,
		DateTime $date,
		PartnersProductsCollectionFactory $partnersProductsCollectionFactory
	) {
		$this->_date = $date;
		$this->partnersProductsCollectionFactory = $partnersProductsCollectionFactory;
		parent::__construct($context);
	}

	/**
	 *
	 */
	protected function _construct()
	{
		$this->_init(PartnersInterface::MAIN_TABLE, PartnersInterface::ID);
	}

	/**
	 * @param AbstractModel $object
	 * @return Partners
	 */
	protected function _beforeSave(AbstractModel $object)
	{
		if ($object->isObjectNew()) {
			$object->setCreatedAt($this->_date->date());
		}
		return parent::_beforeSave($object);
	}

	/**
	 * @param AbstractModel $object
	 * @return Partners
	 */
	protected function _afterSave(AbstractModel $object)
	{
		$this->_savePartnerProducts($object);
		return parent::_afterSave($object);
	}

	/**
	 * @param AbstractModel $object
	 * @return $this
	 */
	protected function _savePartnerProducts(AbstractModel $object)
	{
		$partnerId = $object->getId();
		$partnerProducts = $object->getPartnerProducts();
		$partnerProductsArray = json_decode($partnerProducts,true);
		$postedProducts = $partnerProductsArray;

		/** @var \Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\Collection $partnerProductsCollection */
		$partnerProductsCollection = $this->partnersProductsCollectionFactory->create();
		$partnerProductsCollection->addPartnersFilter($partnerId);
		$partnerProductsCollection->load();

		$oldProducts = [];
		foreach($partnerProductsCollection as $product) {
			$oldProducts[$product->getProductId()] = $product->getPosition();
		}

		// find out which are products to update
		if($postedProducts) {

			// find out which are products to delete
			$insert = array_diff_key($postedProducts, $oldProducts);
			$delete = array_diff_key($oldProducts, $postedProducts);

			$update = array_intersect_key($postedProducts, $oldProducts);
			$update = array_diff_assoc($update, $oldProducts);
		}

		$connection = $this->getConnection();

		// delete
		if (!empty($delete)) {
			$cond = ['product_id IN(?)' => array_keys($delete), 'partner_id=?' => $partnerId];
			$connection->delete(PartnersProductsInterface::MAIN_TABLE, $cond);
		}

		// insert
		if (!empty($insert)) {
			$data = [];
			foreach ($insert as $productId => $productPrice) {
				$data[] = [
					'partner_id' => (int)$partnerId,
					'product_id' => (int)$productId,
					'partner_price' => (double)$productPrice,
				];
			}
			$connection->insertMultiple(PartnersProductsInterface::MAIN_TABLE, $data);
		}

		// update
		if (!empty($update)) {
			foreach ($update as $productId => $partnerPrice) {
				if($partnerPrice > 0) {
					$bind = ['partner_price' => $partnerPrice];
					$where = ['partner_id = ?' => (int)$partnerId, 'product_id = ?' => $productId];
					$connection->update(PartnersProductsInterface::MAIN_TABLE, $bind, $where);
				}
			}
		}
	}

	/**
	 * @param $url
	 * @return mixed
	 * @throws \Zend_Db_Statement_Exception
	 */
	public function loadPartnerByUrl($url) {
		$connection = $this->getConnection();
		$tableName = PartnersInterface::MAIN_TABLE;
		$query = $connection->query('SELECT * FROM `' . $tableName . '` WHERE url=? LIMIT 1', $url );
		return $query->fetch();
	}

	/**
	 * @param $email
	 * @return mixed
	 * @throws \Zend_Db_Statement_Exception
	 */
	public function loadPartnerByEmail($email) {
		$connection = $this->getConnection();
		$tableName = PartnersInterface::MAIN_TABLE;
		$query = $connection->query('SELECT * FROM `' . $tableName . '` WHERE email=? LIMIT 1', $email);
		return $query->fetch();
	}
}