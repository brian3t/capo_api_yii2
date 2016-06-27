<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cuser */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="cuser-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model); ?>

    <?php //= $form->field($model, 'id', ['template' => '{input}'])->textInput(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'First Name']) ?>

    <?= $form->field($model, 'status_code')->textInput(['maxlength' => true, 'placeholder' => 'Status Code']) ?>

    <?= $form->field($model, 'status_description')->textInput(['maxlength' => true, 'placeholder' => 'Status Description']) ?>

    <?= $form->field($model, 'commuter')->textInput(['placeholder' => 'Commuter']) ?>

    <?= $form->field($model, 'hashed_password')->textInput(['maxlength' => true, 'placeholder' => 'Hashed Password']) ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username']) ?>
    <?= $form->field($model, 'enrolled')->textInput(['placeholder' => 'Enrolled']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Username']) ?>

    <?= $form->field($model, 'commuter_data')->textInput(['maxlength' => true, 'placeholder' => 'Commuter Data']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
