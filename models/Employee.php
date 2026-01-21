<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $dept_id
 * @property string $email
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $password
 * @property string|null $gender
 * @property string|null $phone
 * @property string|null $address
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Employee extends \yii\db\ActiveRecord
{

    public $empid;

    /**
     * ENUM field values
     */
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'firstname', 'lastname', 'password', 'gender', 'phone', 'address'], 'default', 'value' => null],
            [['dept_id'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 'active'],
            [['dept_id'], 'integer'],
            [['email'], 'required'],
            [['gender', 'address', 'status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid'], 'string', 'max' => 255],
            [['email', 'firstname', 'lastname', 'password'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 100],
            //['gender', 'in', 'range' => array_keys(self::optsGender())],
            //['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'dept_id' => 'Dept ID',
            'email' => 'Email',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'password' => 'Password',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'dept_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->uuid = Yii::$app->security->generateRandomString(36);
            $this->created_at = time();
        }

        $this->updated_at = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
}
