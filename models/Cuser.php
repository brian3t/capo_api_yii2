<?php

namespace app\models;

use Yii;
use \app\models\base\Cuser as BaseCuser;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cuser".
 */
class Cuser extends BaseCuser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
            [
                [['id'], 'required'],
                [['commuter', 'enrolled'], 'number'],
                [['lat', 'lng'], 'number'],
                [['id', 'first_name', 'status_description', 'username'], 'string', 'max' => 80],
                [['status_code'], 'string', 'max' => 20],
                [['created_at', 'updated_at'], 'string'],
                [['hashed_password'], 'string', 'max' => 28],
                [['email'], 'string', 'max' => 125],
                [['commuter_data'], 'string', 'max' => 8000],
                [['address_realtime'], 'string', 'max' => 800],
                [['apns_device_reg_id'], 'string', 'max' => 64]
            ]);
    }
    
    /**
     * @inheritdoc
     * @return mixed
     */
    public function behaviors()
    {
        if (\Yii::$app->params['ISLOCAL']) {
            return [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => false,
                    'updatedAtAttribute' => 'updated_at',
                    'value' => new \yii\db\Expression('NOW()'),
                ],
            ];

        } else {

            return [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => false,
                    'updatedAtAttribute' => 'updated_at',
                    'value' => new \yii\db\Expression('SYSDATE'),
                ],
            ];
        }
    }
    
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['updated_at']);
        return $fields;
    }
}
