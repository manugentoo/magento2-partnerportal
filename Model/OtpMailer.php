<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Helper\Config;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class OtpMailer
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class OtpMailer
{
	/**
	 * @var String
	 */
	const EMAIL_TEMPLATE = 'partnerportal_otp_email_template';

	/**
	 * @var TransportBuilder
	 */
	private $transportBuilder;

	/**
	 * @var StoreManagerInterface
	 */
	private $storeManager;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var State
	 */
	private $state;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @param TransportBuilder $transportBuilder
	 * @param StoreManagerInterface $storeManager
	 * @param LoggerInterface $logger
	 * @param State $state
	 * @param Config $config
	 */
	public function __construct(
		TransportBuilder $transportBuilder,
		StoreManagerInterface $storeManager,
		LoggerInterface $logger,
		State $state,
		Config $config
	) {
		$this->transportBuilder = $transportBuilder;
		$this->storeManager = $storeManager;
		$this->logger = $logger;
		$this->state = $state;
		$this->config = $config;
	}

	/**
	 * @param Partners $partner
	 * @param $otpCode
	 * @param $emailToCC
	 * @return $this
	 * @throws LocalizedException
	 * @throws \Magento\Framework\Exception\MailException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function sendOtp(Partners $partner, $otpCode, $emailToCC = [])
	{
		if (!$otpCode) {
			throw new LocalizedException(__('Invalid email OTP Code'));
		}

		if (!filter_var($partner->getEmail(), FILTER_VALIDATE_EMAIL)) {
			throw new LocalizedException(__('Invalid email recipient'));
		}

		$store = $this->storeManager->getStore();

		$transport = $this->transportBuilder
			->setTemplateIdentifier(self::EMAIL_TEMPLATE)
			->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store->getId()])
			->addTo($partner->getEmail(), $partner->getEmail());

		if (count($emailToCC) > 0) {
			foreach ($emailToCC as $ccEmails) {
				$transport->addCc(trim($ccEmails), trim($ccEmails));
			}
		}

		$templateParams = [
			'otp' => $otpCode,
			'otp_expiration' => $this->config->getOtpMinutesValidity(),
			'partner_name' => $partner->getName()
		];

		$transport->setTemplateVars($templateParams)
			->setFrom('general')
			->getTransport()
			->sendMessage();

		return $this;
	}

	/**
	 * @param TransportBuilder $transportBuilder
	 */
	public function setTransportBuilder(TransportBuilder $transportBuilder): void
	{
		$this->transportBuilder = $transportBuilder;
	}

	/**
	 * @param StoreManagerInterface $storeManager
	 */
	public function setStoreManager(StoreManagerInterface $storeManager): void
	{
		$this->storeManager = $storeManager;
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger(LoggerInterface $logger): void
	{
		$this->logger = $logger;
	}

	/**
	 * @param State $state
	 */
	public function setState(State $state): void
	{
		$this->state = $state;
	}

	/**
	 * @param mixed $consoleMode
	 */
	public function setConsoleMode($consoleMode): void
	{
		$this->consoleMode = $consoleMode;
	}


}