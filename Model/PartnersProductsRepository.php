<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Api\Data;
use Manugentoo\PartnerPortal\Api\PartnersProductsRepositoryInterface;
use Manugentoo\PartnerPortal\Model\ResourceModel\PartnersProducts as PartnersProductsResource;
use Manugentoo\PartnerPortal\Model\PartnersProductsFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class PartnersProductsRepository
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class PartnersProductsRepository implements PartnersProductsRepositoryInterface
{

	/**
	 * @var PartnersProductsResource
	 */
	private $resource;

	/**
	 * @var \Manugentoo\PartnerPortal\Model\PartnersProductsFactory
	 */
	private $partnersFactory;

	/**
	 * @var Data\PartnersInterface
	 */
	private $dataPartnerInterface;

	/**
	 * @var DataObjectHelper
	 */
	private $dataObjectHelper;

	/**
	 * @var DataObjectProcessor
	 */
	private $dataObjectProcessor;

	/**
	 * PartnersProductsRepository constructor.
	 * @param PartnersProductsResource $resource
	 * @param \Manugentoo\PartnerPortal\Model\PartnersProductsFactory $partnersProductFactory
	 * @param Data\PartnersInterface $dataPartnerInterface
	 * @param DataObjectHelper $dataObjectHelper
	 * @param DataObjectProcessor $dataObjectProcessor
	 */
	public function __construct(
		PartnersProductsResource $resource,
		PartnersProductsFactory $partnersProductFactory,
		Data\PartnersInterface $dataPartnerInterface,
		DataObjectHelper $dataObjectHelper,
		DataObjectProcessor $dataObjectProcessor
	) {
		$this->resource = $resource;
		$this->partnersFactory = $partnersProductFactory;
		$this->dataPartnerInterface = $dataPartnerInterface;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->dataObjectProcessor = $dataObjectProcessor;
	}

	/**
	 * @param Data\PartnersProductsInterface $partner
	 * @return mixed|void
	 * @throws CouldNotSaveException
	 */
	public function save(Data\PartnersProductsInterface $partner)
	{
		try {
			$this->resource->save($partner);
		} catch (\Exception $exception) {
			throw new CouldNotSaveException(
				__('Could now save the partner products: $%1', $exception->getMessage()),
				$exception
			);
		}
	}

	/**
	 * @param $id
	 * @return Data\PartnersProductsInterface
	 * @throws NoSuchEntityException
	 */
	public function getById($id)
	{
		$partners = $this->partnersFactory->create();
		$partners->load($id);

		if (!$partners->getId()) {
			throw new NoSuchEntityException(
				__('Partner Products with %1 does not exists. $%1', $id)
			);
		}
		return $partners;
	}

	/**
	 * @param Data\PartnersProductsInterface $partner
	 * @return mixed|void
	 * @throws CouldNotDeleteException
	 */
	public function delete(Data\PartnersProductsInterface $partner)
	{
		try {
			$this->resource->delete($partner);
		} catch (\Exception $exception) {
			throw new CouldNotDeleteException(
				__('Could now delete the partner products: $%1', $exception->getMessage()),
				$exception
			);
		}
	}

	/**
	 * @param $id
	 * @return mixed|void
	 * @throws CouldNotDeleteException
	 * @throws NoSuchEntityException
	 */
	public function deleteById($id)
	{
		$this->delete($this->getById($id));
	}
}