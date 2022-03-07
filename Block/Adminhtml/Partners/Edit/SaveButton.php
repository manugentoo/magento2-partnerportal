<?php
namespace Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 * @package Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Partner'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}