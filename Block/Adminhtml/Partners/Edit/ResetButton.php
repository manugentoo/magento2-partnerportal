<?php
namespace Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 * @package Manugentoo\PartnerPortal\Block\Adminhtml\Partners\Edit
 * @author Manu Gentoo <manugentoo@gmail.com>
 */
class ResetButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}