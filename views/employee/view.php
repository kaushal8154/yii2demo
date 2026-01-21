<?php 

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $model app\models\Employee */

$this->title = $model->firstname . ' ' . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> -->
        <!-- <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure?',
                'method' => 'post',
            ],
        ]) ?> -->
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uuid',
            [
                'label' => 'Department',
                'value' => $model->department->dept_name ?? 'N/A',
            ],
            'firstname',
            'lastname',
            'email:email',
            'gender',
            'phone',
            'address:ntext',
            [
                'attribute' => 'status',
                'value' => $model->status ? 'Active' : 'Inactive',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i A', strtotime($model->created_at)),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i A', strtotime($model->updated_at)),
            ],
        ],
    ]) ?>

</div>
