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
                    ]                                          
                ],
            ],
        ];        
    }

    public function actionIndex(){
               
    }

    public function actionSave(){
            
    }

}