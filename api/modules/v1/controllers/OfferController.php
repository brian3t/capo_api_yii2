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

class OfferController extends BaseActiveController
{
    public $modelClass = 'app\models\Offer';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Methods' => ['GET', 'POST', 'OPTIONS', 'DELETE', 'PUT'],
                    'Access-Control-Request-Headers' => ['Content-Type']
                ],
            ],
        ], parent::behaviors());
    }

    public function actionIndex()
    {
        /* @var $modelClass Offer */
        $modelClass = $this->modelClass;

        $offers = $modelClass::find()->innerJoinWith('cuser')->where(\Yii::$app->request->queryParams)->addSelect('*,cuser.commuter_data, cuser.first_name')->asArray()->all();
        /** @var array $offers */
        foreach ($offers as &$offer) {
            $commuter_data = json_decode($offer['commuter_data'], true);
            $name = $commuter_data['commuterName']??$offer['first_name'];
            $phone = $commuter_data['hphone']??'';
            $offer['name'] = $name;
            $offer['phone'] = $phone;
            // $offer = ArrayHelper::getColumn($offer, ['cuser_id', 'created_at', 'updated_at', 'status']);
            $offer = array_intersect_key($offer, array_flip(['cuser_id', 'created_at', 'updated_at', 'status', 'name', 'phone']));
        }
        echo json_encode($offers);
        return;

    }
    
}
