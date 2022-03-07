<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Session\Storage;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\Generic;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class Session
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Session extends \Magento\Framework\Session\SessionManager
{
	/**
	 * @var Generic
	 */
	protected $_session;
	/**
	 * @var null
	 */
	protected $_coreUrl = null;
	/**
	 * @var
	 */
	protected $_configShare;
	/**
	 * @var
	 */
	protected $_urlFactory;
	/**
	 * @var ManagerInterface
	 */
	protected $_eventManager;
	/**
	 * @var \Magento\Framework\App\Response\Http
	 */
	protected $response;
	/**
	 * @var
	 */
	protected $_sessionManager;

	/**
	 * @param Http $request
	 * @param SidResolverInterface $sidResolver
	 * @param ConfigInterface $sessionConfig
	 * @param SaveHandlerInterface $saveHandler
	 * @param ValidatorInterface $validator
	 * @param StorageInterface $storage
	 * @param CookieManagerInterface $cookieManager
	 * @param CookieMetadataFactory $cookieMetadataFactory
	 * @param Context $httpContext
	 * @param State $appState
	 * @param Generic $session
	 * @param ManagerInterface $eventManager
	 * @param \Magento\Framework\App\Response\Http $response
	 * @throws \Magento\Framework\Exception\SessionException
	 */
	public function __construct(
		Http $request,
		SidResolverInterface $sidResolver,
		ConfigInterface $sessionConfig,
		SaveHandlerInterface $saveHandler,
		ValidatorInterface $validator,
		StorageInterface $storage,
		CookieManagerInterface $cookieManager,
		CookieMetadataFactory $cookieMetadataFactory,
		Context $httpContext,
		State $appState,
		Generic $session,
		ManagerInterface $eventManager,
		\Magento\Framework\App\Response\Http $response
	) {
		$this->_session = $session;
		$this->_eventManager = $eventManager;

		parent::__construct(
			$request,
			$sidResolver,
			$sessionConfig,
			$saveHandler,
			$validator,
			$storage,
			$cookieManager,
			$cookieMetadataFactory,
			$appState
		);
		$this->response = $response;
		$this->_eventManager->dispatch(Storage::SESSION_NAME . '_init', [Storage::SESSION_NAME . '_session' => $this]);
	}
}