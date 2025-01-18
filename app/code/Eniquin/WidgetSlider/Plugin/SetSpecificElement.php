<?php

declare(strict_types=1);

namespace Eniquin\WidgetSlider\Plugin;

use Magento\Widget\Model\Config\Converter;

/**
 * Class SetSpecificElement
 *
 * Ajusta el "type" del parámetro con base en los datos que vengan del helper_block
 */
class SetSpecificElement
{
    /**
     * Después de convertir la configuración de widget, modificamos el type
     */
    public function afterConvert(Converter $subject, array $result, $source): array
    {
        foreach ($result as &$widget) {
            if (!isset($widget['parameters'])) {
                continue;
            }
            foreach ($widget['parameters'] as &$parameter) {
                if (!isset($parameter['helper_block'])) {
                    continue;
                }
                if (isset($parameter['helper_block']['data']['element'])) {
                    $parameter['type'] = $parameter['helper_block']['data']['element'];
                }
            }
        }
        return $result;
    }
}
