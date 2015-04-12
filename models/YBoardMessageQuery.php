<?php
//Scopes for YBoardForum et al
namespace app\modules\yboard\models; 

class YBoardMessageQuery extends \yii\db\ActiveQuery
{
    public function inboxScope()
    {
        return $this->andWhere(['inbox' => 1]);
    }   
    
    public function outboxScope()
    {
        return $this->andWhere(['outbox' => 1]);
    }
    
    public function unreadScope()
    {
        return $this->andWhere(['read_indicator' => 0]);
    }
    
    public function reportScope()
    {
        return $this->andWhere(['sendto' => 0]);
    } 
      
    public function reportMsgScope()
    {
        return $this->andWhere(['sendto' => 0]);
    }   
}
