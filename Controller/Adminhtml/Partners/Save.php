<?php

namespace Manugentoo\PartnerPortal\Controller\Adminhtml\Partners;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;
use Manugentoo\PartnerPortal\Api\PartnersRepositoryInterface;
use Manugentoo\PartnerPortal\Model\PartnersFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Manugentoo\PartnerPortal\Controller\Adminhtml\Partners
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Save extends \Magento\Backend\App\Action
{

	/**
	 * @var DataPersistorInterface
	 */
	protected $dataPersistor;
	/**
	 * @var PartnersFactory|null
	 */
	private $partnersFactory;
	/**
	 * @var PartnersRepositoryInterface|null
	 */
	private $partnersRepository;

	/**
	 * Save constructor.
	 * @param Action\Context $context
	 * @param DataPersistorInterface $dataPersistor
	 * @param PartnersFactory|null $partnersFactory
	 * @param PartnersRepositoryInterface|null $partnersRepository
	 */
	public function __construct(
		Action\Context $context,
		DataPersistorInterface $dataPersistor,
		PartnersFactory $partnersFactory = null,
		PartnersRepositoryInterface $partnersRepository = null
	) {
		$this->dataPersistor = $dataPersistor;
		$this->partnersFactory = $partnersFactory ?: ObjectManager::getInstance()->get(PartnersFactory::class);
		$this->partnersRepository = $partnersRepository ?: ObjectManager::getInstance()->get(
			PartnersRepositoryInterface::class
		);
		parent::__construct($context);
	}

	/**
	 * @return bool
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('PartnerPortal::partner_save');
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$data = $this->getRequest()->getPostValue();

		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();

		if ($data) {
			try {

				if (empty($data['id'])) {
					$data['id'] = null;
				}

				/** @var \Manugentoo\PartnerPortal\Model\Partners $model */
				$model = $this->partnersFactory->create();

				$id = $this->getRequest()->getParam('id');

				if ($id) {
					try {
						$model = $this->partnersRepository->getById($id);
					} catch (LocalizedException $e) {
						$this->messageManager->addErrorMessage(__('This partners no longer exists.'));
						return $resultRedirect->setPath('*/*/');
					}
				}

				// validate url for new entries
				$urlKey = $this->getRequest()->getParam('url');
				if(!$model->getId()) {
					if($this->partnersRepository->loadPartnerByUrl($urlKey) !== false) {
						throw new LocalizedException(__('Your chosen custom Partner Url is already taken.'));
					}
				} else {
					if($model->getUrl() != $urlKey) {
						if($this->partnersRepository->loadPartnerByUrl($urlKey) !== false) {
							throw new LocalizedException(__('Your chosen custom Partner Url is already taken.'));
						}
					}
				}

				// validate Email for new entries
				$partnerEmail = $this->getRequest()->getParam('email');
				if(!$model->getId()) {
					if($this->partnersRepository->loadPartnerByEmail($partnerEmail) !== false) {
						throw new LocalizedException(__('Partner Email already exists'));
					}
				} else {
					if($model->getEmail() != $partnerEmail) {
						if($this->partnersRepository->loadPartnerByEmail($partnerEmail) !== false) {
							throw new LocalizedException(__('Partner Email already exists'));
						}
					}
				}

				// fix partner_logo file name before setting the data to model
				if(isset($data[PartnersInterface::PARTNER_LOGO][0]['file'])) {
					$image = $data[PartnersInterface::PARTNER_LOGO][0];
					$data[PartnersInterface::PARTNER_LOGO] = $image['file'];
				}
				else {
					$data[PartnersInterface::PARTNER_LOGO] = null;
				}

				// fix client_logo file name before setting the data to model
				if(isset($data[PartnersInterface::CLIENT_LOGO][0]['file'])) {
					$image = $data[PartnersInterface::CLIENT_LOGO][0];
					$data[PartnersInterface::CLIENT_LOGO] = $image['file'];
				}
				else {
					$data[PartnersInterface::CLIENT_LOGO] = null;
				}

				$model->setData($data);

				$this->_eventManager->dispatch(
					'partnersportal_partner_save_prepare_save',
					['partners' => $model, 'request' => $this->getRequest()]
				);

				$this->partnersRepository->save($model);
				$this->messageManager->addSuccessMessage(__('You saved the partner.'));
				$this->dataPersistor->clear(PartnersInterface::DATA_PERSISTOR_KEY);
				if ($this->getRequest()->getParam('back')) {
					return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
				}
				return $resultRedirect->setPath('*/*/');
			} catch (LocalizedException $e) {
				$this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
			} catch (\Exception $e) {
				$this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the partner.'));
			}

			$this->dataPersistor->set(PartnersInterface::DATA_PERSISTOR_KEY, $data);
			return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
		}
		return $resultRedirect->setPath('*/*/');
	}
}