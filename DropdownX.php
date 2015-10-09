<?php
namespace octoweb/widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class DropdownX extends \kartik\dropdown\DropdownX{
    
    protected function renderItems($items, $options = []){
        $lines = [];
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (is_string($item)) {
                $lines[] = $item;
                continue;
            }
            if (!isset($item['label'])) {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $icon=ArrayHelper::getValue($item, 'icon',false);
            $itemOptions = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $linkOptions['tabindex'] = '-1';
            $url = array_key_exists('url', $item) ? $item['url'] : null;
            
            if($icon)
                $label='<span class="'.$icon.'"></span> '.$label;
            
            if (empty($item['items'])) {
                if ($url === null) {
                    $content = $label;
                    Html::addCssClass($itemOptions, 'dropdown-header');
                } else {
                    $content = Html::a($label, $url, $linkOptions);
                }
            } else {
                Html::addCssClass($linkOptions, 'dropdown-toggle');
                $linkOptions['data-toggle'] = 'dropdown';
                $submenuOptions = $options;
                unset($submenuOptions['id']);
                $content = Html::a($label, $url === null ? '#' : $url, $linkOptions)
                    . $this->renderItems($item['items'], $submenuOptions);
                Html::addCssClass($itemOptions, 'dropdown dropdown-submenu');
            }

            $lines[] = Html::tag('li', $content, $itemOptions);
        }

        return Html::tag('ul', implode("\n", $lines), $options);
    }
}
