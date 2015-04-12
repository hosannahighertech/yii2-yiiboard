<?php
namespace app\modules\yboard\components;

use yii\base\Widget;
use yii\helpers\Html;

class SimpleSearchForm extends Widget
{
    public $options = [];
    public $model = null;
    
    public function init()
    {
        parent::init();
        if(!isset($this->options['action']))
        {
            $this->options['action'] = 'search/index';
        }
    }

    public function run()
    { 
        $form = \yii\widgets\ActiveForm::begin([			
            'id'=>'simple-search-form', 
			'action'=> [$this->options['action']],
			'enableAjaxValidation'=>false,
]);  

        echo Html::textInput('search', null, ['class'=>'form-control', 'hint'=>YBoard::t('yboard', 'Search')]); 
        echo Html::hiddenInput('type', '0');  
        echo Html::hiddenInput('choice', '0');  
        Html::submitButton('Submit', ['class' => 'small-search-button'])  ;
        
        \yii\widgets\ActiveForm::end();  
    }
}
