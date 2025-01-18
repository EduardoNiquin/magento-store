<?php

declare(strict_types=1);

namespace Eniquin\WidgetSlider\Block\Adminhtml\Widget;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Clase base que inyecta "label", "label_add_row", "label_remove_row", etc.
 */
class Dynamic extends Widget
{
    /** @var ObjectManagerInterface */
    protected $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->objectManager = $objectManager;
        parent::__construct($context, $data);
    }

    public function prepareElementHtml(AbstractElement $element): AbstractElement
    {
        $collectionClass = $this->getData('collection');
        if ($collectionClass) {
            $collection = $this->objectManager->get($collectionClass);
            if ($collection instanceof OptionSourceInterface) {
                $element->setData('options', $collection->toOptionArray());
            }
        }

        $element->setData('label', $this->getData('label'));
        $element->setData('label_add_row', $this->getData('label_add_row') ?: __('Add Row'));
        $element->setData('label_remove_row', $this->getData('label_remove_row') ?: __('Remove Row'));

        return $element;
    }
}
