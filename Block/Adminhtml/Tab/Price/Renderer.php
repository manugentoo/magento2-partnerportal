<?php

namespace Manugentoo\PartnerPortal\Block\Adminhtml\Tab\Price;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

/**
 * Class Renderer
 * @package Manugentoo\PartnerPortal\Block\Adminhtml\Tab\Price
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class Renderer extends AbstractRenderer
{
	/**
	 * @param Context $context
	 * @param array $data
	 */
	public function __construct(
		Context $context,
		array $data = []
	)
	{
		parent::__construct($context, $data);
	}

	/**
	 * @param DataObject $row
	 * @return string
	 */
	public function render(DataObject $row)
	{
		$priceDisplay = $row->getPartnerPrice() != 0 ? $row->getPartnerPrice() : $row->getPrice();
		$priceDisplay = number_format($priceDisplay, 2);
		$html = '<input type="text" class="input-text validate-number partner_price" name="partner_price" value="' . $priceDisplay . '">';
		return $html;
	}
}