<?php
namespace Manugentoo\PartnerPortal\Block\Vip;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Helper\Partner as PartnerHelper;
use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Manugentoo\PartnerPortal\Model\Session as PartnerSession;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class Base
 * @package Manugentoo\PartnerPortal\Block\Vip
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Base extends Template
{
	/**
	 * @var PartnersRepository
	 */
	protected $partnersRepository;
	/**
	 * @var Registry
	 */
	protected $registry;
	/**
	 * @var FormKey
	 */
	protected $formKey;
	/**
	 * @var PartnerSession
	 */
	protected $partnerSession;
	/**
	 * @var PartnerHelper
	 */
	protected $partnerHelper;

	/**
	 * @param Template\Context $context
	 * @param array $data
	 * @param PartnersRepository $partnersRepository
	 * @param Registry $registry
	 * @param FormKey $formKey
	 * @param PartnerSession $partnerSession
	 * @param PartnerHelper $partnerHelper
	 */
	public function __construct (
		Template\Context $context,
		array $data = [],
		PartnersRepository $partnersRepository,
		Registry $registry,
		FormKey $formKey,
		PartnerSession $partnerSession,
		PartnerHelper $partnerHelper
	)  {
		parent::__construct($context, $data);
		$this->partnersRepository = $partnersRepository;
		$this->registry = $registry;
		$this->formKey = $formKey;
		$this->partnerSession = $partnerSession;
		$this->partnerHelper = $partnerHelper;
	}

	/**
	 * @return Partners|null
	 */
	public function getPartner(){

		if($partner = $this->partnerHelper->getPartner()) {
			return $partner;
		}

		return false;
	}

	/**
	 * @return string
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getFormKey() {
		return $this->formKey->getFormKey();
	}

	/**
	 * @return false|string
	 */
	public function getPartnerHeaderImage() {

		$uploadDir = PartnersInterface::PARTNER_LOGO_UPLOAD_DIR;
		$partner = $this->getPartner();
		$partnerClientLogo = $partner->getPartnerLogo();

		if($partnerClientLogo) {
			return $this->getUrl() . 'pub/media/' . $uploadDir . $partnerClientLogo;
		}

		return false;
	}

	/**
	 * @return false|string
	 */
	public function getClientHeaderImage() {

		$uploadDir = PartnersInterface::CLIENT_LOGO_UPLOAD_DIR;
		$partner = $this->getPartner();
		$partnerClientLogo = $partner->getClientLogo();

		if($partnerClientLogo) {
			return $this->getUrl() . 'pub/media/' . $uploadDir . $partnerClientLogo;
		}

		return false;
	}
}