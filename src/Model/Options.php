<?php
/**
 * Этот файл является частью виджета информационной панели (Dashboard).
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Dashboard\Shortcuts\Model;

use Gm;
use Gm\Panel\Data\Model\WidgetOptionsModel;

/**
 * Модель настроек параметров виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Dashboard\Version\Model
 * @since 1.0
 */
class Options extends WidgetOptionsModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'showModules'     => 'showModules', // показывать модули
            'showExtensions'  => 'showExtensions', // показывать расширения
            'showTitle'       => 'showTitle', // показывать заголовок
            'showDescription' => 'showDescription', // показывать описание
            'showSmall'       => 'showSmall', // компактный вид списка
            'modules'         => 'modules', // идентификаторы модулей
            'extensions'      => 'extensions', // идентификаторы расширений
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formatterRules(): array
    {
        return [
            [
                ['showModules', 'showExtensions', 'showTitle', 'showDescription', 'showSmall'], 'logic' => [true, false]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            // конвертируем из `[1 => 'on', ...]` в `[1 => true, ...]`
            if ($this->modules)
                $this->modules = array_fill_keys(array_keys($this->modules), true);
            else
                $this->modules = [];
            // конвертируем из `[1 => 'on', ...]` в `[1 => true, ...]`
            if ($this->extensions)
                $this->extensions = array_fill_keys(array_keys($this->extensions), true);
            else
                $this->extensions = [];
        }
        return $isValid;
    }
}