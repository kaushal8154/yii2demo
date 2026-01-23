<?php 

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class EmployeeController extends Controller
{
    public function actionIndex()
    {
        //echo Yii::$app->security->generatePasswordHash('123456');exit;

        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    

    public function actionSave($id=0){
        $postData = Yii::$app->request->post();
        //echo "<pre>";print_r($postData);exit;
        //die("here ".$id);         
        if(isset($postData['employee']['dept_id']) && $postData['employee']['dept_id'] > 0){
            $model = Employee::findOne($id);    
            if ($model->load($postData)) {

            $model->updated_at = Date('Y-m-d H:i:s');
            $saved = $model->save();
            if($saved){
                return $this->redirect(['index']);
            }       
        }

        }else{
            $model = new Employee();    
            if ($model->load($postData) && $model->save()) {
                return $this->redirect(['index']);
            }
        }
    }

    public function actionCreate()
    {
        $model = new Employee();
        $postData = Yii::$app->request->post();
        //echo "<pre>";print_r($postData);exit;

        if ($model->load($postData) && $model->save()) {
            return $this->redirect(['index']);
        }

        //echo "<pre>";print_r($model);exit;
        return $this->render('create', ['model' => $model,'empid'=>0]);
    }

    public function actionUpdate($id)
    {
        if($id> 0){
            $model = Employee::findOne($id);
        }else{
            $model = new Employee();
        }
        
        if (!$model) {
            throw new NotFoundHttpException();
        }
        
        //echo $id."<pre>";print_r($postData);exit;        
        /* if ($model->load($postData) && $model->save()) {
            return $this->redirect(['index']);
        } */

        if(Yii::$app->request->isPost){

            $postData = Yii::$app->request->post();    
            //$postData['image']='';
            if ($model->load($postData)) {

                /**  photo upload */
                $imageFile = UploadedFile::getInstance($model, 'imageFile');
                if($imageFile){
                    $fileName = time() . '.' . $imageFile->extension;
                    $imageFile->saveAs('uploads/userphotos/' . $fileName);
                    $model->image = $fileName;
                }
                
                /**  --------- */

                $model->updated_at = Date('Y-m-d H:i:s');
                $saved = $model->save();                
                if($saved){
                    //Yii::$app->session->setFlash('success', 'Saved successfully.');
                    //return $this->redirect(['index']);
                    //return "success";
                    return $this->asJson([
                        'status' => true,
                        'message' => 'Data saved successfully',
                        'data' => []
                    ]);

                }else{
                    $errors = $model->getFirstErrors();
                    return $this->asJson([
                        'status' => false,
                        'message' => 'Failed',
                        'data' => [
                            'errors'=>$errors,
                        ]
                    ]);
                }       
            }

        }        
        
        //return $this->render('update', ['model' => $model]);
        return $this->renderAjax('_form', ['model' => $model]);
    }

    public function actionUpdateOld($id)
    {
        $model = Employee::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException();
        }

        $postData = Yii::$app->request->post();        
        
        /* if ($model->load($postData) && $model->save()) {
            return $this->redirect(['index']);
        } */

        if ($model->load($postData)) {

            $model->updated_at = Date('Y-m-d H:i:s');
            $saved = $model->save();
            if($saved){
                return $this->redirect(['index']);
            }       
        }
        
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        Employee::findOne($id)->delete();
        //return $this->redirect(['index']);

        return $this->asJson([
            'status' => true,
            'message' => 'Deleted Successfully!',
            'data' => []
        ]);

    }

    public function actionView($id)
    {
        $model = Employee::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('Employee not found');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionUpdateStatus()
    {
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');

        $model = Employee::findOne($id);
        if ($model) {
            $model->status = $status;
            $model->save(false);
        }
    }
}

