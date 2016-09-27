<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Cuser;
use app\models\Request;
use yii\base\Exception;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\db\ActiveRecord;
use Yii;

class RequestController extends BaseActiveController
{
    public $modelClass = 'app\models\Request';
    const MAX_COORDS_DIFF = 1;
    const MAX_ITEMS = 10;
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Methods' => ['GET', 'POST', 'OPTIONS', 'DELETE', 'PUT', 'PATCH'],
                    'Access-Control-Request-Headers' => ['Content-Type'],
                ],
            ],
        ], parent::behaviors());
    }
    
    public function actionCreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        header("Access-Control-Allow-Origin: *");
        
        $entityBody = file_get_contents('php://input');
        try {
            $entityBody = json_decode($entityBody);
            //if request exists, update its detail
            $request = Request::find()->where(array('cuser_id' => intval($entityBody->cuser_id)))->one();
            if (is_object($request) && isset($request->cuser_id)) {
                /** @var Request $request */
                $request->load(['Request' => (array)$entityBody]);
                if (!$request->save()) {
                    \Yii::error("Failed updating request" . json_encode($request) . "\nNew data: " . json_encode($entityBody));
                };
                
                echo json_encode($request->attributes);
                \Yii::$app->response->setStatusCode(200);
                return;
            }
            //else, try creating one
            $new_request = new Request();
            $new_request->load(['Request' => (array)$entityBody]);
            try {
                $new_request->save();
                echo json_encode($request->attributes);
                \Yii::$app->response->setStatusCode(201);
                return;
            } catch (Exception $e) {
                \Yii::error("Cant create new request " . json_encode($entityBody) . $e->getMessage());
                \Yii::$app->response->setStatusCode(400);
                return;
            }
        } catch (Exception $e) {
            \Yii::error("Bad input " . $e->getMessage());
            \Yii::$app->response->setStatusCode(400);
            return;
        }
    }
    
    public function actionIndex($cur_lat = null, $cur_lng = null)
    {
        if (is_null($cur_lat)) {
            $cur_lat = 38.900571;
        }
        if (is_null($cur_lng)) {
            $cur_lng = -77.008910;
        }
        
        
        $requests = Request::find()->where(['>=', 'pickup_lat', $cur_lat - self::MAX_COORDS_DIFF])
            ->andWhere(['<=', 'pickup_lat', $cur_lat + self::MAX_COORDS_DIFF])
            ->andWhere(['>=', 'pickup_lng', $cur_lng - self::MAX_COORDS_DIFF,])
            ->andWhere(['<=', 'pickup_lng', $cur_lng + self::MAX_COORDS_DIFF])->limit(self::MAX_ITEMS);
        return $requests->all();
    }
    
    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function actionUpdate($id)
    {
        /* @var $model ActiveRecord */
        $model = Request::find()->where(['cuser_id' => $id])->one();
        //try creating if not exists
        if (is_null($model)) {
            $model = new Request();
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create new request.');
            }
            return $model;
        }
        
        //updating
        if ($this->hasProperty('checkAccess') && $this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

//        $model->scenario = $this->scenario;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        
        return $model;
    }
    
}
