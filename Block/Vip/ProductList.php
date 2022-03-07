<?php

namespace Manugentoo\PartnerPortal\Block\Vip;

use Amasty\Preorder\Helper\Data as AmastyHelperData;
use Manugentoo\PartnerPortal\Helper\Partner as PartnerHelper;
use Manugentoo\PartnerPortal\Model\PartnersProducts;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\Collection as PartnersProductCollection;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\CollectionFactory as PartnersProductCollectionFactory;
use Manugentoo\PartnerPortal\Model\Session as PartnerSession;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Url\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;


/**
 * Class Products
 * @package Manugentoo\PartnerPortal\Block\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class ProductList extends Base
{

	/**
	 * @var PartnersProductCollectionFactory
	 */
	private $partnersProductCollectionFactory;
	/**
	 * @var ProductCollectionFactory
	 */
	private $productCollectionFactory;
	/**
	 * @var CartHelper
	 */
	private $cartHelper;
	/**
	 * @var Data
	 */
	private $urlHelper;

	public function __construct(
		Template\Context $context,
		array $data = [],
		PartnersRepository $partnersRepository,
		Registry $registry,
		FormKey $formKey,
		PartnerSession $partnerSession,
		PartnerHelper $partnerHelper,
		PartnersProductCollectionFactory $partnersProductCollectionFactory,
		ProductCollectionFactory $productCollectionFactory,
		CartHelper $cartHelper,
		Data $urlHelper
	) {
		$this->partnersProductCollectionFactory = $partnersProductCollectionFactory;
		$this->productCollectionFactory = $productCollectionFactory;
		$this->cartHelper = $cartHelper;
		$this->urlHelper = $urlHelper;
		parent::__construct($context, $data, $partnersRepository, $registry, $formKey, $partnerSession, $partnerHelper);
	}

	/**
	 * @return ProductCollection|\Magento\Framework\Data\Collection\AbstractDb
	 */
	public function getLoadedProductCollection()
	{
		$partnerId = $this->getPartner()->getId();

		/** @var PartnersProductCollection $partnersProductCollection */
		$partnersProductCollection = $this->partnersProductCollectionFactory->create();
		$partnerProductIds = $partnersProductCollection->addPartnersFilter($partnerId)->load();

		$partnerProductIdsArray = null;
		/** @var  PartnersProducts $partnerProduct */
		foreach ($partnerProductIds as $partnerProduct) {
			$partnerProductIdsArray[] = $partnerProduct->getProductId();
		}

		/** @var ProductCollection $productCollection */
		$productCollection = $this->productCollectionFactory->create();
		$productCollection->addAttributeToSelect('*');
		$productCollection->addFieldToFilter('entity_id', $partnerProductIdsArray);

		return $productCollection;
	}

	/**
	 * @param Product $product
	 * @return array
	 */
	public function getAddToCartPostParams(Product $product)
	{
		$url = $this->getAddToCartUrl($product, ['_escape' => false]);
		return [
			'action' => $url,
			'data' => [
				'product' => (int)$product->getEntityId(),
				ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
			]
		];
	}

	/**
	 * @param $product
	 * @param $additional
	 * @return string
	 */
	public function getAddToCartUrl($product, $additional = [])
	{
		if (!$product->getTypeInstance()->isPossibleBuyFromList($product)) {
			if (!isset($additional['_escape'])) {
				$additional['_escape'] = true;
			}
			if (!isset($additional['_query'])) {
				$additional['_query'] = [];
			}
			$additional['_query']['options'] = 'cart';
			return $this->getProductUrl($product, $additional);
		}
		return $this->cartHelper->getAddUrl($product, $additional);
	}

	/**
	 * @return mixed
	 */
	public function isRedirectToCartEnabled()
	{
		return $this->_scopeConfig->getValue(
			'checkout/cart/redirect_to_cart',
			ScopeInterface::SCOPE_STORE
		);
	}

	/**
	 * @return mixed
	 */
	public function getMediaUrl()
	{
		$objectManager = ObjectManager::getInstance();
		return $objectManager->get(StoreManagerInterface::class)
			->getStore()
			->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
	}

	/**
	 * @return mixed
	 */
	public function getAmastyPreOrderHelper()
	{
		$objectManager = ObjectManager::getInstance();
		return $objectManager->create(AmastyHelperData::class);
	}
}