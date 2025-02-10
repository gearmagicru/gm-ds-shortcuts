<?php
/**
 * Этот файл является частью виджета информационной панели (Dashboard).
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Dashboard\Shortcuts\Options;

use Gm;
use Gm\Helper\Str;
use Gm\Helper\Html;
use Gm\Panel\Widget\OptionsWindow;

/**
 * Интерфейс окна настроек параметров виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Dashboard\Shortcuts\Options
 * @since 1.0
 */
class Options extends OptionsWindow
{
    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $this->cls = 'g-window_settings g-ds-shortcuts-options';
        $this->width = 760;
        $this->height = '95%';
        $this->resizable = false;
        $this->form->autoScroll = true;
        $this->form->items = [
            [
                'xtype'  => 'fieldset',
                'title'  => '#List items',
                'layout' => 'column',
                'items'  => [
                    [
                        'columnWidth' => 0.5,
                        'padding'     => 2,
                        'defaults'    => [
                            'xtype'      => 'checkbox',
                            'ui'         => 'switch',
                            'labelWidth' => 160,
                            'labelAlign' => 'right'
                        ],
                        'items'       => [
                            [
                                'name'       => 'showModules',
                                'checked'    => $this->options['showModules'] ?? true,
                                'fieldLabel' => '#Modules'
                            ],
                            [
                                'name'       => 'showExtensions',
                                'checked'    => $this->options['showExtensions'] ?? true,
                                'fieldLabel' => '#Extensions'
                            ]
                        ]
                    ],
                    [
                        'columnWidth' => 0.5,
                        'padding'     => 2,
                        'defaults'    => [
                            'xtype'      => 'checkbox',
                            'ui'         => 'switch',
                            'labelWidth' => 160,
                            'labelAlign' => 'right'
                        ],
                        'items'       => [
                            [
                                'name'       => 'showTitle',
                                'checked'    => $this->options['showTitle'] ?? true,
                                'fieldLabel' => '#Show title'
                            ],
                            [
                                'name'       => 'showDescription',
                                'checked'    => $this->options['showDescription'] ?? true,
                                'fieldLabel' => '#Show description'
                            ],
                            [
                                'name'       => 'showSmall',
                                'checked'    => $this->options['showSmall'] ?? false,
                                'fieldLabel' => '#Compact list view'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'layout' => 'column',
                'style'  => 'margin-bottom:20px',
                'items'  => [
                    [
                        'layout'      => 'anchor',
                        'columnWidth' => 0.5,
                        'padding'     => 2,
                        'title'       => '#Modules displayed in the list',
                        'defaults'    => [
                            'xtype'      => 'checkbox',
                            'ui'         => 'switch',
                            'labelWidth' => 160,
                            'labelAlign' => 'right'
                        ],
                        'items' => $this->getModulesItems()
                    ],
                    [
                        'layout'      => 'anchor',
                        'columnWidth' => 0.5,
                        'padding'     => 2,
                        'title'       => '#Extensions displayed in the list',
                        'defaults'    => [
                            'xtype'      => 'checkbox',
                            'ui'         => 'switch',
                            'labelWidth' => 160,
                            'labelAlign' => 'right'
                        ],
                        'items' => $this->getExtensionItems()
                    ]
                ]
            ]
        ];
    }

    /**
     * Возвращает список доступных для выбора модулей.
     * 
     * @return array
     */
    protected function getModulesItems(): array
    {
        $items = [];
        // выбранные модуля
        $checked = $this->options['modules'] ?? [];

        /** @var array Параметры установленных модулей */
        $modules = Gm::$app->modules->getRegistry()->getListInfo(true, false);
        foreach ($modules as $rowId => $module) {
            if ($module['use'] === FRONTEND) continue;
            $items[] = [
                'xtype'    => 'checkbox',
                'ui'       => 'switch',
                'boxLabel' => 
                    Html::tag('img', '', ['src' => $module['smallIcon'], 'align' => 'absmiddle']) . ' ' . 
                    Html::tag('span', Str::ellipsis($module['name'], 0, 35)),
                'tooltip'  => $module['description'],
                'autoEl'   => ['tag' => 'div', 'data-qtip' => $module['description']],
                'name'     => 'modules[' . $rowId . ']',
                'checked'  => isset($checked[$rowId])
            ];
        }
        return $items;
    }

    /**
     * Возвращает список доступных для выбора расширений.
     * 
     * @return array
     */
    protected function getExtensionItems(): array
    {
        $items = [];
        // выбранные расширения модулей
        $checked = $this->options['extensions'] ?? [];

        /** @var array Параметры установленных расширений модулей */
        $extensions = Gm::$app->extensions->getRegistry()->getListInfo(true, false);
        foreach ($extensions as $rowId => $extension) {
            if ($extension['use'] === FRONTEND) continue;
            $items[] = [
                'xtype'    => 'checkbox',
                'ui'       => 'switch',
                'boxLabel' => 
                    Html::tag('img', '', ['src' => $extension['smallIcon'], 'align' => 'absmiddle']) . ' ' . 
                    Html::tag('span', Str::ellipsis($extension['name'], 0, 35)),
                'tooltip'  => $extension['description'],
                'autoEl'   => ['tag' => 'div', 'data-qtip' => $extension['description']],
                'name'     => 'extensions[' . $rowId . ']',
                'checked'  => isset($checked[$rowId])
            ];
        }
        return $items;
    }
}