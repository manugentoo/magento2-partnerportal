<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersMessageInterface;

/**
 * Class Products
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Products extends Base
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|void
	 * @throws \Exception
	 */
	public function execute()
	{
		parent::execute();
		$partner = $this->getPartner();

		if($partner === false) {
			$redirectUrl = $this->getNoRouteUrl();
			return $this->getResponse()->setRedirect($redirectUrl);
		}


		if($partner) {

			// validate access token
			if ($this->accessTokenHelper->isAccessTokenExpired() == true) {
				if($this->helperOtp->isOtpExpired($partner) == true) {
					$this->messageManager->addErrorMessage(
						__(PartnersMessageInterface::ERROR_MESSAGE_TOKEN_EXPIRED)
					);
				}

				$indexUrl = $this->getIndexUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($indexUrl);
			}

			// validate access token
			if($this->helperOtp->isOtpExpired($partner) == true) {
				$redirectUrl = $this->getIndexUrl() . $partner->getUrl();
				return $this->getResponse()->setRedirect($redirectUrl);
			}

		}  else {
			$this->redirectToNoRoute();
		}

		return $this->_pageFactory->create();
	}
}