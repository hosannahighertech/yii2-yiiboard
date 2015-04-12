<?php

namespace app\modules\yboard\controllers;

use Yii;
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardMemberSearch; 
use app\modules\yboard\models\YBoardSession;
use app\modules\yboard\YBoard;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use hosanna\profile\models\User;
use yii\data\ActiveDataProvider;

/**
 * MemberController implements the CRUD actions for YBoardMember model.
 */
class MemberController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all YBoardMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->can('app.forum.member.index'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
       
        $user = new User;
        
        $qstring = Yii::$app->request->get($user->formName())['username'];
        
        $user->username = $qstring;
        
        $userIds =  User::find()
            ->select('id')
            ->asArray()
            ->filterWhere(['like','username',$qstring])->all(); 
            
        $query = YBoardMember::find();
         
        foreach($userIds as $id)
        {
            $query->orWhere(['id'=>$id['id']]); 
        }   
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        //if ($searchModel->load(Yii::$app->request->getQueryParams())) {
        //    $query->andFilterWhere(['like','id', $searchModel->id]);
        //}
        
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $user,
        ]);
    }

    /**
     * Displays a single YBoardMember model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!Yii::$app->user->can('app.forum.member.view'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Displays a single YBoardMember model for Editing and other bells.
     * @param string $id
     * @return mixed
     */
    public function actionProfile($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('app.forum.member.profile', ['profile'=>$model]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
       
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
    
    /**
     * Displays a single YBoardMember model allowing Limited.
     * @param string $id
     * @return mixed
     */
    public function actionUsercp($id)
    { 
        $model = $this->findModel($id); 
        
        if(!Yii::$app->user->can('app.forum.member.usercp', ['profile'=>$model]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if (isset($_POST['hasEditable'])) { 
            // read your posted model attributes
            if (!$model->load($_POST)  || !$model->save()) {
                // validation error
                echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'Could Not Update Field']);
            }
            // else if nothing to do always return an empty JSON encoded output
            else {
                $attrName = key($_POST[$model->formName()]);
                // read or convert your posted information
                $value = $model->{$attrName};
                echo \yii\helpers\Json::encode(['output'=>$value, 'message'=>'']);
            }
        }
        else
        {
            throw new ForbiddenHttpException(YBoard::t('yboard','This Action is forbidden!'));
        }
    }

    /**
     * Creates a new YBoardMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAssociate($id)
    {
        if(!Yii::$app->user->can('app.forum.member.associate'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model = YBoardMember::findOne($id);
        if($model==null)
            $model = new YBoardMember;
        $model->setAttribute('id',$id); 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing YBoardMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('app.forum.member.update', ['profile'=>$model]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing YBoardMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->can('app.forum.member.delete'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    //set user active    
	public function actionUpdateOnlineStatus($id, $uid=null) 
    {	
        $time = time() - $this->module->onlineLimit;
        // delete older session entries  
        YBoardSession::deleteAll("last_visit <".$time);
        
        $session = YBoardSession::findOne($id) ;
        
        //print_r($session); die();
        if($session==null)
        {
            $session = new YBoardSession;
            $session->setAttributes(['last_visit'=>time(), 'id'=>$id, 'user_id'=>$uid]);
        } 
        else
            $session->setAttribute('last_visit', time());
        
        $success = $session->save();
        
        $guests =  YBoardSession::find()->where('user_id IS NULL')->count();
        $members =  YBoardSession::find()->where('user_id IS NOT NULL')->count();

        //echo  json_encode(['success'=>$success, 'errors'=>$session->errors, 'users'=>['guests'=>$guests, 'members'=>$members]]);
        echo  json_encode(['time'=>$time, 'success'=>$success, 'errors'=>$session->errors, 'message'=>YBoard::t('yboard','{{guests}} guest(s) and {{members}} active member(s)', ['{guests}'=>($guests), '{members}'=>$members])]);
    }

    /**
     * Finds the YBoardMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return YBoardMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = YBoardMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
