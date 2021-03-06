<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\helpers\Pusher;
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
        unset($actions['create']);
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
            if (isset($commuter_data['commuterName'])) {
                $name = $commuter_data['commuterName'];
            }
            $phone = '';
            if (isset($commuter_data['hphone'])) {
                $phone = $commuter_data['hphone'];
            }
            $offer['name'] = $name;
            $offer['phone'] = $phone;
            // $offer = ArrayHelper::getColumn($offer, ['cuser_id', 'created_at', 'updated_at', 'status']);
            $offer = array_intersect_key($offer,
                array_flip(['cuser_id', 'created_at', 'updated_at', 'status', 'name', 'phone']));
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
    public function actionUpdate($id)
    {
        /* @var $model ActiveRecord */
        $model = Offer::find()->where(['cuser_id' => $id])->one();
        //try creating if not exists
        if (is_null($model)) {
            $model = new Offer();
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if ($model->save() === false) {
                throw new ServerErrorHttpException('Failed to create new offer.');
                Yii::error("Failed to create new offer. Params:" . json_encode(Yii::$app->getRequest()->getBodyParams()));
                if ($model->hasErrors()){
                    Yii::error("Error: " . json_encode($model->attributes));
                }
            }

            // START mhemry
            $rider = $model->requestCuser;
            if (is_object($rider) && property_exists($rider, 'apns_device_reg_id') && $rider->apns_device_reg_id !== null) {
                $pusher = new Pusher();
                $pusher->actionPushOfferFound($rider, $model);
            }
            // END mhemry
            
            return $model;
        }
        
        //updating
        if ($this->hasProperty('checkAccess') && $this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

//        $model->scenario = $this->scenario;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false) {
            Yii::error("Failed to create new offer. Params:" . json_encode(Yii::$app->getRequest()->getBodyParams()));
            if ($model->hasErrors()){
                Yii::error("Error: " . json_encode($model->attributes));
            }
            throw new ServerErrorHttpException('Failed to create new offer.');
        }
        
        return $model;
    }
    
    /**
     * Updates an existing model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function actionCreate()
    {
        /* @var $model Offer */
        $model = new Offer();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = $model->id;
            Yii::error("Offer saved. Data: ". json_encode($model->attributes));
        } elseif (!$model->hasErrors()) {
            Yii::error("Error saving offer: ". json_encode($model->errors));
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        } else {
            Yii::error("Error saving offer: ". json_encode($model->errors));
        }
        
        //notifies rider
        $rider = $model->requestCuser;
        /* @var $rider Cuser */
        if ($rider->apns_device_reg_id !== null) {
            $pusher = new Pusher();
            $pusher->actionPushOfferFound($rider, $model);
        }
        return $model;
    }
    
    /**
     * Sends a test notification to Ross Edgar.
     * Note: must have a request and an offer for Ross already
     */
    public function actionTestpush()
    {
        $pusher = new Pusher();
        //Mediabeef
        $offer = Offer::find()->where(['request_cuser' => '57c3a3235ac4a57c3a3235ac4f'])->one();
        $rider = Cuser::find()->where(['id' => '57c3a3235ac4a57c3a3235ac4f'])->one();
        if ($offer && $rider) {
            $pusher->actionPushOfferFound($rider, $offer, false);
        }
        
        //ROSS Edgar
        $offer = Offer::find()->where(['request_cuser' => '57bb54360485157bb543604857'])->one();
        $rider = Cuser::find()->where(['id' => '57bb54360485157bb543604857'])->one();
        if ($offer && $rider) {
            @$pusher->actionPushOfferFound($rider, $offer, false);
        }
    }

    /**
     * Sends a test notification to Ross Edgar.
     * Note: must have a request and an offer for Ross already
     */
    public function actionTestpushdirect($username = 'mediabeef')
    {
        $pusher = new Pusher();
        //Mediabeef
        $rider = Cuser::find()->where(['username' => [$username, ucwords($username)]])->one();
        if ($rider) {
            $pusher->actionPushDirect($rider, false);
        }
    }
}
