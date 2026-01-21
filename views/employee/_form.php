<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Department;

$form = ActiveForm::begin(
    [   
        'id'=>'frmEmp',
        //'action'=>Yii::$app->homeUrl.'employee/update?id='.$model->id
        //'action'=>Yii::$app->homeUrl.'employee/save'
        //'errorCssClass'=>'text-danger'
    ]
);

//echo $form->field($model, 'empid')->hiddenInput();
//echo $form->field($model, 'empid')->hiddenInput(['value'=> $empid])->label(false);
//echo $form->field($model, 'empid')->hiddenInput(['value'=>$model->id])->label(false);

echo $form->field($model, 'dept_id')->dropDownList(
    ArrayHelper::map(Department::find()->all(), 'id', 'dept_name'),
    ['prompt' => 'Select Department']
);

echo $form->field($model, 'firstname')->textInput();
echo $form->field($model, 'lastname')->textInput();
echo $form->field($model, 'email')->textInput();
echo $form->field($model, 'gender')->dropDownList([
    'male' => 'Male',
    'female' => 'Female',
    //'Other' => 'Other',
]);

echo $form->field($model, 'phone')->textInput();
echo $form->field($model, 'address')->textarea();
echo $form->field($model, 'status')->dropDownList(['active' => 'Active', 'inactive' => 'Inactive']);

echo Html::submitButton('Save', ['class' => 'btn btn-primary']);

ActiveForm::end();


