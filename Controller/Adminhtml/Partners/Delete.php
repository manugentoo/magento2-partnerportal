<?php
namespace Manugentoo\PartnerPortal\Controller\Adminhtml\Partners;

use Manugentoo\PartnerPortal\Model\Partners;
use Manugentoo\PartnerPortal\Model\PartnersRepository;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 * @package Manugentoo\PartnerPortal\Controller\Adminhtml\Partners
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Delete extends Action
{

	/**
	 * @var Partners|null
	 */
	private $partners;
	/**
	 * @var PartnersRepository
	 */
	private $partnersRepository;

	/**
	 * Delete constructor.
	 * @param Action\Context $context
	 * @param Partners $partners
	 * @param PartnersRepository $partnersRepository
	 */
	public function __construct(
		Action\Context $context,
		Partners $partners,
		PartnersRepository $partnersRepository
	) {
		$this->partners = $partners;
		$this->partnersRepository = $partnersRepository;
		parent::__construct($context);
	}

	/**
	 * @return bool
	 */
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Manugentoo_PartnerPortal::partner_delete');
    }

	/**
	 * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|mixed
	 */
	public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

		$resultRedirect = $this->resultRedirectFactory->create();
		if ($id) {
			try {
				$title = "";
				$model = $this->partnersRepository->getById($id);
				$title = $model->getName();
				$this->partnersRepository->delete($model);
				// display success message
				$this->messageManager->addSuccess(__('The partner has been deleted.'));
				// go to grid
				$this->_eventManager->dispatch(
					'adminhtml_partnersportal_partner_on_delete',
					['title' => $title, 'status' => 'success']
				);
				return $resultRedirect->setPath('*/*/');
			} catch (LocalizedException $e) {
				$this->_eventManager->dispatch(
					'adminhtml_partnersportal_partner_on_delete',
					['title' => $title, 'status' => 'fail']
				);
				// display error message
				$this->messageManager->addError($e->getMessage());
				// go back to edit form
				return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
				return $resultRedirect->setPath('*/*/');
			}
		}
        // display error message
        $this->messageManager->addError(__('We can\'t find a partners to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
