<?php
//Scopes for YBoardMember et al
namespace app\modules\yboard\models; 

class YBoardSpiderQuery extends \yii\db\ActiveQuery
{
    public function presentScope()
    {        
		$recent = date('Y-m-d H:i:s', time() - 900);
        return $this->andWhere("last_visit > '$recent'")
        ->orderBy('last_visit DESC');
    } 
}
