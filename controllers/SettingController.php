<?php

namespace app\modules\yboard\controllers; 

use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardSetting;
use app\modules\yboard\models\YBoardForum;
use app\modules\yboard\models\YBoardSpider;

use app\modules\yboard\models\YBoardSpiderSearch;
use app\modules\yboard\models\YBoardMembergroup;
use app\modules\yboard\models\YBoardRank;
use app\modules\yboard\models\YBoardVote;

use app\modules\yboard\models\YBoardMessage;
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardMemberSearch; 

use app\modules\yboard\YBoardSettingsAsset;
use hosanna\profile\models\User;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataProvider;
 
class SettingController extends \yii\web\Controller
{
    public $layout = "admin";
    
    public function init()
    {
        parent::init();
        YBoardSettingsAsset::register($this->view)->publish(\Yii::$app->assetManager); //register YBoard settings js 
    }
    
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
     * Lists all YBoardSetting models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->can('app.forum.setting.index'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $dataProvider = new ActiveDataProvider([
            'query' => YBoardSetting::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
     /**
     * Creates a new YBoardSetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('app.forum.setting.create'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = [];
        $model = new YBoardSetting;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $json['success'] = 'yes';
        } 
        else {
            $json['error'] = YBoard::t('yboard', 'Could not add new setting');
        }   
		echo json_encode($json);
		Yii::$app->end();
    }
    
    /**
     * Updates an existing YBoardSetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {        
        if(!Yii::$app->user->can('app.forum.setting.update'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = [];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $json['success'] = 'yes';
        } 
        else {
            $json['error'] = YBoard::t('yboard', 'Could not update setting');
        }   
		echo json_encode($json);
		Yii::$app->end();
    }
    
    public function actionForum()
    {
        if(!Yii::$app->user->can('app.forum.setting.forum'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model=new YBoardForum;
		$forum = [];
		$category = YBoardForum::find()->sortedScope()->categoryScope()->all();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if($model->load(\Yii::$app->request->post()) && $model->save())
            return $this->redirect(['forum']);	 
		
		return $this->render('forum', [
			'model'=>$model,
			'category'=>$category,
		]);
    }

    public function actionGroup()
    {
        if(!Yii::$app->user->can('app.forum.setting.group'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model=new YBoardMembergroup(['scenario'=>'search']);
		$model->setAttributes([]);  // clear any default values 
        $model->load(Yii::$app->request->post());

		return $this->render('group', ['model'=>$model]);
    } 
    
        
    /**
	 * handle Ajax call for sorting categories and forums
	 */
	public function actionAjaxSort() {
        if(!Yii::$app->user->can('app.forum.setting.ajax-sort'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		if(isset($_POST['cat'])) {
			$number = 1;
			foreach($_POST['cat'] as $id) {
				$model = YBoardForum::findOne($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes'=>'yes');
		} elseif(isset($_POST['frm'])) {
			$number = 1;
			foreach($_POST['frm'] as $id) {
				$model = YBoardForum::findOne($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes'=>'yes');
		} else { 
			$json = array('succes'=>'no');
		}
		echo json_encode($json);
		Yii::$app->end();
	}   
    
    public function actionDeleteUser($id)
    {
        if(!Yii::$app->user->can('app.forum.setting.delete-user'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model = YBoardMember::findOne($id);
        
        if($model!=null && $model->delete())        
            echo json_encode(['success'=>'yes']);
        else
            echo json_encode(['success'=>'no', 'error'=>YBoard::t('yboard', 'Could not delete User')]);
    }

    
	/**
	 * handle Ajax call for deleting membergroup
	 */
	public function actionDeleteMembergroup() {
        if(!Yii::$app->user->can('app.forum.setting.delete-membergroup'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = array();
		if(isset($_POST['id'])) {
			if($_POST['id'] == 1) {
				$json['success'] = 'no';
				$json['message'] = YBoard::t('yboard', 'The default member group cannot be removed.');
			} else {
				YBoardMembergroup::findOne($_POST['id'])->delete();
				$json['success'] = 'yes';
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
    
	/**
	 * handle Ajax call for deleting rank
	 */
	public function actionDeleteRank() {
        if(!Yii::$app->user->can('app.forum.setting.delete-rank'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['id'])) {			 
            if(YBoardRank::findOne($_POST['id'])->delete())
                $json['success'] = 'yes'; 
            else
				$json['success'] = 'no';
				$json['message'] = YBoard::t('yboard', 'Could not delete Rank');
		}
		echo json_encode($json);
		Yii::$app->end();
	}

    /**
	 * handle Ajax call for deleting spider
	 */
	public function actionDeleteSpider() {
        if(!Yii::$app->user->can('app.forum.setting.delete-spider'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['id'])) {
			if(YBoardSpider::findOne($_POST['id'])->delete())
                $json['success'] = 'yes';
            else
                $json['error'] = YBoard::t('yboard', 'Could Not delete Spider, try again');
		}
		echo json_encode($json);
		Yii::$app->end();
	}

    public function actionGetForum($id)
    {
        if(!Yii::$app->user->can('app.forum.setting.get-forum'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = [];
		if(isset($_GET['id'])) {
			$model = YBoardForum::findOne($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['sort'] = $model->sort;
				$json['subtitle'] = $model->subtitle;
				$json['cat_id'] = $model->cat_id;
				$json['type'] = $model->type;
				$json['locked'] = $model->locked;
				$json['public'] = $model->public;
				$json['moderated'] = $model->moderated;
				$json['membergroup_id'] = $model->membergroup_id;
				$json['poll'] = $model->poll;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
    }

    /**
      * handle Ajax call for saving forum
	 */
	public function actionSaveForum() {
        if(!Yii::$app->user->can('app.forum.setting.save-forum'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['YBoardForum'])) {
			$model = YBoardForum::findOne($_POST['YBoardForum']['id']);
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = array_values($model->errors);
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}

    public function actionGetMembergroup()
    {
        if(!Yii::$app->user->can('app.forum.setting.get-membergroup'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = [];
		if(isset($_GET['id'])) {
			$model = YBoardMembergroup::findOne($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['description'] = $model->description; 
				$json['color'] = $model->color;
				$json['group_role'] = $model->group_role;
				$json['image'] = $model->image;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
    }
    
    public function actionGetRank()
    {
        if(!Yii::$app->user->can('app.forum.setting.get-rank'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = [];
		if(isset($_GET['id'])) {
			$model = YBoardRank::findOne($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['title'] = $model->title; 
				$json['min_posts'] = $model->min_posts;
				$json['stars'] = $model->stars; 
			}
		}
		echo json_encode($json);
		Yii::$app->end();
    }

    /**
	 * handle Ajax call for getting spider
	 */
	public function actionGetSpider() {
        if(!Yii::$app->user->can('app.forum.setting.get-spider'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_GET['id'])) {
			$model = YBoardSpider::findOne($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['user_agent'] = $model->user_agent;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
     
    /**
     * Lists all YBoardRank models.
     * @return mixed
     */
    public function actionRank()
    {
        if(!Yii::$app->user->can('app.forum.setting.rank'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $dataProvider = new ActiveDataProvider([
            'query' => YBoardRank::find(),
        ]);

        return $this->render('rank', [
            'dataProvider' => $dataProvider,
        ]);
    }
 
    /**
	 * handle Ajax call for saving membergroup
	 */
	public function actionSaveMembergroup() {
        if(!Yii::$app->user->can('app.forum.setting.save-membergroup'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];        
        $model = new YBoardMembergroup;
        
		if(isset($_POST[$model->formName()])) {
 			if($_POST[$model->formName()]['id'] != '') { 
				$model = YBoardMembergroup::findOne($_POST[$model->formName()]['id']); 
                //remove from the POST
                unset($_POST[$model->formName()]['id']); 
			}  
            
            if($model->load(Yii::$app->request->post()) && $model->save()) {
                $json['success'] = 'yes';
            } else {
                $json['error'] =  YBoard::t('yboard', 'Could not save Member Group!');
            }  
		}
		echo json_encode($json);
		Yii::$app->end();
	} 
    
    /**
	 * handle Ajax call for saving ranks
	 */
	public function actionSaveRank() {
        if(!Yii::$app->user->can('app.forum.setting.save-rank'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];        
        $model = new YBoardRank;
        
		if(isset($_POST[$model->formName()])) {
 			if($_POST[$model->formName()]['id'] != '') { 
				$model = YBoardRank::findOne($_POST[$model->formName()]['id']); 
                //remove from the POST
                unset($_POST[$model->formName()]['id']); 
			}  
            
            if($model->load(Yii::$app->request->post()) && $model->save()) {
                $json['success'] = 'yes';
            } else {
                $json['error'] =  YBoard::t('yboard', 'Could not save Rank');
            }  
		}
		echo json_encode($json);
		Yii::$app->end();
	} 

    /*
     * handle Ajax call for saving spider
	 */
	public function actionSaveSpider() {
        if(!Yii::$app->user->can('app.forum.setting.save-spider'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];        
        $model = new YBoardSpiderSearch;
        
		if(isset($_POST[$model->formName()])) {
 			if($_POST[$model->formName()]['id'] != '') { 
				$model = YBoardSpiderSearch::findOne($_POST[$model->formName()]['id']); 
                //remove from the POST
                unset($_POST[$model->formName()]['id']); 
			}  
            
            if($model->load(Yii::$app->request->post()) && $model->save()) {
                $json['success'] = 'yes';
            } else {
                $json['error'] =  YBoard::t('yboard', 'Could not save Spider!');
            }  
		}
		echo json_encode($json);
		Yii::$app->end();
	}

    public function actionSpider() {
        if(!Yii::$app->user->can('app.forum.setting.spider'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $searchModel = new YBoardSpiderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('spider', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
    
    /**
     * Lists all YBoardMember models.
     * @return mixed
     */
    public function actionUsers()
    {   
        if(!Yii::$app->user->can('app.forum.setting.users'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $user = new User;
        
        $qstring = Yii::$app->request->get($user->formName())['username'];
        
        $user->username = $qstring;
        
        $userIds =  User::find()
            ->select('id')
            ->asArray() 
            ->filterWhere(['like','username',$qstring])
            ->andWhere('id<>'.Yii::$app->user->id)
            ->all(); 
            
        $query = YBoardMember::find();
         
        foreach($userIds as $id)
        {
            $query->orWhere(['id'=>$id['id']]); 
        }   
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]); 
        
        
        return $this->render('users', [
            'dataProvider' => $dataProvider,
            'searchModel' => $user,
        ]);
    }
    
    /**
     * Displays a single YBoardMember model for Editing and other bells.
     * @param string $id
     * @return mixed
     */
    public function actionEditProfile($id)
    {
        if(!Yii::$app->user->can('app.forum.setting.edit-profile'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model = YBoardMember::findOne($id);
        if ($model== null) { 
            throw new NotFoundHttpException('The requested User does not exist.');
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing YBoardSetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->can('app.forum.setting.delete'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the YBoardSetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return YBoardSetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = YBoardSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
