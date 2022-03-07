<?php

namespace Manugentoo\PartnerPortal\Block\Adminhtml;

/**
 * Class Partners
 * @package Manugentoo\PartnerPortal\Block\Adminhtml
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Partners extends \Magento\Backend\Block\Widget\Grid\Container
{

	/**
	 *
	 */
	protected function _construct()
	{
		$this->_controller = 'adminhtml_partners';
		$this->_blockGroup = 'manugentoo_partners';
		$this->_headerText = __('Manage partners');

		parent::_construct();

		if ($this->_isAllowedAction('Manugentoo_PartnerPortal::base')) {
			$this->buttonList->update('add', 'label', __('Add Partners'));
		} else {
			$this->buttonList->remove('add');
		}
	}

	/**
	 * @param $resourceId
	 * @return bool
	 */
	protected function _isAllowedAction($resourceId)
	{
		return $this->_authorization->isAllowed($resourceId);
	}
}
