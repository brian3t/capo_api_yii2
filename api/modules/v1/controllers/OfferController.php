<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Offer;
use yii\data\ActiveDataProvider;
use app\models\Cuser;
use yii\base\Exception;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\db\ActiveRecord;
use Yii;

class OfferController extends BaseActiveController
{
    public $modelClass = 'app\models\Offer';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
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
                    'Access-Control-Request-Headers' => ['Content-Type']
                ],
            ],
        ], parent::behaviors());
    }

    public function actionIndex()
    {
        /* @var $modelClass Offer */
        $modelClass = $this->modelClass;

        $offers = $modelClass::find()->innerJoinWith('cuser')->where(\Yii::$app->request->queryParams)->addSelect('offer.cuser_id, offer.request_cuser, offer.created_at,
        offer.updated_at, offer.status,cuser.commuter_data, cuser.first_name')->asArray()->all();
        /** @var array $offers */
        foreach ($offers as &$offer) {
            $commuter_data = json_decode($offer['commuter_data'], true);
            $name = $offer['first_name'];
            if (isset($commuter_data['commuterName'])){
                $name = $commuter_data['commuterName'];
            }
            $phone = '';
            if (isset($commuter_data['hphone'])){
                $phone = $commuter_data['hphone'];
            }
            $offer['name'] = $name;
            $offer['phone'] = $phone;
            // $offer = ArrayHelper::getColumn($offer, ['cuser_id', 'created_at', 'updated_at', 'status']);
            $offer = array_intersect_key($offer, array_flip(['cuser_id', 'created_at', 'updated_at', 'status', 'name', 'phone']));
        }
        echo json_encode($offers);
        return;

    }

    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function actionUpdate($id){
        /* @var $model ActiveRecord */
        $model = Offer::find()->where(['cuser_id'=>$id])->one();
        //try creating if not exists
        if (is_null($model)){
            $model = new Offer();
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create new offer.');
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
