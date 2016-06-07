<?php
namespace app\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;


class CommentController extends ActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Comment';

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