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
		//Yii::warning('OfferController > actionIndex');
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
		Yii::warning('OfferController > actionUpdate');
        /* @var $model ActiveRecord */
        $model = Offer::find()->where(['cuser_id' => $id])->one();
        //try creating if not exists
        if (is_null($model)) {
            $model = new Offer();
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create new offer.');
            }
            //todob notify mobile devices here
			
			
			
			// START mhemry	
			$rider = $model->requestCuser;			
			Yii::warning('OfferController > actionUpdate > notify rider 1');
			Yii::warning("OfferController > actionUpdate > rider apns: " . $rider->apns_device_reg_id);
			if ($rider->apns_device_reg_id !== null) {
				Yii::warning('OfferController > actionUpdate > notify rider 2 - action time START');
				$pusher = new Pusher();
				$pusher->actionPushOfferFound($rider, $model);
				Yii::warning('OfferController > actionUpdate > notify rider 3 - action time END');
			}
			Yii::warning('OfferController > actionUpdate > the end');
			// END mhemry


			
			
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
    
    /**
     * Updates an existing model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function actionCreate()
    {
		Yii::warning('OfferController > actionCreate');
        /* @var $model Offer */
        $model = new Offer();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
			Yii::warning('OfferController > actionCreate > model saved');
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
        } elseif (!$model->hasErrors()) {
			Yii::warning('OfferController > actionCreate > model save error');
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        
        //notifies rider
        $rider = $model->requestCuser;
        /* @var $rider Cuser */
		//Yii::error("Offer create ". json_encode($rider->getAttributes()));
		Yii::warning('OfferController > actionCreate > notify rider 1');
        if ($rider->apns_device_reg_id !== null) {
			Yii::warning('OfferController > actionCreate > notify rider 2 - action time START');
            $pusher = new Pusher();
            $pusher->actionPushOfferFound($rider, $model);
			Yii::warning('OfferController > actionCreate > notify rider 3 - action time END');
        }
		Yii::warning('OfferController > actionCreate > the end');
        return $model;
    }
    
    /**
     * Sends a test notification to Ross Edgar.
     * Note: must have a request and an offer for Ross already
     */
    public function actionTestpush()
    {
        $pusher = new Pusher();
        $offer = Offer::find()->where(['request_cuser' => '57c3a3235ac4a57c3a3235ac4f'])->one();
        $rider = Cuser::find()->where(['id' => '57c3a3235ac4a57c3a3235ac4f'])->one();
        if ($offer && $rider) {
			$pusher->actionPushOfferFound($rider, $offer);
		}
		
        $offer = Offer::find()->where(['request_cuser' => '57bb54360485157bb543604857'])->one();
        $rider = Cuser::find()->where(['id' => '57bb54360485157bb543604857'])->one();
        if ($offer && $rider) {
			@$pusher->actionPushOfferFound($rider, $offer);
		}
    }
}
