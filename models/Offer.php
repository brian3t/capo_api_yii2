<?php

namespace app\models;

use Yii;
use \app\models\base\Offer as BaseOffer;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "offer".
 */
class Offer extends BaseOffer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['cuser_id', 'request_cuser'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['cuser_id', 'request_cuser'], 'string', 'max' => 26]
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

}
