<?php
//Scopes for YBoardForum et al
namespace app\modules\yboard\models; 

class YBoardPostQuery extends \yii\db\ActiveQuery
{
    public function approvedScope()
    {
        return $this->andWhere(['approved' => 1]);
    }  
    
    public function unapprovedScope()
    {
        return $this->andWhere(['approved' => 0]);
    } 
}
