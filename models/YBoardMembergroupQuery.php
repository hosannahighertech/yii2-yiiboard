<?php
//Scopes for YBoardMembergroup et al
namespace app\modules\yboard\models; 

class YBoardMembergroupQuery extends \yii\db\ActiveQuery
{
    public function specificScope()
    {
        return $this->andWhere('id > 0');
    } 
}
