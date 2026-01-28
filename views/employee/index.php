<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
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

$this->registerCssFile(
    'https://cdn.jsdelivr.net/npm/bootstrap-toggle@2.2.2/css/bootstrap-toggle.min.css',
    ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]
);

$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/bootstrap-toggle@2.2.2/js/bootstrap-toggle.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
    echo Alert::widget([
        'options' => ['class' => 'alert-' . $type],
        'body' => $message,
    ]);
}

//echo Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']);
echo Html::a(
                'Create Employee', ['update?id='], 
                [
                    'class' => 'btn btn-success',
                    'data-id' => 0,
                    'onclick' => "$('#employeeModal').modal('show')
                                .find('#modalContent')
                                .load('" . Url::to(['employee/update', 'id' => 0]) . "');
                            return false;",
                ]
            );

Pjax::begin([
    'id' => 'employee-grid-pjax',
]);

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
        //'status',
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::checkbox('status', ($model->status == 'active' ? 1 : 0), [
                    'class' => 'toggle-status',
                    'data-id' => $model->id,
                    'data-toggle' => 'toggle',
                    'data-size' => 'small',
                    'data-on' => 'Active',
                    'data-off' => 'Inactive',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                ]);
            }
        ],
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
                            'onclick' => "$('#employeeModal').modal('show')
                                .find('#modalContent')
                                .load('" . Url::to(['employee/update', 'id' => $model->id]) . "');
                            return false;",
                        ]
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-trash"></i>',
                        //$url,
                        'javascript:void(0);',
                        [
                            'class' => 'btn btn-danger btn-sm custom-delete',
                            'title' => 'Delete',
                            'data' => [
                                //'confirm' => 'Are you sure?',
                                //'method' => 'post',
                                'title' => 'Delete',
                                'url' => $url,
                            ],
                        ]
                    );
                },
            ],
        ],

           
    ],
]);

Pjax::end();

$this->registerJs("
    $(function () {

        $(document).on('change', '.toggle-status', function () {
            var id = $(this).data('id');
            var status = $(this).prop('checked') ? 'active' : 'inactive';

            $.ajax({
                url: 'update-status',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    //console.log('Status updated');
                    toastr.success('Status updated');
                }
            });
        });
        
        toastr.options = {
            'closeButton': true,
            'progressBar': true,
            'positionClass': 'toast-top-right',
            'timeOut': 3000
        };

        $(document).on('click', '.custom-update', function () {
            let id = $(this).data('id');
            //alert('Edit clicked. ID = ' + id);
            // open edit modal here

            //$('#employeeModal').modal('show');

        });                

        $(document).on('click','.btn-save-empform',function(){
            //e.preventDefault();                        
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
                        $.pjax.reload({
                            container: '#employee-grid-pjax',
                            timeout: 2000
                        });
                        toastr.success('Employee saved successfully');
                        $('#employeeModal').modal('hide');
                    }else{
                        console.log(res.data.errors.email);
                        var error_message = Object.values(res.data.errors)[0];
                        toastr.error(error_message);
                        //$('#employeeModal').modal('hide');
                        //alert('failed');
                    }
                }
            });
            return false;
        });

        // after pjax reload
        $(document).on('pjax:end', function () {
            $('.toggle-status').bootstrapToggle();
        });


        var deleteUrl = '';

        $(document).on('click', '.custom-delete', function (e) {
            e.preventDefault();
            deleteUrl = $(this).data('url');
            $('#deleteModal').modal('show');
        });

        $('#confirmDelete').on('click', function () {
            if (deleteUrl !== '') {
                $.post(deleteUrl, function (res) {
                    //location.reload(); // or $.pjax.reload({container:'#pjax-grid'});
                    if (res.status) {
                        $('#deleteModal').modal('hide');
                        toastr.success(res.message);
                        $.pjax.reload({
                            container: '#employee-grid-pjax',
                            timeout: 2000
                        });
                    }

                    
                });
            }
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


    Modal::begin([
        'id' => 'deleteModal',
        //'header' => '<h4>Confirm Delete</h4>',
        'footer' => '
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
        ',
    ]);

    echo "<p>Are you sure you want to delete this record?</p>";

    Modal::end();
?>


