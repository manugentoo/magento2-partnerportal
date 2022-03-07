<?php

namespace Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Manugentoo\PartnerPortal\Api\PartnersRepositoryInterface;

/**
 * Class GenericButton
 * @package Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class GenericButton
{
	/**
	 * @var Context
	 */
	protected $context;
	/**
	 * @var PartnersRepositoryInterface
	 */
	private $partnersRepository;

	/**
	 * GenericButton constructor.
	 * @param Context $context
	 * @param PartnersRepositoryInterface $partnersRepository
	 */
	public function __construct(
		Context $context,
		PartnersRepositoryInterface $partnersRepository
	) {
		$this->context = $context;
		$this->partnersRepository = $partnersRepository;
	}

	/**
	 * @return null
	 */
	public function getId()
	{
		try {
			return $this->partnersRepository->getById(
				$this->context->getRequest()->getParam('id')
			)->getId();
		} catch (NoSuchEntityException $e) {
		}
		return null;
	}

	/**
	 * @param string $route
	 * @param array $params
	 * @return string
	 */
	public function getUrl($route = '', $params = [])
	{
		return $this->context->getUrlBuilder()->getUrl($route, $params);
	}
}