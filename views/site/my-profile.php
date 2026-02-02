<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

$this->title = 'Employee Signup';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please fill out the following fields to signup:</p>

<div class="site-signup">

    <?php $form = ActiveForm::begin(['id' => 'form-profile','action'=>Url::to(['site/profile']),'method'=>'post']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'disabled' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['disabled' => true]) ?>

        <?= $form->field($model, 'firstname')->textInput() ?>

        <?= $form->field($model, 'lastname')->textInput() ?>
                

        <div class="form-group">
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-success',
                'name' => 'signup-button'
            ]) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
