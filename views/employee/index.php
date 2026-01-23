<?php

use yii\grid\GridView;
use yii\helpers\Html;
//use yii\bootstrap\Alert;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;

use yii\web\JqueryAsset;
JqueryAsset::register($this);
use yii\web\View;

use yii\helpers\Url;

$this->registerCssFile(
    'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    ['position' => View::POS_HEAD]
);

$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

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
        //'id',
        [
            'attribute'=>'Photo',
            'format'=> 'raw',
            'value' => function ($model) {
                return Html::img(Yii::getAlias('@web/uploads/userphotos/' . $model->image),['width' => '50px']);
            },            
        ],
        'firstname',
        'lastname',
        'email',
        [
            'attribute' => 'dept_id',
            'value' => 'department.dept_name',
        ],
        'status',
        /* [
            'class' => 'yii\grid\ActionColumn'
        ],*/

        [
            'class' => ActionColumn::class,
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-eye"></i>',
                        $url,
                        [
                            'class' => 'btn btn-info btn-sm custom-view',
                            'title' => 'View',
                        ]
                    );
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-edit"></i>',
                        "javascript:void(0);",
                        [
                            'class' => 'btn btn-warning btn-sm custom-update',
                            'title' => 'Edit',
                            'data-id' => $model->id,
                            'onclick' => "
                            $('#employeeModal').modal('show')
                                .find('#modalContent')
                                .load('" . Url::to(['employee/update', 'id' => $model->id]) . "');
                            return false;
                        "
                        ]
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-trash"></i>',
                        $url,
                        [
                            'class' => 'btn btn-danger btn-sm custom-delete',
                            'title' => 'Delete',
                            'data' => [
                                'confirm' => 'Are you sure?',
                                'method' => 'post',
                            ],
                        ]
                    );
                },
            ],
        ],

           
    ],
]);


$this->registerJs("
    $(function () {
        
        toastr.options = {
            'closeButton': true,
            'progressBar': true,
            'positionClass': 'toast-top-right',
            'timeOut': false
        };

        $(document).on('click', '.custom-update', function () {
            let id = $(this).data('id');
            //alert('Edit clicked. ID = ' + id);
            // open edit modal here

            //$('#employeeModal').modal('show');

        });                

        $(document).on('click','.btn-save-empform',function(){
            //e.preventDefault();            
            alert('hhh');            
            var myForm = $('#frmEmp');
            //var formData = document.getElementById('frmEmp');
            var form = document.getElementById('frmEmp');
            var formData = new FormData(form);


            $.ajax({
                url: myForm.attr('action'),
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.status) {
                        //alert('success');
                        toastr.success('Employee saved successfully');
                        $('#employeeModal').modal('hide');
                    }else{
                        toastr.error('Failed to save. Something went wrong.');
                        $('#employeeModal').modal('hide');
                        //alert('failed');
                    }
                }
            });
            return false;
        });

    });
");

?>

<?php
    Modal::begin([
        'id' => 'employeeModal',
        'size' => Modal::SIZE_LARGE,
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
?>


