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
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public static $title="Сотрудники";
	public static $users=[];

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
			[['Ename', 'Uvolen', ], 'required'],
			[['Uvolen'], 'integer'],
			//[['id' ], 'string', 'max' => 16],
			[['Ename', 'Login'], 'string', 'max' => 255],
			//[['id'], 'unique'],
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
			'Uvolen' => 'Отключен',
			'Login' => 'Логин (AD)',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
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

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->id;
	}

	public static function findByLogin($login){
		$login=mb_strtolower($login);
		//при поиске по логину предпочитаем сначала искать среди трудоустроенных
		$list = static::find()->select(['id','Login','Uvolen'])->orderBy(['Uvolen'=>'ASC','id'=>'DESC'])->all();
		foreach ($list as $item) {
			if (!strcmp(mb_strtolower($item['Login']),$login)) return $item;
		}
		return null;
	}


	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAccessToken()
	{
		$this->access_token = Yii::$app->security->generateRandomString();
	}

	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id]);
	}

	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne(['access_token' => $token]);
	}

}