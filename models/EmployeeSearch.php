<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class EmployeeSearch extends Employee
{
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'email'], 'safe'],            
        ];
    }

    public function search($params)
    {
        $query = Employee::find()->joinWith('department');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['dept_id' => $this->dept_id])
              ->andFilterWhere(['status' => $this->status])
              ->andFilterWhere(['like', 'firstname', $this->firstname])
              ->andFilterWhere(['like', 'lastname', $this->lastname])
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
