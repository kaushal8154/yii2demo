<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Department;


$form = ActiveForm::begin([
    'id'=> 'frmEmployeeFile',
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= $form->field($model, 'file_name')->fileInput(); ?>

<?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>


