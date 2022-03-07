<?php

namespace Manugentoo\PartnerPortal\Session;

/**
 * Class Storage
 * @package Manugentoo\PartnerPortal\Session
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Storage extends \Magento\Framework\Session\Storage
{
	/**
	 *
	 */
	const SESSION_NAME = 'partner_session';

	/**
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param $namespace
	 * @param array $data
	 */
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		$namespace = self::SESSION_NAME,
		array $data = []
	) {
		parent::__construct($namespace, $data);
	}
}