<?php

use yii\grid\GridView;
use yii\helpers\Html;
//use yii\bootstrap\Alert;
use yii\bootstrap5\Alert;

foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
    echo Alert::widget([
        'options' => ['class' => 'alert-' . $type],
        'body' => $message,
    ]);
}

//echo Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']);
echo Html::a('Create Employee', ['update?id='], ['class' => 'btn btn-success']);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'firstname',
        'lastname',
        'email',
        [
            'attribute' => 'dept_id',
            'value' => 'department.dept_name',
        ],
        'status',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);

