<?php

namespace Eniquin\WidgetSlider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

/**
 * Bloque principal del Slider.
 */
class SlideWidget extends Template implements BlockInterface
{
    protected $_template = 'slide-widget.phtml';

    /**
     * UID para identificar cada slider en el frontend.
     */
    protected $widgetUid;

    protected function _beforeToHtml()
    {
        $this->widgetUid = uniqid('eniquin_slider_');
        return parent::_beforeToHtml();
    }

    /**
     * Retorna el ID único de este slider
     */
    public function getWidgetUid(): string
    {
        return $this->widgetUid;
    }

    /**
     * Retorna un array con los slides (imagen desktop + móvil + 3 botones).
     */
    public function getSlides(): array
    {
        $rawSlides = (string) $this->getData('slides');
        if (!$rawSlides) {
            return [];
        }

        $slideRows = array_filter(
            explode(',', $rawSlides),
            fn($slideRow) => trim($slideRow) !== ''
        );

        $slides = [];
        foreach ($slideRows as $slideRow) {
            // desktop|mobile|btnText1|btnUrl1|btnText2|btnUrl2|btnText3|btnUrl3
            $parts = explode('|', $slideRow);

            $slides[] = [
                'desktop' => $parts[0] ?? '',
                'mobile'  => $parts[1] ?? '',
                'buttons' => [
                    ['text' => $parts[2] ?? '', 'url' => $parts[3] ?? ''],
                    ['text' => $parts[4] ?? '', 'url' => $parts[5] ?? ''],
                    ['text' => $parts[6] ?? '', 'url' => $parts[7] ?? ''],
                ]
            ];
        }
        return $slides;
    }

    /**
     * Dirección del slider: "horizontal" o "vertical"
     */
    public function getDirection(): string
    {
        return $this->getData('slider_direction') ?: 'horizontal';
    }

    /**
     * Tipo de paginación: "bullets", "fraction", "progressbar"
     */
    public function getPaginationType(): string
    {
        return $this->getData('pagination_type') ?: 'bullets';
    }

    /**
     * Efecto de transición: "slide", "fade", "cube", "coverflow", "flip"
     */
    public function getSliderEffect(): string
    {
        return $this->getData('slider_effect') ?: 'slide';
    }

    /**
     * Número de slides visibles
     */
    public function getSlidesPerView(): int
    {
        return (int) ($this->getData('slides_per_view') ?: 1);
    }

    /**
     * Velocidad de transición (ms)
     */
    public function getSliderSpeed(): int
    {
        return (int) ($this->getData('slider_speed') ?: 4000);
    }

    /**
     * ¿Mostrar flechas?
     */
    public function showArrows(): bool
    {
        return (bool) $this->getData('show_arrows');
    }

    /**
     * ¿Mostrar paginación?
     */
    public function showPagination(): bool
    {
        return (bool) $this->getData('show_pagination');
    }
}
