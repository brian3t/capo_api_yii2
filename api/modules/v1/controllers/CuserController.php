<?php
namespace app\api\modules\v1\controllers;

use app\models\Cuser;
use yii\base\Exception;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\HttpException;

class CuserController extends ActiveController
{
    public $modelClass='app\models\Cuser';

    public function actions()
    {
        $actions=parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class'=>Cors::className(),
                'cors'=>[
                    'Origin'=>['*'],
                    'Access-Control-Request-Methods'=>['GET','POST','OPTIONS','DELETE','PUT'],
                    'Access-Control-Request-Headers'=>['Content-Type']
                ],
            ],
        ],parent::behaviors());
    }

    public function actionCreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        header("Access-Control-Allow-Origin: *");

        $entityBody=file_get_contents('php://input');
        try
        {
            $entityBody=json_decode($entityBody);
            //if cuser exists, return its id
//            $cuser = Cuser::findOne(['commuter'=>$entityBody->commuter]);
//            $cuser  = Cuser::find()->where(['id'=>'571317eeb6f15571317eeb6f1a']);
            $cuser  = Cuser::find()->where(array('commuter'=>intval($entityBody->commuter)));
            //else, try creating one
        } catch (Exception $e)
        {
            \Yii::error("Bad input " . $e->getMessage());
//            echo '{"status":"successful"}';
            \Yii::$app->response->setStatusCode(404);
//            throw new HttpException(404,'Bad incoming data!');
            return;
        }

        echo '{"status":"successful"}';
        return;
    }

}
