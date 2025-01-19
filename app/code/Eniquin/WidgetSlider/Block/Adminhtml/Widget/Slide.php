<?php

declare(strict_types=1);

namespace Eniquin\WidgetSlider\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Exception\LocalizedException;

class Slide extends Template implements RendererInterface
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

    public function render(AbstractElement $element)
    {
        $this->_element = $element;

        $html = '<fieldset class="dynamic fieldset admin__fieldset fieldset-wide fieldset-widget-options">'
              . '<legend>' . $element->getLabel() . '</legend>'
              . '<div class="content">';

        // parsear el valor en la DB
        $values = explode(',', (string)$element->getValue());
        foreach ($values as $value) {
            if (trim($value) === '') {
                continue;
            }
            $html .= $this->getSlideRowHtml($element, $value);
        }

        $html .= '</div>';

        // Fila oculta para clonar
        $html .= $this->getSlideRowHtml($element, null, true);

        // Botón "Add Slide"
        $html .= $this->getAddRowButton($element);

        $html .= '</fieldset>';
        return $html;
    }

    /**
     * Genera el HTML para cada "fila" de slide
     */
    protected function getSlideRowHtml(AbstractElement $element, $value = null, bool $clone = false): string
    {
        // 8 campos: desktopImage|mobileImage|btnText1|btnUrl1|btnText2|btnUrl2|btnText3|btnUrl3
        $parts = $value ? explode('|', $value) : [];

        return $this->getLayout()->createBlock(Template::class)
            ->setTemplate('Eniquin_WidgetSlider::slide.phtml')
            ->setData('desktopImageVal', $parts[0] ?? '')
            ->setData('mobileImageVal',  $parts[1] ?? '')
            ->setData('btnText1Val',    $parts[2] ?? '')
            ->setData('btnUrl1Val',     $parts[3] ?? '')
            ->setData('btnText2Val',    $parts[4] ?? '')
            ->setData('btnUrl2Val',     $parts[5] ?? '')
            ->setData('btnText3Val',    $parts[6] ?? '')
            ->setData('btnUrl3Val',     $parts[7] ?? '')
            ->setData('label_remove_row', $element->getData('label_remove_row'))
            ->setData('is_clone', $clone)
            ->toHtml();
    }

    protected function getAddRowButton(AbstractElement $element)
    {
        return $this->getLayout()->createBlock(Template::class)
            ->setTemplate('Eniquin_WidgetSlider::add_slide.phtml')
            ->setData('label_add_row', $element->getData('label_add_row'))
            ->toHtml();
    }
}
