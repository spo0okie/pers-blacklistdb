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
	static $title='Сотрудники';

	static $cache=null;

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
	public static function primaryKey()
	{
		return ["employee_id"];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
	        [['employment'], 'string', 'max' => 32],
            [['name', 'position'], 'string', 'max' => 64],
            [['comment', 'reason'], 'string', 'max' => 255],
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
			unset($this->id);
			//обновлено текущим пользователем
			$this->updated_by=Yii::$app->user->id;
			//если номер сотрудника не выставлен, то новый
			if (!$this->employee_id) $this->employee_id=static::find()->max('employee_id')+1;
			//если флажок удаления не выставлен явно, то ноль
			if (is_null($this->deleted)) $this->deleted=0;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * формирует код для поиска всех записей
	 * описана отдельной функцией по той причине, что у нас нестандартная работа с таблицей
	 * и по сути запрос всех записей не является запросом всех, а на самом деле
	 * запрашивает последние версии записей о каждом сотруднике
	 */
	static public function reqLast() {
		return static::find()
			->select('*')
			->innerJoin(
				'(SELECT max(id) max_id, employee_id FROM employees GROUP BY employee_id) i',
				'employees.id=i.max_id'
			);
	}

	/**
	 * возвращает все записи + кэширует
	 * @return array|null|\yii\db\ActiveRecord[]
	 */
	static public function fetchAll()
	{
		/**
		 * Есть кэш - отдаем его
		 * нет кэша - делаем его и отдаем
		 */
		return (!is_null(static::$cache))?
			static::$cache
			:
			static::$cache=static::reqLast()->all();
	}

	/**
	 * возвращает все актуальные уникальные значения какого-то поля
	 * @param string $field
	 * @return array
	 */
	static public function fetchFields($field='name') {
		$list = static::find()
			->select($field)
			->distinct($field)
			->innerJoin(
				'(SELECT max(id) max_id, employee_id FROM employees GROUP BY employee_id) i',
				'employees.id=i.max_id'
			)
			->where('deleted=0')->all();
		return \yii\helpers\ArrayHelper::getColumn($list, $field);
	}

	static public function reqHistory($id) {
		return static::find()
			->select('*')
			->Where(['employee_id'=>$id])
			->orderBy(['id'=>SORT_DESC]);
	}
}
