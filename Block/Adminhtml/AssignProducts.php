<?php

namespace Manugentoo\PartnerPortal\Block\Adminhtml;

use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\CollectionFactory as PartnersProductsCollectionFactory;

/**
 * Class AssignProducts
 * @package Manugentoo\PartnerPortal\Block\Adminhtml
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class AssignProducts extends \Magento\Backend\Block\Template
{
	/**
	 * Block template
	 *
	 * @var string
	 */
	protected $_template = 'products/assign_products.phtml';

	/**
	 * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
	 */
	protected $blockGrid;

	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * @var \Magento\Framework\Json\EncoderInterface
	 */
	protected $jsonEncoder;

	/**
	 * @var \Manugentoo\PartnerPortal\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $productFactory;
	/**
	 * @var PartnersProductsCollectionFactory
	 */
	protected $partnersProductsCollectionFactory;

	/**
	 * AssignProducts constructor.
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
	 * @param \Manugentoo\PartnerPortal\Model\ResourceModel\Product\CollectionFactory $productFactory
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\Json\EncoderInterface $jsonEncoder,
		\Manugentoo\PartnerPortal\Model\ResourceModel\Product\CollectionFactory $productFactory,
		PartnersProductsCollectionFactory $partnersProductsCollectionFactory,
		array $data = []
	) {
		$this->registry = $registry;
		$this->jsonEncoder = $jsonEncoder;
		$this->productFactory = $productFactory;
		$this->partnersProductsCollectionFactory = $partnersProductsCollectionFactory;
		parent::__construct($context, $data);
	}

	/**
	 * Return HTML of grid block
	 *
	 * @return string
	 */
	public function getGridHtml()
	{
		return $this->getBlockGrid()->toHtml();
	}

	/**
	 * Retrieve instance of grid block
	 *
	 * @return \Magento\Framework\View\Element\BlockInterface
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getBlockGrid()
	{
		if (null === $this->blockGrid) {
			$this->blockGrid = $this->getLayout()->createBlock(
				\Manugentoo\PartnerPortal\Block\Adminhtml\Tab\ProductGrid::class,
				'manugentoo.partners.product.grid'
			);
		}
		return $this->blockGrid;
	}

	/**
	 * @return string
	 */
	public function getProductsJson()
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

		$productFactory = $this->productFactory->create();
		$productFactory->addAttributeToSelect('*');
		$productFactory->addFieldToSelect(['product_id', 'position']);
		$productFactory->addFieldToFilter('entity_id', array('in' => array_keys($currentProducts)) );

		$result = [];
		if (!empty($productFactory->getData())) {
			foreach ($productFactory->getData() as $products) {
				$result[$products['entity_id']] = '';
			}
			return $this->jsonEncoder->encode($result);
		}
		return '{}';
	}

	/**
	 * @return mixed|null
	 */
	public function getItem()
	{
		return $this->registry->registry('my_item');
	}
}