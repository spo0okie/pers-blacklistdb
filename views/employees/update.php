<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employees */

$this->title = 'Редактирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => \app\models\Employees::$title, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->employee_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="employees-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
