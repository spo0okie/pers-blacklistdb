<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;

/* @var $this yii\web\View */
/* @var $model app\models\Employees */
/* @var $form yii\widgets\ActiveForm */

$positions=\app\models\Employees::fetchFields('position');
$reasons=\app\models\Employees::fetchFields('reason');
$employments=\app\models\Employees::fetchFields('employment');

?>

<div class="employees-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


	<?= $form->field($model, 'position')->widget(Typeahead::classname(), [
		'options' => ['autocomplete'=>'off'],
		'pluginOptions' => ['highlight'=>true],
		'defaultSuggestions' => $positions,
		'dataset' => [[	'local' => $positions,	'limit' => '10']]
    ]); ?>

	<?= $form->field($model, 'employment')->widget(Typeahead::classname(), [
		'options' => ['autocomplete'=>'off'],
		'pluginOptions' => ['highlight'=>true],
		'defaultSuggestions' => $employments,
		'dataset' => [[	'local' => $employments,	'limit' => '10']]
	]); ?>

	<?= $form->field($model, 'reason')->widget(Typeahead::classname(), [
		'options' => ['autocomplete'=>'off'],
		'pluginOptions' => ['highlight'=>true],
		'defaultSuggestions' => $reasons,
		'dataset' => [[	'local' => $reasons,	'limit' => '10']]
	]); ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
