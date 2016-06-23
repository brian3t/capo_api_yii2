<?php
namespace app\api\modules\v1\controllers;

use app\models\Cuser;
use yii\base\Exception;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\HttpException;

class RequestController extends ActiveController
{
    public $modelClass = 'app\models\Request';

    public function actions()
    {
        $actions = parent::actions();
        // unset($actions['create']);
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


}
