<?php
namespace app\models;
use Yii;
/**
 * This is the model class for table "users".
 *
 * @property string $id Табельный номер
 * @property string $Ename Полное имя
 * @property int $Uvolen Уволен
 * @property string $Login Логин (AD)
 */
class Users extends \yii\db\ActiveRecord
{
	public static $title="Сотрудники";

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'users';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'Ename', 'Uvolen', ], 'required'],
			[['Uvolen'], 'integer'],
			[['id' ], 'string', 'max' => 16],
			[['Ename', 'Login'], 'string', 'max' => 255],
			[['id'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'Табельный номер',
			'Ename' => 'Полное имя',
			'Uvolen' => 'Уволен',
			'Login' => 'Логин (AD)',
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		foreach (self::$users as $user) {
			if ($user['accessToken'] === $token) {
				return new static($user);
			}
		}
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}
	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return $this->password === $password;
	}

	/**
	 * Возвращает массив ключ=>значение запрошенных/всех записей таблицы
	 * @param array $items список элементов для вывода
	 * @param string $keyField поле - ключ
	 * @param string $valueField поле - значение
	 * @param bool $asArray
	 * @return array
	 */
	public static function listItems($items=null, $keyField = 'id', $valueField = 'Ename', $asArray = true)
	{
		$query = static::find()->filterWhere(['Uvolen'=>0])->andWhere(['!=','Login',''])->orderBy('Ename');
		if (!is_null($items)) $query->filterWhere(['id'=>$items]);
		if ($asArray) $query->select([$keyField, $valueField])->asArray();
		return \yii\helpers\ArrayHelper::map($query->all(), $keyField, $valueField);
	}
}