<?php
namespace Manugentoo\PartnerPortal\Observer;

use Manugentoo\PartnerPortal\Helper\AccessToken;
use Manugentoo\PartnerPortal\Helper\Partner as PartnerHelper;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class PartnerProductPriceUpdateCart
 * @package Manugentoo\PartnerPortal\Observer
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class PartnerProductPriceUpdateCart implements ObserverInterface
{
	/**
	 * @var AccessToken
	 */
	private $accessToken;

	/**
	 * @var PartnerHelper
	 */
	private $partnerHelper;

	/**
	 * @param AccessToken $accessToken
	 * @param PartnerHelper $partnerHelper
	 */
	public function __construct(
		AccessToken $accessToken,
		PartnerHelper $partnerHelper
	) {
		$this->accessToken = $accessToken;
		$this->partnerHelper = $partnerHelper;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$item = $observer->getEvent()->getData('quote_item');
		$item = ($item->getParentItem() ? $item->getParentItem() : $item);

		if ($this->accessToken->isAccessTokenExpired() == false) {

			$partner = $this->accessToken->getPartner();

			if($partner) {

				$productId = $item->getProductId();
				$partnerHelper = $this->partnerHelper;

				if($partnerProduct = $partnerHelper->getProductInPartnerSessionById($productId)) {
					$partnerPrice = $partnerProduct->getPartnerPrice();
					$item->setCustomPrice($partnerPrice);
					$item->setOriginalCustomPrice($partnerPrice);
					$item->getProduct()->setIsSuperMode(true);
				}
			}
		}
	}
}