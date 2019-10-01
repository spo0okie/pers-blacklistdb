<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Employees */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => \app\models\Employees::$title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employees-view">

    <div class="row">
        <div class="col-md-6" >
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-md-6" >
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" >
	        <?php try {
		        echo DetailView::widget([
			        'model' => $model,
			        'attributes' => [
				        'position',
				        'employment',
				        'reason',
				        'comment',
			        ],
		        ]);
	        } catch (Exception $e) {
		        echo 'Ошибка вывода виджета DetailView';
	        } ?>
            <p>
		        <?= Html::a('Правка', ['update', 'id' => $model->employee_id], ['class' => 'btn btn-primary']) ?>
		        <?= Html::a('Удалить', ['delete', 'id' => $model->employee_id], [
			        'class' => 'btn btn-danger',
			        'data' => [
				        'confirm' => 'Удалить запись о сотруднике?',
				        'method' => 'post',
			        ],
		        ]) ?>
            </p>
        </div>
        <div class="col-md-6" >
	        <?php try {
		        echo DetailView::widget([
			        'model' => $model,
			        'attributes' => [
				        'updated_at',
				        [
					        'label' => 'Изменения внес',
					        'value' => $model->updatedBy->Ename,
				        ],
			        ],
		        ]);
	        } catch (Exception $e) {
		        echo 'Ошибка вывода виджета DetailView';
	        } ?>
        </div>
    </div>
    <br />
    <p>История версий:</p>
    <div>
	    <?= GridView::widget([
		    'dataProvider' => $dataProvider,
		    'persistResize' => true,
		    'hover'=>true,
		    'layout' => '{items}',
		    'columns' => [
			    'name',
			    'position',
			    'reason',
			    'employment',
			    'comment',
			    'updated_at',
			    [
				    'label' => 'Изменения внес',
				    'attribute' => 'updatedBy.Ename',
			    ],
		    ],
	    ]); ?>
    </div>



</div>
