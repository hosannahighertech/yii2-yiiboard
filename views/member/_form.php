<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\yboard\YBoard;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\modelsYBoardMember $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="pad5 yboard-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'personal_text')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'show_online')->dropDownList([0=>YBoard::t('yboard', 'No'), 1=>YBoard::t('yboard', 'Yes')]) ?>
    
    <?= $form->field($model, 'contact_pm')->dropDownList([0=>YBoard::t('yboard', 'No'), 1=>YBoard::t('yboard', 'Yes')]) ?>
    
    <!--?= $form->field($model, 'warning')->textInput() ?-->

    <!--?= $form->field($model, 'posts')->textInput(['maxlength' => 10]) ?-->

    <!--?= $form->field($model, 'group_id')->textInput() ?-->

    <!--?= $form->field($model, 'upvoted')->textInput() ?-->

    <!--?= $form->field($model, 'moderator')->textInput() ?-->

    <!--?= $form->field($model, 'first_visit')->textInput() ?-->

    <!--?= $form->field($model, 'last_visit')->textInput() ?-->

    <?= $form->field($model, 'signature')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('website').' '.Html::img($this->context->module->getSocialImage('globe.png'), ['alt'=>'website', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('facebook').' '.Html::img($this->context->module->getSocialImage('facebook.png'), ['alt'=>'facebook', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'twitter')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('twitter').' '.Html::img($this->context->module->getSocialImage('twitter.png'), ['alt'=>'twitter', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'google')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('google').' '.Html::img($this->context->module->getSocialImage('google.png'), ['alt'=>'google', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'linkedin')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('linkedin').' '.Html::img($this->context->module->getSocialImage('linkedin.png'), ['alt'=>'linkedin', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'blogger')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('blogger').' '.Html::img($this->context->module->getSocialImage('blogger.png'), ['alt'=>'blogger', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'youtube')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('youtube').' '.Html::img($this->context->module->getSocialImage('youtube.png'), ['alt'=>'youtube', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'yahoo')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('yahoo').' '.Html::img($this->context->module->getSocialImage('yahoo.png'), ['alt'=>'yahoo', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'skype')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('skype').' '.Html::img($this->context->module->getSocialImage('skype.png'), ['alt'=>'skype', 'style'=>'vertical-align:middle'])) ?>

    <?= $form->field($model, 'metacafe')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('metacafe').' '.Html::img($this->context->module->getSocialImage('metacafe.png'), ['alt'=>'Metacafe', 'style'=>'vertical-align:middle'])) ?>
   
   <?= $form->field($model, 'github')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('github').' '.Html::img($this->context->module->getSocialImage('github.png'), ['alt'=>'github', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'orkut')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('orkut').' '.Html::img($this->context->module->getSocialImage('orkut.png'), ['alt'=>'orkut', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'wordpress')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('wordpress').' '.Html::img($this->context->module->getSocialImage('wordpress.png'), ['alt'=>'wordpress', 'style'=>'vertical-align:middle'])) ?>

   <?= $form->field($model, 'tumblr')->textInput(['maxlength' => 255])->label($model->getAttributeLabel('tumblr').' '.Html::img($this->context->module->getSocialImage('tumblr.png'), ['alt'=>'tumblr', 'style'=>'vertical-align:middle'])) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? YBoard::t('yboard', 'Add Profile') : YBoard::t('yboard', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
