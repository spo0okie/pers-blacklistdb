<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property int $id id
 * @property int $employee_id id Сотрудника
 * @property string $name ФИО
 * @property string $position Должность
 * @property string $reason Причина
 * @property string $employment Тип трудоустройства
 * @property string $comment Примечание
 * @property string $updated_at Время записи
 * @property int $updated_by Автор
 * @property int $deleted Удалено
 *
 * @property Users $updatedBy
 */
class Employees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'position', 'reason'], 'string', 'max' => 64],
            [['employment'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'employee_id' => 'id Сотрудника',
            'name' => 'ФИО',
            'position' => 'Должность',
            'reason' => 'Причина',
            'employment' => 'Тип трудоустройства',
            'comment' => 'Примечание',
            'updated_at' => 'Время записи',
            'updated_by' => 'Автор',
            'deleted' => 'Удалено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }


	/**
	 * Перед сохранением вносит правки
	 * @param bool $insert
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			//обновлено текущим пользователем
			$this->updated_by=Yii::$app->user->id;
			//если номер сотрудника не выставлен, то новый
			if (!$this->employee_id) $this->employee_id=1;//static::find()->max('employee_id')+1;
			//если флажок удаления не выставлен явно, то ноль
			if (is_null($this->deleted)) $this->deleted=0;
			return true;
		} else {
			return false;
		}
	}

}
