<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title='Offer';
$this->params['breadcrumbs'][]=$this->title;
$search="$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="offer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Offer',['create'],['class'=>'btn btn-success']) ?>
    </p>
    <?php
    $gridColumn=[
        ['class'=>'yii\grid\SerialColumn'],
        [
            'attribute'=>'cuser_id',
            'label'=>'Driver',
            'value'=>function ($model)
            {
                return $model->cuser->username . ' - ' . $model->cuser->id;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Cuser::find()->asArray()->all(),'id','id'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Cuser','id'=>'grid--cuser']
        ],
        [
            'attribute'=>'request_cuser',
            'label'=>'Rider',
            'value'=>function ($model)
            {
                return $model->requestCuser->cuser->username;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Request::find()->asArray()->all(),'cuser_id','cuser_id'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Request','id'=>'grid--request_cuser']
        ],
        'status',
        [
            'attribute'=>'created_at',
            // 'format' => 'datetime'
        ],
        [
            'attribute'=>'updated_at',
            // 'format' => 'datetime'
        ],

        [
            'class'=>'yii\grid\ActionColumn',
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider'=>$dataProvider,
        'columns'=>$gridColumn,
        'pjax'=>true,
        'pjaxSettings'=>['options'=>['id'=>'kv-pjax-container-offer']],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        // set a label for default menu
        'export'=>[
            'label'=>'Page',
            'fontAwesome'=>true,
        ],
        // your toolbar can include the additional full export menu
        'toolbar'=>[
            '{export}',
            ExportMenu::widget([
                'dataProvider'=>$dataProvider,
                'columns'=>$gridColumn,
                'target'=>ExportMenu::TARGET_BLANK,
                'fontAwesome'=>true,
                'dropdownOptions'=>[
                    'label'=>'Full',
                    'class'=>'btn btn-default',
                    'itemsBefore'=>[
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
            ]),
        ],
    ]); ?>

</div>
