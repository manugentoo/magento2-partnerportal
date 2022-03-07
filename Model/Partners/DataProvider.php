<?php

namespace Manugentoo\PartnerPortal\Model\Partners;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Model\ResourceModel\Partners\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Class DataProvider
 * @package Manugentoo\PartnerPortal\Model\Partners
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{

	/**
	 * @var
	 */
	protected $collection;
	/**
	 * @var DataPersistorInterface
	 */
	protected $dataPersistor;

	/**
	 * @var
	 */
	protected $loadedData;
	/**
	 * @var StoreManagerInterface
	 */
	protected $storeManager;
	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * DataProvider constructor.
	 * @param $name
	 * @param $primaryFieldName
	 * @param $requestFieldName
	 * @param CollectionFactory $collectionFactory
	 * @param DataPersistorInterface $dataPersistor
	 * @param StoreManagerInterface $storeManager
	 * @param Filesystem $filesystem
	 * @param array $meta
	 * @param array $data
	 * @param PoolInterface|null $pool
	 */
	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $collectionFactory,
		DataPersistorInterface $dataPersistor,
		StoreManagerInterface $storeManager,
		Filesystem $filesystem,
		array $meta = [],
		array $data = [],
		PoolInterface $pool = null
	) {
		$this->collection = $collectionFactory->create();
		$this->dataPersistor = $dataPersistor;
		$this->storeManager = $storeManager;
		$this->filesystem = $filesystem;
		parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
	}

	/**
	 * @return mixed
	 * @throws NoSuchEntityException
	 */
	public function getData()
	{
		if (isset($this->loadedData)) {
			return $this->loadedData;
		}
		$items = $this->collection->getItems();

		/** @var PartnersInterface $item */

		foreach ($items as $item) {

			$this->loadedData[$item->getId()] = $item->getData();

			// retrieve partner logo image
			if ($item->getPartnerLogo()) {
				// replace icon to your custom field name
				$partnerLogo[PartnersInterface::PARTNER_LOGO][0]['name'] = $item->getPartnerLogo();
				$partnerLogo[PartnersInterface::PARTNER_LOGO][0]['url'] = $this->getPartnerLogoMediaUrl() . $item->getPartnerLogo();
				$partnerLogo[PartnersInterface::PARTNER_LOGO][0]['file'] = $item->getPartnerLogo();

				$imageFile = $this->getPartnerLogoMediaPath() . $item->getPartnerLogo();

				if(file_exists($imageFile)) {
					$partnerLogo[PartnersInterface::PARTNER_LOGO][0]['size'] = filesize($imageFile);
					$partnerLogoImageInfo = getimagesize($imageFile);
					$partnerLogo[PartnersInterface::PARTNER_LOGO][0]['type'] = $partnerLogoImageInfo['mime'];
				}

				$fullData = $this->loadedData;
				$this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $partnerLogo);
			}

			// retrieve client logo image
			if ($item->getClientLogo()) {
				// replace icon to your custom field name
				$clientLogo[PartnersInterface::CLIENT_LOGO][0]['name'] = $item->getClientLogo();
				$clientLogo[PartnersInterface::CLIENT_LOGO][0]['url'] = $this->getClientLogoMediaUrl() . $item->getClientLogo();
				$clientLogo[PartnersInterface::CLIENT_LOGO][0]['file'] = $item->getClientLogo();

				$imageFile = $this->getClientLogoMediaPath() . $item->getClientLogo();
				if(file_exists($imageFile)) {
					$clientLogo[PartnersInterface::CLIENT_LOGO][0]['size'] = filesize($imageFile);
					$clientLogoImageInfo = getimagesize($imageFile);
					$clientLogo[PartnersInterface::CLIENT_LOGO][0]['type'] = $clientLogoImageInfo['mime'];
				}

				$fullData = $this->loadedData;
				$this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $clientLogo);
			}

		}


		/** @var Used in save action $data */
		$data = $this->dataPersistor->get(PartnersInterface::DATA_PERSISTOR_KEY);
		if (!empty($data)) {
			$item = $this->collection->getNewEmptyItem();
			$item->setData($data);
			$this->loadedData[$item->getId()] = $item->getData();
			$this->dataPersistor->clear(PartnersInterface::DATA_PERSISTOR_KEY);
		}

		return $this->loadedData;
	}

	/**
	 * @return string
	 * @throws NoSuchEntityException
	 */
	public function getBaseMediaUrl() {
		return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
	}

	/**
	 * @return string
	 */
	public function getBaseMediaPath() {
		return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
	}

	/**
	 * @return string
	 */
	public function getPartnerLogoMediaPath() {
		return $this->getBaseMediaPath()  . PartnersInterface::PARTNER_LOGO_UPLOAD_DIR;
	}

	/**
	 * @return string
	 * @throws NoSuchEntityException
	 */
	public function getPartnerLogoMediaUrl() {
		return $this->getBaseMediaUrl() . PartnersInterface::PARTNER_LOGO_UPLOAD_DIR;
	}

	/**
	 * @return string
	 */
	public function getClientLogoMediaPath() {
		return $this->getBaseMediaPath() . PartnersInterface::CLIENT_LOGO_UPLOAD_DIR;
	}

	/**
	 * @return string
	 * @throws NoSuchEntityException
	 */
	public function getClientLogoMediaUrl() {
		return $this->getBaseMediaUrl() . PartnersInterface::CLIENT_LOGO_UPLOAD_DIR;
	}

}