<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

$this->title = 'Employee Signup';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please fill out the following fields to signup:</p>

<div class="site-signup">

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Signup', [
                'class' => 'btn btn-success',
                'name' => 'signup-button'
            ]) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
