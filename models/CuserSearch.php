<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cuser;

/**
 * app\models\CuserSearch represents the model behind the search form about `app\models\Cuser`.
 */
 class CuserSearch extends Cuser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'first_name', 'status_code', 'created_at', 'updated_at', 'status_description', 'hashed_password', 'email', 'username', 'commuter_data', 'address_realtime'], 'safe'],
            [['commuter', 'enrolled'], 'integer'],
            [['lat', 'lng'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Cuser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'commuter' => $this->commuter,
            'enrolled' => $this->enrolled,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'status_code', $this->status_code])
            ->andFilterWhere(['like', 'status_description', $this->status_description])
            ->andFilterWhere(['like', 'hashed_password', $this->hashed_password])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'commuter_data', $this->commuter_data])
            ->andFilterWhere(['like', 'address_realtime', $this->address_realtime]);

        return $dataProvider;
    }
}
