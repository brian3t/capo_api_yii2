<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "request".
 *
 * @property string $cuser_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $dropoff_full_address
 * @property string $dropoff_lat
 * @property string $dropoff_lng
 * @property string $pickup_full_address
 * @property string $pickup_lat
 * @property string $pickup_lng
 * @property string $trigger_col
 *
 * @property \app\models\Offer[] $offers
 * @property \app\models\Cuser $cuser
 */
class Request extends \yii\db\ActiveRecord
{

//    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cuser_id', 'dropoff_lat', 'dropoff_lng', 'pickup_lat', 'pickup_lng'], 'required'],
            [['status'], 'string'],
            [['created_at', 'updated_at', 'trigger_col'], 'safe'],
            [['dropoff_lat', 'dropoff_lng', 'pickup_lat', 'pickup_lng'], 'number'],
            [['cuser_id'], 'string', 'max' => 36],
            [['dropoff_full_address', 'pickup_full_address'], 'string', 'max' => 400]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cuser_id' => 'Cuser ID',
            'status' => 'Status',
            'dropoff_full_address' => 'Dropoff Full Address',
            'dropoff_lat' => 'Dropoff Lat',
            'dropoff_lng' => 'Dropoff Lng',
            'pickup_full_address' => 'Pickup Full Address',
            'pickup_lat' => 'Pickup Lat',
            'pickup_lng' => 'Pickup Lng',
            'trigger_col' => 'Trigger Col',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(\app\models\Offer::className(), ['request_cuser' => 'cuser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCuser()
    {
        return $this->hasOne(\app\models\Cuser::className(), ['id' => 'cuser_id']);
    }

/**
     * @inheritdoc
     * @return type mixed
     */ 
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\RequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\RequestQuery(get_called_class());
    }
}
