<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Files';
?>

<h2><?= Html::encode($this->title) ?></h2>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Employee Name',
            'value' => function ($model) {
                return $model->employee->username ?? '—';
            },
        ],
        'file_name',        
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d-m-Y'],
        ],
        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a(
                    'View',
                    Yii::getAlias('@web/uploads/employee_files/' . $model->file_name),
                    ['target' => '_blank', 'class' => 'btn btn-sm btn-primary']
                );
            }
        ],
        [
            'label' => 'Edit',
            'format' => 'raw',
            'value' => function ($model) {
                return (Yii::$app->user->can('editFile') && (Yii::$app->user->id ==  $model->emp_id) || Yii::$app->user->id ==  $model->employee->manager_id) ? Html::a(
                    'Edit',
                    'file/update?id='.$model->id,
                    ['target' => '_blank', 'class' => 'btn btn-sm btn-primary']
                ) : '';
            }
        ],
        
        /* [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        'Delete',
                        ['file/delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'data-confirm' => 'Are you sure?',
                            'data-method' => 'post',
                        ]
                    );
                },
            ],
        ], */
    ],
]); 

?>
