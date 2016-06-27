<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Cuser]].
 *
 * @see Cuser
 */
class CuserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Cuser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Cuser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}