<?php
//Scopes for YBoardForum et al
namespace app\modules\yboard\models; 

class YBoardForumQuery extends \yii\db\ActiveQuery
{
    public function categoriesScope()
    {
        $this->andWhere(['type' => 0]) 
                    ->orderBy('sort');
        return $this;
    }
    
    public function categoryScope()
    {
        $this->andWhere(['type' => 0]) ;
        return $this;
    }
       
    public function forumScope()
    {
        return $this->andWhere(['type' => 1]) ;
    }
       
    public function publicScope()
    {
        $this->andWhere(['public' => 1]) ;
        return $this;
    } 
       
    public function sortedScope()
    {
        $this->orderBy('sort') ;
        return $this;
    } 
    
    public function memberGroupScope($member=0)
    {
        //-1 mean admin so he should see all groups.
        if($member!=-1)
        {
            $usr = YBoardMember::findOne(\Yii::$app->user->id);
            $this->andWhere(['membergroup_id' => 0]);
            if($usr!==null)
                $this->orWhere(['membergroup_id' => $usr->group_id]);
        }
        return $this;
    } 
}
