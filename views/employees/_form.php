<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;

/* @var $this yii\web\View */
/* @var $model app\models\Employees */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'position')->widget(Typeahead::classname(), [
		'options' => ['placeholder' => 'Наберите или выберите название','autocomplete'=>'on'],
		'pluginOptions' => ['highlight'=>true],
		'dataset' => [[	'local' => \app\models\Employees::fetchFields('position'),	'limit' => '10']]
    ]); ?>


	<?= $form->field($model, 'reason')->widget(Typeahead::classname(), [
		'options' => ['placeholder' => 'Наберите или выберите название','autocomplete'=>'on'],
		'pluginOptions' => ['highlight'=>true],
		'dataset' => [[	'local' => \app\models\Employees::fetchFields('reason'),	'limit' => '10']]
	]); ?>

	<?= $form->field($model, 'employment')->widget(Typeahead::classname(), [
		'options' => ['placeholder' => 'Наберите или выберите название','autocomplete'=>'on'],
		'pluginOptions' => ['highlight'=>true],
		'dataset' => [[	'local' => \app\models\Employees::fetchFields('employment'),	'limit' => '10']]
	]); ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
