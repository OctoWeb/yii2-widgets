<?php
namespace octoweb\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class NavX extends \kartik\nav\NavX{
    
    public $dropdownClass = '\octoweb\widgets\DropdownX';

    public function renderItem($item){
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $icon=ArrayHelper::getValue($item, 'icon',false);
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }
        
        if($icon)
            $label='<span class="'.$icon.'"></span> '.$label;
            
        if ($items !== null) {
            $linkOptions['data-toggle'] = 'dropdown';
            Html::addCssClass($options, 'dropdown');
            Html::addCssClass($linkOptions, 'dropdown-toggle');
            $label .= $this->dropdownIndicator;
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                $dropdown = $this->dropdownClass;
                $dropdownOptions = ArrayHelper::merge($this->dropdownOptions, [
                    'items' => $items,
                    'encodeLabels' => $this->encodeLabels,
                    'clientOptions' => false,
                    'view' => $this->getView(),
                ]);
                $items = $dropdown::widget($dropdownOptions);
            }
        }
        
        
        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    protected function isChildActive($items, &$active){
        foreach ($items as $i => $child) {
            if (ArrayHelper::remove($items[$i], 'active', false) || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'active');
                if ($this->activateParents) {
                    $active = true;
                }
            }
            if (isset($items[$i]['items']) && is_array($items[$i]['items'])) {
                $childActive = false;
                $items[$i]['items'] = $this->isChildActive($items[$i]['items'], $childActive);
                if ($childActive) {
                    Html::addCssClass($items[$i]['options'], 'active');
                    $active = true;
                }
            }
        }
        return $items;
    }
}
