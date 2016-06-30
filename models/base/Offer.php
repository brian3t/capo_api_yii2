<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "offer".
 *
 * @property string $cuser_id
 * @property string $request_cuser
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 *
 * @property \app\models\Request $requestCuser
 * @property \app\models\Cuser $cuser
 */
class Offer extends \yii\db\ActiveRecord
{

    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cuser_id', 'request_cuser'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['cuser_id', 'request_cuser'], 'string', 'max' => 26]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cuser_id' => 'Cuser ID',
            'request_cuser' => 'Request Cuser',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestCuser()
    {
        return $this->hasOne(\app\models\Request::className(), ['cuser_id' => 'request_cuser']);
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
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }
}
