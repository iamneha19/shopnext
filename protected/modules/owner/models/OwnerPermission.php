<?php

/**
 * This is the model class for table "owner_permission".
 *
 * The followings are the available columns in table 'owner_permission':
 * @property integer $owner_permission_id
 * @property integer $owner_role_id
 * @property string $permission_name
 * @property string $all_permission
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 */
class OwnerPermission extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OwnerPermission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'owner_permission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_role_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('active_status, status', 'length', 'max'=>1),
			array('permission_name, all_permission', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('owner_permission_id, owner_role_id, permission_name, all_permission, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'owner_permission_id' => 'Owner Permission',
			'owner_role_id' => 'Owner Role',
			'permission_name' => 'Permission Name',
			'all_permission' => 'All Permission',
			'active_status' => 'Active Status',
			'added_on' => 'Added On',
			'updated_on' => 'Updated On',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('owner_permission_id',$this->owner_permission_id);
		$criteria->compare('owner_role_id',$this->owner_role_id);
		$criteria->compare('permission_name',$this->permission_name,true);
		$criteria->compare('all_permission',$this->all_permission,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}