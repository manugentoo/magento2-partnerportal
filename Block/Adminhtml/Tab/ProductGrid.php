<?php

namespace Manugentoo\PartnerPortal\Block\Adminhtml\Tab;

use Manugentoo\PartnerPortal\Api\Data\PartnersProductsInterface;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\CollectionFactory as PartnersProductsCollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Store;

/**
 * Class ProductGrid
 * @package Manugentoo\PartnerPortal\Block\Adminhtml\Tab
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class ProductGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{

	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $coreRegistry = null;

	/**
	 * @var \Magento\Catalog\Model\ProductFactory
	 */
	protected $productFactory;

	/**
	 * @var \Manugentoo\PartnerPortal\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $productCollFactory;
	/**
	 * @var PartnersProductsCollectionFactory
	 */
	private $partnersProductsCollectionFactory;


	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Backend\Helper\Data $backendHelper,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Manugentoo\PartnerPortal\Model\ResourceModel\Product\CollectionFactory $productCollFactory,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\Module\Manager $moduleManager,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		Visibility $visibility = null,
		PartnersProductsCollectionFactory $partnersProductsCollectionFactory,
		array $data = []
	) {
		$this->productFactory = $productFactory;
		$this->productCollFactory = $productCollFactory;
		$this->coreRegistry = $coreRegistry;
		$this->moduleManager = $moduleManager;
		$this->_storeManager = $storeManager;
		$this->visibility = $visibility ?: ObjectManager::getInstance()->get(Visibility::class);
		$this->partnersProductsCollectionFactory = $partnersProductsCollectionFactory;
		parent::__construct($context, $backendHelper, $data);
	}

	/**
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/index/grids', ['_current' => true]);
	}

	/**
	 * [_construct description]
	 * @return [type] [description]
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setId('manugentoo_partners_products');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getRequest()->getParam('entity_id')) {
			$this->setDefaultFilter(['in_products' => 1]);
		} else {
			$this->setDefaultFilter(['in_products' => 0]);
		}
		$this->setSaveParametersInSession(true);
	}

	/**
	 * @return ProductGrid
	 */
	protected function _prepareCollection()
	{
		$store = $this->_getStore();
		$collection = $this->productFactory->create()->getCollection()->addAttributeToSelect(
			'sku'
		)->addAttributeToSelect(
			'name'
		)->addAttributeToSelect(
			'attribute_set_id'
		)->addAttributeToSelect(
			'type_id'
		)->setStore(
			$store
		);

		if ($this->moduleManager->isEnabled('Magento_CatalogInventory')) {
			$collection->joinField(
				'qty',
				'cataloginventory_stock_item',
				'qty',
				'product_id=entity_id',
				'{{table}}.stock_id=1',
				'left'
			);
		}
		if ($store->getId()) {
			$collection->setStoreId($store->getId());
			$collection->addStoreFilter($store);
			$collection->joinAttribute(
				'name',
				'catalog_product/name',
				'entity_id',
				null,
				'inner',
				Store::DEFAULT_STORE_ID
			);
			$collection->joinAttribute(
				'status',
				'catalog_product/status',
				'entity_id',
				null,
				'inner',
				$store->getId()
			);
			$collection->joinAttribute(
				'visibility',
				'catalog_product/visibility',
				'entity_id',
				null,
				'inner',
				$store->getId()
			);
			$collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
		} else {
			$collection->addAttributeToSelect('price');
			$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
			$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
		}

		// join price on from partner portal
		$joinConditions = 'e.entity_id = pp.product_id';
		$collection->getSelect()->joinLeft(
			['pp' => PartnersProductsInterface::MAIN_TABLE],
			$joinConditions,
			[]
		)->columns("pp.partner_price");

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	/**
	 * [get store id]
	 *
	 * @return Store
	 */
	protected function _getStore()
	{
		$storeId = (int)$this->getRequest()->getParam('store', 0);
		return $this->_storeManager->getStore($storeId);
	}

	/**
	 * @param \Magento\Backend\Block\Widget\Grid\Column $column
	 * @return $this|ProductGrid
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function _addColumnFilterToCollection($column)
	{
		if ($column->getId() == 'in_products') {
			$productIds = $this->_getSelectedProducts();
			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
			} else {
				if ($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}

	/**
	 * @return array
	 */
	protected function _getSelectedProducts()
	{
		$products = array_keys($this->getSelectedProducts());
		return $products;
	}

	/**
	 * @return array
	 */
	public function getSelectedProducts()
	{
		$partnerId = $this->getRequest()->getParam('id');

		/** @var \Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\Collection $partnerProductsCollection */
		$partnerProductsCollection = $this->partnersProductsCollectionFactory->create();
		$partnerProductsCollection->addPartnersFilter($partnerId);
		$partnerProductsCollection->load();

		$currentProducts = [];
		foreach($partnerProductsCollection as $product) {
			$currentProducts[$product->getProductId()] = $product->getPosition();
		}

		$model = $this->productCollFactory->create()
			->addAttributeToSelect('*')
			->addFieldToFilter('entity_id', array('in' => array_keys($currentProducts)) );

		$grids = [];
		foreach ($model as $key => $value) {
			$grids[] = $value->getEntityId();
		}

		$prodId = [];
		foreach ($grids as $obj) {
			$prodId[$obj] = ['position' => $currentProducts[$obj]];
		}

		return $prodId;
	}

	/**
	 * @return Extended
	 */
	protected function _prepareColumns()
	{
		$this->addColumn(
			'in_products',
			[
				'type' => 'checkbox',
				'html_name' => 'products_id',
				'required' => true,
				'values' => $this->_getSelectedProducts(),
				'align' => 'center',
				'index' => 'entity_id',
			]
		);

		$this->addColumn(
			'entity_id',
			[
				'header' => __('ID'),
				'width' => '50px',
				'index' => 'entity_id',
				'type' => 'number',
			]
		);
		$this->addColumn(
			'name',
			[
				'header' => __('Name'),
				'index' => 'name',
				'header_css_class' => 'col-type',
				'column_css_class' => 'col-type',
			]
		);
		$this->addColumn(
			'sku',
			[
				'header' => __('SKU'),
				'index' => 'sku',
				'header_css_class' => 'col-sku',
				'column_css_class' => 'col-sku',
			]
		);
		$store = $this->_getStore();
		$this->addColumn(
			'price',
			[
				'header' => __('Current Price'),
				'type' => 'price',
				'currency_code' => $store->getBaseCurrency()->getCode(),
				'index' => 'price',
				'header_css_class' => 'col-price',
				'column_css_class' => 'col-price',
			]
		);
		$this->addColumn(
			'partner_price',
			[
				'header' => __('Partner Price'),
				'name' => 'partner_price',
				'width' => 60,
				'index' => 'partner_price',
				'renderer' => \Manugentoo\PartnerPortal\Block\Adminhtml\Tab\Price\Renderer::class
			]
		);

		return parent::_prepareColumns();
	}
}