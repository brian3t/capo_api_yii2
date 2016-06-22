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
                [['id', 'first_name', 'status_description', 'username'], 'string', 'max' => 80],
                [['status_code'], 'string', 'max' => 20],
                [['created_at', 'updated_at'], 'string', 'max' => 6],
                [['hashed_password'], 'string', 'max' => 28],
                [['email'], 'string', 'max' => 125]
            ]);
    }
    
    /**
     * @inheritdoc
     * @return type mixed
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
    
    
}
