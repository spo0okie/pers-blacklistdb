<?php
/**
 * Created by PhpStorm.
 * User: aareviakin
 * Date: 15.09.2019
 * Time: 13:42
 */


namespace console\controllers;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

	public function actionInit() {
		$auth = Yii::$app->authManager;

		$auth->removeAll(); //На всякий случай удаляем старые данные из БД...

		// Создадим роли админа и редактора новостей
		$admin = $auth->createRole('admin');
		$editor = $auth->createRole('editor');

		// запишем их в БД
		$auth->add($admin);
		$auth->add($editor);

		// Создаем разрешения. Например, просмотр админки viewAdminPage и редактирование новости updateNews
		$viewAdminPage = $auth->createPermission('viewAdminPage');
		$viewAdminPage->description = 'Просмотр админки';

		$updatePersons = $auth->createPermission('updatePersons');
		$updatePersons->description = 'Редактирование сотрудников';

		// Запишем эти разрешения в БД
		$auth->add($viewAdminPage);
		$auth->add($updatePersons);

		// Теперь добавим наследования. Для роли editor мы добавим разрешение updateNews,
		// а для админа добавим наследование от роли editor и еще добавим собственное разрешение viewAdminPage

		// Роли «Редактор новостей» присваиваем разрешение «Редактирование новости»
		$auth->addChild($editor,$updatePersons);

		// админ наследует роль редактора новостей. Он же админ, должен уметь всё! :D
		$auth->addChild($admin, $editor);

		// Еще админ имеет собственное разрешение - «Просмотр админки»
		$auth->addChild($admin, $viewAdminPage);

		// Назначаем роль admin пользователю с ID 1
		$auth->assign($admin, 1);

		// Назначаем роль editor пользователю с ID 2
		$auth->assign($editor, 2);
	}
}