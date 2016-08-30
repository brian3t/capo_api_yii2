<?php

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "cuser".
 *
 * @property string $id
 * @property string $first_name
 * @property string $status_code
 * @property string $created_at
 * @property string $updated_at
 * @property string $status_description
 * @property integer $commuter
 * @property string $hashed_password
 * @property integer $enrolled
 * @property string $email
 * @property string $username
 * @property string $commuter_data
 * @property array $commuter_data_array
 * @property string $lat
 * @property string $lng
 * @property string $address_realtime
 *
 * @property string $name
 * @property string $phone
 * @property \app\models\Offer $offer
 * @property \app\models\Request[] $requests
 *
 */
class Cuser extends \yii\db\ActiveRecord
{

    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'],'required'],
            [['commuter','enrolled'],'number'],
            [['lat', 'lng'], 'number'],
            [['id','first_name','status_description','username'],'string','max'=>80],
            [['status_code'],'string','max'=>20],
            [['created_at','updated_at'],'string'],
            [['hashed_password'],'string','max'=>28],
            [['email'],'string','max'=>125],
            [['commuter_data'], 'string', 'max' => 8000],
            [['address_realtime'], 'string', 'max' => 800]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cuser';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'first_name'=>'First Name',
            'status_code'=>'Status Code',
            'status_description'=>'Status Description',
            'commuter'=>'Commuter',
            'hashed_password'=>'Hashed Password',
            'enrolled'=>'Enrolled',
            'email'=>'Email',
            'username'=>'Username',
            'lat' => 'Current Lat',
            'lng' => 'Current Lng',
            'address_realtime' => 'Current Address',
        );
    }

    public function beforeValidate()
    {
        if(empty($this->id))
        {
            $this->id=uniqid() . uniqid();
        }
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(\app\models\Request::className(),['cuser_id'=>'id']);
    }

    public function getCommuter_data_array()
    {
        try
        {
            return json_decode($this->commuter_data,true);
        } catch (\Exception $e)
        {
            error("Bad commuter data. Message: {$e->getMessage()}. Cuser: " . json_encode($this->attributes));
            return '';
        }
    }

    public function getName()
    {
        $name=$this->first_name;

        if(isset($this->commuter_data_array['commuterName']))
        {
            $name=$this->commuter_data_array['commuterName'];
        }
        return $name;
    }

    public function getPhone()
    {
        $phone='';
        if(isset($this->commuter_data_array['hphone']))
        {
            $phone=$this->commuter_data_array['hphone'];
        }
        return $phone;
    }

    public function fields()
    {
        return array_merge(['name','phone'],parent::fields());
    }

    public function getUsername_and_id()
    {
        return $this->username . ' - ' . $this->id;
    }
}