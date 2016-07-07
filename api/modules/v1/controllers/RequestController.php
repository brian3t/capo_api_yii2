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

class RequestController extends BaseActiveController
{
    public $modelClass = 'app\models\Request';

    public function actions()
    {
        $actions = parent::actions();
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
                    'Access-Control-Request-Methods' => ['GET', 'POST', 'OPTIONS', 'DELETE', 'PUT'],
                    'Access-Control-Request-Headers' => ['Content-Type']
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
                    \Yii::error("Failed updating request" . json_encode($request). "\nNew data: ". json_encode($entityBody));
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


}
