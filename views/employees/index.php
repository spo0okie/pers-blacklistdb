<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \app\models\Employees::$title;
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('tbody td').on('click', function (e) {
        // Получить ближайший ID в <tr> из data-key атрибута
        var id = $(this).closest('tr').data('key');
        if(e.target == this)
            location.href = '" . \yii\helpers\Url::to(['employees/view']) . "?id=' + id;
    });
", yii\web\View::POS_END);


?>
<div class="employees-index">

    <?php if (\Yii::$app->user->can('edit_access')) { ?>
        <p>
		    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php }?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'persistResize' => true,
	    'hover'=>true,
	    'layout' => '{items}',
	    'columns' => [
            'name',
            'position',
            'reason',
            'employment',
            'comment',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
