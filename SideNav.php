<?php
namespace octoweb\widgets;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class SideNav extends \kartik\sidenav\SideNav{
    
    public $iconPrefix='';
    
    public function init(){
        parent::init();
        /*SideNavAsset::register($this->getView());
        $this->activateParents = true;
        $this->submenuTemplate = "\n<ul class='nav nav-pills nav-stacked'>\n{items}\n</ul>\n";*/
        $this->linkTemplate = '<a href="{url}"{linkOptions}>{icon}{label}</a>';
        /*$this->labelTemplate = '{icon}{label}';
        $this->markTopItems();
        Html::addCssClass($this->options, 'nav nav-pills nav-stacked kv-sidenav');*/
    }
    
    protected function renderItem($item){
        $this->validateItems($item);
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
        $url = Url::to(ArrayHelper::getValue($item, 'url', '#'));
        $linkOptions_ar=ArrayHelper::getValue($item, 'linkOptions');
        $linkOptions='';
        
        if(!empty($linkOptions_ar)){
            foreach($linkOptions_ar as $key=>$value){
                $linkOptions.=' '.$key.'="'.$value.'"';
            }
        }
        if (empty($item['top'])) {
            if (empty($item['items'])) {
                $template = str_replace('{icon}', $this->indItem . '{icon}', $template);
            } else {
                $template = isset($item['template']) ? $item['template'] :'<a href="{url}" class="kv-toggle">{icon}{label}</a>';
                $openOptions = ($item['active']) ? ['class' => 'opened'] : ['class' => 'opened', 'style' => 'display:none'];
                $closeOptions = ($item['active']) ? ['class' => 'closed', 'style' => 'display:none'] : ['class' => 'closed'];
                $indicator = Html::tag('span', $this->indMenuOpen, $openOptions) . Html::tag('span', $this->indMenuClose, $closeOptions);
                $template = str_replace('{icon}', $indicator . '{icon}', $template);
            }
        }
        $icon = empty($item['icon']) ? '' : '<span class="' . $this->iconPrefix . $item['icon'] . '"></span> &nbsp;';
        unset($item['icon'], $item['top']);
        return strtr($template, [
            '{url}' => $url,
            '{label}' => $item['label'],
            '{icon}' => $icon,
            '{linkOptions}'=>$linkOptions
        ]);
    }
    
    
    
}