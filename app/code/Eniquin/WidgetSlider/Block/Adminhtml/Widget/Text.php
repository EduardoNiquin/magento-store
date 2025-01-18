<?php

declare(strict_types=1);

namespace Eniquin\WidgetSlider\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Clase para crear múltiples inputs de texto dinámicamente
 */
class Text extends Template implements RendererInterface
{
    /** @var Factory */
    protected $elementFactory;

    /** @var AbstractElement */
    protected $_element;

    public function __construct(
        Factory $elementFactory,
        Template\Context $context,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Render principal del fieldset con varias filas
     */
    public function render(AbstractElement $element)
    {
        $this->_element = $element;

        $html = '<fieldset class="dynamic fieldset admin__fieldset fieldset-wide fieldset-widget-options">'
              . '<legend>' . $element->getLabel() . '</legend>'
              . '<div class="content">';

        $values = explode(',', (string)$element->getValue());
        foreach ($values as $value) {
            if ($value === '') {
                continue;
            }
            $html .= $this->getInputRowHtml($element, $value);
        }

        $html .= '</div>'
              // Fila oculta para clonar
              . $this->getInputRowHtml($element, null, true)
              // Botón “Add Row”
              . $this->getAddRowButton($element)
              . '</fieldset>';

        return $html;
    }

    protected function getInputRowHtml(AbstractElement $element, $value = null, bool $clone = false): string
    {
        try {
            $input = $this->elementFactory->create('text')
                ->setForm($element->getForm())
                ->setName($element->getName() . '[]')
                ->setValue($value);

            return $this->getLayout()->createBlock(Template::class)
                ->setTemplate('Eniquin_WidgetSlider::text.phtml')
                ->setData('input_field', $input)
                ->setData('label_remove_row', $element->getData('label_remove_row'))
                ->setData('is_clone', $clone)
                ->toHtml();

        } catch (LocalizedException $exception) {
            return '';
        }
    }

    protected function getAddRowButton(AbstractElement $element): string
    {
        try {
            return $this->getLayout()->createBlock(Template::class)
                ->setTemplate('Eniquin_WidgetSlider::add.phtml')
                ->setData('label_add_row', $element->getData('label_add_row'))
                ->toHtml();
        } catch (LocalizedException $exception) {
            return '';
        }
    }
}
