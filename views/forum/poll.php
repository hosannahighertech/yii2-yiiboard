<?php
/* @var $this ForumController */
/* @var $choiceProvider ActiveDataProvider */

use yii\widgets\ListView;
?>

 <?= ListView::widget([ 
    'id'=>'yboardPoll',
    'itemView'=>'_pollResult', 
    'dataProvider'=>$choiceProvider, 
    'summary'=>false,
]);  

