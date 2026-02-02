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
                    /* [
                        'allow' => true,
                        'roles' => ['@'], // logged in users only
                    ] */                      
                    [
                        'allow' => true,
                        'actions' => ['create', 'update'],
                        //'roles' => ['manager'],                        
                    ],                                        
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],                        
                    ],                                        
                ],
            ],
        ];
    }

    public function actionIndex()
    {        
        $empId = Yii::$app->user->id;        

        if(Yii::$app->user->can('manager')){
            $quriedData = EmployeeFile::find()->joinWith('employee')->where(['employee.manager_id'=>$empId])->orWhere(['employee.id' => $empId]);
        }else if(Yii::$app->user->can('employee')){
            $quriedData = EmployeeFile::find()->joinWith('employee')->where(['files.emp_id'=>$empId]);
        }else{
            $quriedData = EmployeeFile::find()->joinWith('employee')->where(['files.emp_id'=>$empId]);
        }
                
        $dataProvider = new ActiveDataProvider([
            'query' => $quriedData,
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
            
    public function actionDownload($filename)
    {
        $filePath = Yii::getAlias('@webroot/uploads/' . $filename);

        if (!file_exists($filePath)) {
            throw new \yii\web\NotFoundHttpException('File not found');
        }

        return Yii::$app->response->sendFile($filePath);
    }

    public function actionCreate()
    {                            
        $model = new EmployeeFile();          

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file_name');

            if ($model->validate()) {
                $newFileName = time() . '_' . $model->file->baseName . '.' . $model->file->extension;
                $model->file->saveAs(
                    Yii::getAlias('@webroot/uploads/employee_files/') . $newFileName
                );

                // save filename to DB                
                $model->emp_id = Yii::$app->user->id;
                //$model->filename = '';
                $model->file_name = $newFileName;                
                //$model->save();
                if (!$model->save()) {
                    print_r($model->errors);
                    exit;
                }
                //echo "<pre>";print_r($model);echo "</pre>";exit;

                Yii::$app->session->setFlash('success', 'File uploaded successfully!');
                return $this->refresh();
            }
        } 

        //echo "<pre>";print_r($model);exit;
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = EmployeeFile::findOne($id);
        if($model->emp_id != Yii::$app->user->id){ 
            //return $this->goHome();                              
            return $this->redirect(['/file']);
        }

        if (!$model) {
            throw new NotFoundHttpException();
        }

        //$postData = Yii::$app->request->post();                
        /* if ($model->load($postData) && $model->save()) {
            return $this->redirect(['index']);
        } */

        if(Yii::$app->request->isPost){
            if ($model->validate()) {                 
                    $old_file =   $model->file_name;
                    $model->file = UploadedFile::getInstance($model, 'file_name');                    
                    //$old_file = '1769686539_sample-2.pdf';
                    if($old_file != null && trim($old_file) != ''){
                        $filePath = Yii::getAlias('@webroot/uploads/employee_files/'.$old_file);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }   
                    }                    
                    $newFileName = time() . '_' . $model->file->baseName . '.' . $model->file->extension;
                    $model->file->saveAs(
                        Yii::getAlias('@webroot/uploads/employee_files/') . $newFileName
                    );
                    $model->file_name = $newFileName;
                    $model->save();

                    Yii::$app->session->setFlash('success', 'File uploaded successfully!');
                    return $this->redirect(['/file']);
            }            
        }            
        
        return $this->render('update', ['model' => $model]);
    }

    
}

