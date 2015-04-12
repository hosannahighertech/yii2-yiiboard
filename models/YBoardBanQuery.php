<?php
//Scopes for YBoardForum et al
namespace app\modules\yboard\models; 

class YBoardBanQuery extends \yii\db\ActiveQuery
{
    public function liftedScope()
    {
        $this->andWhere('expires<='.time()) ;
        return $this;
    }  
    
    public function activeScope()
    {
        $this->andWhere('expires>'.time()) ; 
        return $this;
    }  
    
    public function userScope()
    {
        $this->andWhere("user_id IS NOT NULL"); 
        return $this;
    }  
    
    public function emailScope()
    {
        $this->andWhere("email IS NOT NULL"); 
        return $this;
    }  
    
    public function ipScope()
    {
        $this->andWhere("ip IS NOT NULL"); 
        return $this;
    }  
}
