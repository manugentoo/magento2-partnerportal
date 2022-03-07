<?php

namespace Manugentoo\PartnerPortal\Controller\Vip;

use Manugentoo\PartnerPortal\Helper\AccessToken as AccessTokenHelper;
use Manugentoo\PartnerPortal\Helper\Config as PartnerPortalConfiguration;
use Manugentoo\PartnerPortal\Helper\Otp;
use Manugentoo\PartnerPortal\Helper\Partner as PartnerHelper;
use Manugentoo\PartnerPortal\Model\OtpMailer;
use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\Collection as PartnerProductsCollection;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts\CollectionFactory as PartnerProductCollectionFactory;
use Manugentoo\PartnerPortal\Model\Session as PartnerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class BaseController
 * @package Manugentoo\PartnerPortal\Controller\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Base extends Action
{
	/**
	 * @var PageFactory
	 */
	protected $_pageFactory;
	/**
	 * @var ScopeConfigInterface
	 */
	protected $config;
	/**
	 * @var UrlInterface
	 */
	protected $url;
	/**
	 * @var PartnersRepository
	 */
	protected $partnersRepository;
	/**
	 * @var Registry
	 */
	protected $registry;
	/**
	 * @var Otp
	 */
	protected $helperOtp;
	/**
	 * @var OtpMailer
	 */
	protected $otpMailer;
	/**
	 * @var DateTime
	 */
	protected $date;
	/**
	 * @var  Partners $partner
	 */
	protected $partner;
	/**
	 * @var AccessTokenHelper
	 */
	protected $accessTokenHelper;
	/**
	 * @var PartnerPortalConfiguration
	 */
	protected $partnerPortalConfig;
	/**
	 * @var PartnerSession
	 */
	protected $partnerSession;
	/**
	 * @var PartnerProductCollectionFactory
	 */
	protected $partnerProductsCollectionFactory;
	/**
	 * @var PartnerProductsCollection
	 */
	protected $partnerProducts;
	/**
	 * @var PartnerHelper
	 */
	protected $partnerHelper;

	public function __construct(
		Context $context,
		PageFactory $pageFactory,
		UrlInterface $url,
		ScopeConfigInterface $config,
		PartnersRepository $partnersRepository,
		Registry $registry,
		Otp $helperOtp,
		AccessTokenHelper $accessTokenHelper,
		OtpMailer $otpMailer,
		DateTime $date,
		PartnerPortalConfiguration $partnerPortalConfig,
		PartnerSession $partnerSession,
		PartnerHelper $partnerHelper,
		PartnerProductCollectionFactory $partnerProductsCollectionFactory
	) {
		$this->url = $url;
		$this->config = $config;
		$this->_pageFactory = $pageFactory;
		$this->partnersRepository = $partnersRepository;
		$this->registry = $registry;
		$this->helperOtp = $helperOtp;
		$this->otpMailer = $otpMailer;
		$this->date = $date;
		$this->accessTokenHelper = $accessTokenHelper;
		$this->partnerPortalConfig = $partnerPortalConfig;
		$this->partnerSession = $partnerSession;
		$this->partnerHelper = $partnerHelper;
		$this->partnerProductsCollectionFactory = $partnerProductsCollectionFactory;
		return parent::__construct($context);
	}

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 */
	public function execute()
	{
		if ($this->partnerPortalConfig->isEnabled() == false) {
			$redirectUrl = $this->url->getUrl('/');
			return $this->getResponse()->setRedirect($redirectUrl);
		}

		$request = $this->getRequest();

		// prevent access on base controller
		if ($request->getActionName() == 'base') {
			return $this->getResponse()->setRedirect(
				$this->getIndexUrl()
			);
		}
	}

	/**
	 * @return false|Partners
	 */
	public function getPartner()
	{
		return $this->partnerHelper->getPartner();
	}

	/**
	 * @return string
	 */
	protected function getIndexUrl()
	{
		return $this->url->getUrl('*/*/index');
	}

	/**
	 * @param Partners $partner
	 * @return $this
	 */
	protected function registerPartner(Partners $partner)
	{
		return $this->partnerHelper->registerPartner($partner);
	}

	/**
	 * @return string
	 */
	protected function getPartnerProductsUrl()
	{
		return $this->url->getUrl('*/*/products');
	}

	/**
	 * @return string
	 */
	protected function getOtpVerifyUrl()
	{
		return $this->url->getUrl('*/*/otpverify');
	}

	/**
	 * @return string
	 */
	protected function getNoRouteUrl()
	{
		return $this->url->getUrl('*/*/noroute');
	}
}