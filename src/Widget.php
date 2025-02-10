<?php
/**
 * Виджет информационной панели (Dashboard).
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Dashboard\Shortcuts;

use Gm;

/**
 * Виджет информационной панели "Компоненты".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Dashboard\Shortcuts
 * @since 1.0
 */
class Widget extends \Gm\Panel\Widget\DashboardWidget
{
    /**
     * Показывать доступные модули.
     * 
     * @var bool
     */
    public bool $showModules = true;

    /**
     * Показывать доступные расширения модулей.
     * 
     * @var bool
     */
    public bool $showExtensions = true;

    /**
     * Показывать заголовок элементов списка.
     * 
     * @var bool
     */
    public bool $showTitle = true;

    /**
     * Показывать описание элементов списка.
     * 
     * @var bool
     */
    public bool $showDescription = true;

    /**
     * Компактый вид элементов списка.
     * 
     * @var bool
     */
    public bool $showSmall = true;

    /**
     * Идентификаторы модулей в виде пар "ключ - значение", которые необходимо 
     * отображать в списке.
     * 
     * @var array
     */
    public array $modules = [];

    /**
     * Идентификаторы расширений модулей в виде пар "ключ - значение", которые необходимо 
     * отображать в списке.
     * 
     * @var array
     */
    public array $extensions = [];

    /**
     * {@inheritdoc}
     */
    public bool $useToolRefresh = true;

    /**
     * {@inheritdoc}
     */
    public bool $useToolSettings = true;

    /**
     * {@inheritdoc}
     */
    public array $requires = ['Gm.view.shortcuts.Shortcuts'];

    /**
     * {@inheritdoc}
     */
    public array $css = ['/widget.css'];

    /**
     * {@inheritdoc}
     */
    protected string $id = 'gm.ds.shortcuts';

    /**
     * {@inheritdoc}
     */
    protected string $contentType = 'remoteStore';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this->title = ''; // убираем заголовок панели виджета
        $this->color = 'none'; // убираем цвет панели виджета
        $this->headerNoColor = true;
        $this->resizable = false;
        $this->autoHeight = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): mixed
    {
        return (new Model\Shortcuts(['widget' => $this]))->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): mixed
    {
        return [
            'xtype'      => 'g-shortcuts',
            'cls'        => 'g-shortcuts g-ds-shortcuts',
            'autoHeight' => true,
            'router'     => [
                'route' => Gm::getAlias('@match/widget/content/' . $this->rowId),
                'rules' => ['data' => '{route}']
            ],
            'tpl' => [
                '<tpl for=".">',
                    '<div class="g-shortcuts__item {disabled} {cls}" title="{tooltip}">',
                        '<div class="g-shortcuts__thumb"><img class="{iconCls}" src="{icon}" title="{title:htmlEncode}"><div class="g-shortcuts__thumb-title">{title:ellipsis(45)}</div><div class="g-shortcuts__thumb-description">{description:ellipsis(60)}</div></div>',
                    '</div>',
                '</tpl>',
                '<div class="x-clear"></div>'
            ]
        ];
    }
}