<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // PERMISSIONS
        $viewFile = $auth->createPermission('viewFile');
        $viewFile->description = 'View own files';
        $auth->add($viewFile);

        $addFile = $auth->createPermission('addFile');
        $addFile->description = 'Add New file';
        $auth->add($addFile);

        $editFile = $auth->createPermission('editFile');
        $editFile->description = 'Update file';
        $auth->add($editFile);

        $downloadFile = $auth->createPermission('downloadFile');
        $downloadFile->description = 'Download file';
        $auth->add($downloadFile);

        /* $updateQty = $auth->createPermission('updateQty');
        $updateQty->description = 'Update Quantity';
        $auth->add($updateQty); */        

        // ROLES
        $employee = $auth->createRole('employee');
        $auth->add($employee);
        $auth->addChild($employee, $viewFile);
        $auth->addChild($employee, $downloadFile);

        $admin = $auth->createRole('manager');
        $auth->add($admin);
        $auth->addChild($admin, $addFile);        
        $auth->addChild($admin, $editFile);        

        echo "Employee RBAC initialized\n";
    }
}



