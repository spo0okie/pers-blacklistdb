<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employees */

$this->title = 'Добавление сотрудника';
$this->params['breadcrumbs'][] = ['label' => \app\models\Employees::$title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
