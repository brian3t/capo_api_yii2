<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="cuser-form">

    <?= $form->field($Request, 'status')->dropDownList([ 'pending' => 'Pending', 'cancelled' => 'Cancelled', 'fulfilled' => 'Fulfilled', 'timeout' => 'Timeout', 'accepted' => 'Accepted', ], ['prompt' => '']) ?>

    <?= $form->field($Request, 'dropoff_full_address')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Full Address']) ?>

    <?= $form->field($Request, 'dropoff_lat')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Lat']) ?>

    <?= $form->field($Request, 'dropoff_lng')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Lng']) ?>

    <?= $form->field($Request, 'pickup_full_address')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Full Address']) ?>

    <?= $form->field($Request, 'pickup_lat')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Lat']) ?>

    <?= $form->field($Request, 'pickup_lng')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Lng']) ?>

    <?= $form->field($Request, 'trigger_col')->textInput(['placeholder' => 'Trigger Col']) ?>

</div>
