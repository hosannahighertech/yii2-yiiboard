<?php
//Scopes for YBoardMember et al
namespace app\modules\yboard\models; 

class YBoardMemberQuery extends \yii\db\ActiveQuery
{
    public function approvedScope()
    {
        return $this->andWhere(['approved' => 1]);
    }  
    
    public function showScope()
    {
        return $this->andWhere(['show_online' => 1]);
    }
    
    public function hiddenScope()
    {
        return $this->andWhere(['show_online' => 0]);
    } 
    
    public function newestScope()
    {
        return $this->orderBy('id DESC')->limit(1);
    }
    
    public function presentScope()
    {        
        $ids = YBoardSession::find()
            ->asArray()
            ->where('user_id IS NOT NULL')
            ->orderBy('last_visit DESC')
            ->all();
            
        $uids= [];  
           
        foreach($ids as $id)
        {
            $uids[] = $id['user_id'];
        }
        
        return $this->andWhere(['id'=>$uids]);
    } 
}   
			 
