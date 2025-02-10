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
use Gm\Stdlib\BaseObject;

/**
 * Модель данных элементов (компонентов) списка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Shortcuts\Model
 * @since 1.0
 */
class Shortcuts extends BaseObject
{
    /**
     * Виджет.
     * 
     * @var null|\Gm\Panel\Widget\DashboardWidget
     */
    public $widget;

    /**
     * Возвращает параметры конфигурации установленных модулей доступных для текущей 
     * роли пользователя.
     * 
     * @param string|array|null $modulesId Идентификаторы установленных модулей в 
     *     базе данных.
     *     Идентификаторы модулей имеют вид:
     *     - `null`, идентификаторы доступные для текущей роли пользователя;
     *     - `string`, пример: `'1,2,3, ...'`;
     *     - `array`, пример: `[1, 2, 3, ...]`.
     * 
     * @return array
     */
    public function getModules(string|array|null $moduleIds = null): array
    {
        /** @var array $items возвращаемые параметры конфигурации установленных модулей */
        $modules = [];
        if ($moduleIds === null) {
            // конфигурации установленных модулей доступных для текущей роли пользователя
            $modulesInfo = Gm::$app->modules->getRegistry()->getListInfo(true, true);
        } else {
            if (is_string($moduleIds)) {
                // преобразование идентификаторов к виду: `[1 => true, 2 => true, ...]`
                $moduleIds = array_fill_keys(explode(',', $moduleIds), true);
            } else
            if (is_array($moduleIds)) {
                // преобразование идентификаторов к виду: `[1 => true, 2 => true, ...]`
                $moduleIds = array_fill_keys($moduleIds, true);
            } else
                return $modules;
            // конфигурации установленных модулей
            $modulesInfo = Gm::$app->modules->getRegistry()->getListInfo(true, false);
        }
        foreach ($modulesInfo as $rowId => $moduleInfo) {
            if (!$moduleInfo['enabled'] || !$moduleInfo['visible'] || ($moduleInfo['use'] === FRONTEND)) continue;
            // выбрать только модули с указанными идентификаторами
            if ($moduleIds) {
                if (!isset($moduleIds[$rowId])) continue;
            }
            $modules[] = $moduleInfo;
        }
        return $modules;
    }

    /**
     * Возвращает элементы списка.
     * 
     * @return array
     */
    public function getItems(): array
    {
        // показывать заголовок элемента
        $showTitle = $this->widget->showTitle;
        // показывать описание элемента
        $showDesc = $this->widget->showDescription;
        /** @var array $items Элементы списка */
        $items = [];

        if ($this->widget->showModules) {
            $cls = 'g-shortcuts__item_mod';
            // если сокращенный вид элемента
            if (!$showTitle && !$showDesc) {
                $cls .= ' g-shortcuts__item_short';
            }
            // если компактный вид элемента
            if ($this->widget->showSmall) {
                $cls .= ' g-shortcuts__item_small';
            }
            // только те модули, которые необходимо отображать
            $available = $this->widget->modules;
            /** @var array Параметры установленных модулей для текущей роли пользователя */
            $modules = Gm::$app->modules->getRegistry()->getListInfo(true, true);
            foreach ($modules as $rowId => $module) {
                if (isset($available[$rowId])) {
                    $items[] = [
                        'title'       => $showTitle ? $module['name'] : '',
                        'description' => $showDesc ? $module['description'] : '',
                        'tooltip'     => $module['description'],
                        'disabled'    => !$module['enabled'] ? 'g-shortcuts__item_disabled' : '',
                        'widgetUrl'   => Gm::alias('@backend', '/' . $module['route']),
                        'icon'        => $module['icon'],
                        'cls'         => $cls
                    ];
                }
            }
        }

        if ($this->widget->showExtensions) {
            $cls = 'g-shortcuts__item_ext';
            // если сокращенный вид элемента
            if (!$showTitle && !$showDesc) {
                $cls .= ' g-shortcuts__item_short';
            }
            // если компактный вид элемента
            if ($this->widget->showSmall) {
                $cls .= ' g-shortcuts__item_small';
            }
            // только те расширения, которые необходимо отображать
            $available = $this->widget->extensions;
            /** @var array Параметры установленных расширений модулей для текущей роли пользователя */
            $extensions = Gm::$app->extensions->getRegistry()->getListInfo(true, true);
            foreach ($extensions as $rowId => $extension) {
                if (isset($available[$rowId])) {
                    $items[] = [
                        'title'       => $showTitle ? $extension['name'] : '',
                        'description' => $showDesc ? $extension['description'] : '',
                        'tooltip'     => $extension['description'],
                        'disabled'    => !$extension['enabled'] ? 'g-shortcuts__item_disabled' : '',
                        'widgetUrl'   => Gm::alias('@backend', '/' . $extension['baseRoute']),
                        'icon'        => $extension['icon'],
                        'cls'         => $cls
                    ];
                }
            }
        }
        return $items;
    }
}
