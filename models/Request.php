<?php

namespace app\models;

use Yii;
use \app\models\base\Request as BaseRequest;

/**
 * This is the model class for table "request".
 */
class Request extends BaseRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id', 'cuser_id', 'dropoff_lat', 'dropoff_lng', 'pickup_lat', 'pickup_lng'], 'required'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['dropoff_lat', 'dropoff_lng', 'pickup_lat', 'pickup_lng'], 'number'],
            [['id', 'cuser_id'], 'string', 'max' => 36],
            [['dropoff_full_address', 'pickup_full_address'], 'string', 'max' => 400]
        ]);
    }
	
}
