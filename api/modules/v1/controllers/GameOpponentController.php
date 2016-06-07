<?php
namespace app\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;

class GameOpponentController extends ActiveController
{
    public $modelClass = 'app\models\GameOpponent';

    public function behaviors()
    {
        return ArrayHelper::merge([
                                      [
                                          'class' => Cors::className(),
                                          'cors' => [
                                              'Origin' => ['http://trilaphp', 'http://trilaphp:8080', 'http://localhost:8080', 'http://api.ngxtri.com',
                                                  'http://api.trilaphp'],
                                          ],
                                      ],
                                  ], parent::behaviors());
    }
}
