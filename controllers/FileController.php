<?php 

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\EmployeeFile;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class FileController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // logged in users only
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        
        $empId = Yii::$app->user->id;

       /*  $files = EmployeeFile::find()
            ->where(['emp_id' => $empId])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('/employee/files', [
            'files' => $files,
        ]); */

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeFile::find()->where(['emp_id' => $empId]),
            'pagination' => [
                'pageSize' => 10, // files per page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);        
        return $this->render('/employee/filesgrid', [
            'dataProvider' => $dataProvider,
        ]);
    }    

    
}

