<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => Employee::class],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $employee = new Employee();
        $employee->username = $this->username;
        $employee->email = $this->email;
        $employee->setPassword($this->password);
        $employee->generateAuthKey();
        $employee->created_at = time();

        return $employee->save() ? $employee : null;
    }
}


