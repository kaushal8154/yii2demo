<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int $emp_id
 * @property string|null $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class EmployeeFile extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    //const STATUS_ACTIVE = 'active';
    //const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename'], 'default', 'value' => null],
            [['emp_id'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 'active'],
            [['emp_id'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            //['status', 'in', 'range' => array_keys(self::optsStatus())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_id' => 'Emp ID',
            'filename' => 'Filename',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */


    // each file belongs to one employee
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }
   
}
