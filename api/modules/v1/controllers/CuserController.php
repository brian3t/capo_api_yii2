<?php
namespace app\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;

class CuserController extends ActiveController
{
    public $modelClass = 'app\models\Cuser';

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
