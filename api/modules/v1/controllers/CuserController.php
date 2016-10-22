<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Cuser;
use yii\base\Exception;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\HttpException;

class CuserController extends BaseActiveController
{
    public $modelClass='app\models\Cuser';
    const MAX_COORDS_DIFF=1;
    const MAX_ITEMS=5;

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
                    'Access-Control-Request-Methods'=>['GET','POST','OPTIONS','DELETE','PUT','PATCH'],
                    'Access-Control-Request-Headers'=>['Content-Type']
                ],
            ],
        ],parent::behaviors());
    }

    public function actionCreate()
    {
        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        header("Access-Control-Allow-Origin: *");

        $entityBody=file_get_contents('php://input');
        try
        {
            $entityBody=json_decode($entityBody);
            //if cuser exists, return its id
            $cuser=Cuser::find()->where(array('commuter'=>intval($entityBody->commuter)))->one();
            if(is_object($cuser) && isset($cuser->id))
            {
                /** @var Cuser $cuser */
                if(property_exists($entityBody,'commuter_data'))
                {
                    $cuser->commuter_data=$entityBody->commuter_data;
                    if(!$cuser->save())
                    {
                        \Yii::error("Failed saving cuser " . json_encode($cuser));
                    };
                }
                echo '{"status":"successful", "id":"' . $cuser->id . '"}';
                \Yii::$app->response->setStatusCode(200);
                return;
            }
            //else, try creating one
            $new_cuser=new Cuser();
            $new_cuser->load(['Cuser'=>(array)$entityBody]);
            try
            {
                $new_cuser->save();
                echo '{"status":"successful", "id":"' . $new_cuser->id . '"}';
                \Yii::$app->response->setStatusCode(201);
                return;
            } catch (Exception $e)
            {
                \Yii::error("Cant create new cuser " . json_encode($entityBody) . $e->getMessage());
                \Yii::$app->response->setStatusCode(400);
                return;
            }
        } catch (Exception $e)
        {
            \Yii::error("Bad input " . $e->getMessage());
            \Yii::$app->response->setStatusCode(400);
            return;
        }
    }

    public function actionQuery()
    {
        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        header("Access-Control-Allow-Origin: *");

        $data=\Yii::$app->request->get('data');

        if(!$data)
        {
            \Yii::error("Bad input ");
            \Yii::$app->response->setStatusCode(400);
            return;
        }
        //data is array of cuser ids
        $cusers=Cuser::find()->where(['id'=>$data])->asArray()->all();
        $result=[];
        foreach ($cusers as $cuser)
        {
            $commuter_data=json_decode($cuser['commuter_data'],true);
            $name=$cuser['first_name'];
            if(isset($commuter_data['commuterName']))
            {
                $name=$commuter_data['commuterName'];
            }
            $phone='';
            if(isset($commuter_data['hphone']))
            {
                $phone=$commuter_data['hphone'];
            }
            $result[$cuser['id']]=[$name,$phone];
        }
        echo json_encode($result);
        return;
    }

    /**
     * @param null $cur_lat
     * @param null $cur_lng
     */
    public function actionGetDrivers($cur_lat=null,$cur_lng=null)
    {
        if(is_null($cur_lat))
        {
            $cur_lat=38.900571;
        }
        if(is_null($cur_lng))
        {
            $cur_lng=-77.008910;
        }


        $drivers=Cuser::find()->select(['id','lat','lng'])->where(['>=','lat',$cur_lat - self::MAX_COORDS_DIFF])
            ->andWhere(['<=','lat',$cur_lat + self::MAX_COORDS_DIFF])
            ->andWhere(['>=','lng',$cur_lng - self::MAX_COORDS_DIFF,])
            ->andWhere(['<=','lng',$cur_lng + self::MAX_COORDS_DIFF])
            ->andWhere(['<>','id',\Yii::$app->request->get('rider')])
            ->andWhere(['=', 'cuser_status', 'driver_idle']) //driveridle
            ->limit(self::MAX_ITEMS);
        return $drivers->all();

    }

}
























































