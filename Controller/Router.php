<?php

namespace Manugentoo\PartnerPortal\Controller;

/**
 * Class Router
 * @package Manugentoo\PartnerPortal\Controller
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Router implements \Magento\Framework\App\RouterInterface
{

	/**
	 * @var \Magento\Framework\App\ActionFactory
	 */
	protected $actionFactory;

	/**
	 * @param \Magento\Framework\App\ActionFactory $actionFactory
	 */
	public function __construct(
		\Magento\Framework\App\ActionFactory $actionFactory
	) {
		$this->actionFactory = $actionFactory;
	}

	/**
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return \Magento\Framework\App\ActionInterface|void
	 */
	public function match(\Magento\Framework\App\RequestInterface $request)
	{

		$identifier = trim($request->getPathInfo(), '/');
		$d = explode('/', $identifier, 3);

		if(isset($d[0]) && ($d[0] == 'vip')) {

			$request->setModuleName('partners')->setControllerName('vip')->setActionName('index');
			$request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

			return $this->actionFactory->create(
				\Magento\Framework\App\Action\Forward::class, ['request' => $request]
			);

		}
	}
}