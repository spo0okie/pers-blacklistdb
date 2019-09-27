<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employees;
use yii\db\ActiveQuery;

/**
 * EmployeesSearch represents the model behind the search form of `app\models\Employees`.
 */
class EmployeesSearch extends Employees
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'updated_by', 'deleted'], 'integer'],
            [['name', 'position', 'reason', 'employment', 'comment', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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

    	/*
    	 * Делаем извращенский запрос примерно следующего смысла:
    	 * выбери мне всех сотрудников и максимальные номера записей по нима
    	 * и через джойн выбери мне все эти записи
    	 * */
		$query=static::reqLast();

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
	        'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // убираем удаленные записи
        $query->andFilterWhere([
            'deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'employment', $this->employment])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
