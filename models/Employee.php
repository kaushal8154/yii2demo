<?php

namespace app\models;

use Yii;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
//use app\models\EmployeeFile;

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
class Employee extends \yii\db\ActiveRecord implements IdentityInterface
{

    public $empid;
    public $imageFile;
    //public $image;

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
            [['image'], 'string', 'max' => 200],
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
            'image' => 'Image',
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

    /** Identity Interface */

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /* ===== Password helpers ===== */

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**  -------------------- */    

    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username,            
        ]);
    }

    /** ------------------------ */

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->uuid = Yii::$app->security->generateRandomString(36);
            $this->created_at = time();
        }

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }

        $this->updated_at = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }


    public function getFiles()
    {
        return $this->hasMany(EmployeeFile::class, ['emp_id' => 'id']);
    }

}
