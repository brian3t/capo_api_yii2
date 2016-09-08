<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Request;

/**
 * Class RequestController
 * @package app\commands
 */
class RequestController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionPrune()
    {
        $idle_requests=[];
//        $idle_requests=Request::find()->where(['<','updated_at',"SYSTIMESTAMP - INTERVAL '10' MINUTE "])->asArray()->all();
        $idle_requests=Request::findBySql("SELECT cuser_id, pickup_full_address, dropoff_full_address FROM `request` where `updated_at` < (SYSTIMESTAMP - INTERVAL '10' MINUTE) ");
        \Yii::error("Requests deleted" . json_encode($idle_requests->asArray()->all(),JSON_PRETTY_PRINT));
//        \Yii::$app->db->createCommand("DELETE FROM `request` where `updated_at` < date_sub(now(), interval 10 minute) ")
        \Yii::$app->db->createCommand("DELETE FROM `request` where `updated_at` < (SYSTIMESTAMP - INTERVAL '10' MINUTE) ")
            ->execute();
    }
}
