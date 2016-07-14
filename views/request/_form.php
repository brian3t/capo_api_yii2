<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Offer', 
        'relID' => 'offer', 
        'value' => \yii\helpers\Json::encode($model->offers),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'cuser_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Cuser::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => 'Choose Cuser'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'pending' => 'Pending', 'cancelled' => 'Cancelled', 'fulfilled' => 'Fulfilled', 'timeout' => 'Timeout', 'accepted' => 'Accepted', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'dropoff_full_address')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Full Address']) ?>

    <?= $form->field($model, 'dropoff_lat')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Lat']) ?>

    <?= $form->field($model, 'dropoff_lng')->textInput(['maxlength' => true, 'placeholder' => 'Dropoff Lng']) ?>

    <?= $form->field($model, 'pickup_full_address')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Full Address']) ?>

    <?= $form->field($model, 'pickup_lat')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Lat']) ?>

    <?= $form->field($model, 'pickup_lng')->textInput(['maxlength' => true, 'placeholder' => 'Pickup Lng']) ?>

    <div class="form-group" id="add-offer"></div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'),['index'],['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
