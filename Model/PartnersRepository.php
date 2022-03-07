<?php

namespace Manugentoo\PartnerPortal\Model;

use Manugentoo\PartnerPortal\Api\Data;
use Manugentoo\PartnerPortal\Api\PartnersRepositoryInterface;
use Manugentoo\PartnerPortal\Model\ResourceModel\Partners as PartnersResource;
use Manugentoo\PartnerPortal\Model\PartnersFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class PartnersRepository
 * @package Manugentoo\PartnerPortal\Model
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class PartnersRepository implements PartnersRepositoryInterface
{
	/**
	 * @var PartnersResource
	 */
	private $resource;
	/**
	 * @var \Manugentoo\PartnerPortal\Model\PartnersFactory
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
	 * PartnersRepository constructor.
	 * @param PartnersResource $resource
	 * @param \Manugentoo\PartnerPortal\Model\PartnersFactory $partnersFactory
	 * @param Data\PartnersInterface $dataPartnerInterface
	 * @param DataObjectHelper $dataObjectHelper
	 * @param DataObjectProcessor $dataObjectProcessor
	 */
	public function __construct(
		PartnersResource $resource,
		PartnersFactory $partnersFactory,
		Data\PartnersInterface $dataPartnerInterface,
		DataObjectHelper $dataObjectHelper,
		DataObjectProcessor $dataObjectProcessor
	) {
		$this->resource = $resource;
		$this->partnersFactory = $partnersFactory;
		$this->dataPartnerInterface = $dataPartnerInterface;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->dataObjectProcessor = $dataObjectProcessor;
	}

	/**
	 * @param Data\PartnersInterface $partner
	 * @throws CouldNotSaveException
	 */
	public function save(Data\PartnersInterface $partner)
	{
		try {
			$this->resource->save($partner);
		} catch (\Exception $exception) {
			throw new CouldNotSaveException(
				__('Could now save the partner: $%1', $exception->getMessage()),
				$exception
			);
		}
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws NoSuchEntityException
	 */
	public function getById($id)
	{
		$partners = $this->partnersFactory->create();
		$partners->load($id);

		if (!$partners->getId()) {
			throw new NoSuchEntityException(
				__('Partner with %1 does not exists. $%1', $id)
			);
		}
		return $partners;
	}

	/**
	 * @param Data\PartnersInterface $partner
	 * @throws CouldNotDeleteException
	 */
	public function delete(Data\PartnersInterface $partner)
	{
		try {
			$this->resource->delete($partner);
		} catch (\Exception $exception) {
			throw new CouldNotDeleteException(
				__('Could now delete the partner: $%1', $exception->getMessage()),
				$exception
			);
		}
	}

	/**
	 * @param $id
	 * @throws CouldNotDeleteException
	 * @throws NoSuchEntityException
	 */
	public function deleteById($id)
	{
		$this->delete($this->getById($id));
	}

	/**
	 * Load Partner by Url
	 * @param $url
	 * @return false|Partners
	 */
	public function loadPartnerByUrl($url) {

		$loadedPartner = $this->resource->loadPartnerByUrl($url);

		if($loadedPartner) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			/** @var Partners $partner */
			$partner = $objectManager->create(Partners::class);
			$partner->setData($loadedPartner);

			return $partner;
		}
		return false;
	}

	/**
	 * @param $email
	 * @return false|Partners
	 */
	public function loadPartnerByEmail($email) {

		$loadedPartner = $this->resource->loadPartnerByEmail($email);

		if($loadedPartner) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			/** @var Partners $partner */
			$partner = $objectManager->create(Partners::class);
			$partner->setData($loadedPartner);
			return $partner;
		}

		return false;
	}
}