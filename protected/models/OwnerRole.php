<?php

/**
 * This is the model class for table "owner_role".
 *
 * The followings are the available columns in table 'owner_role':
 * @property integer $owner_role_id
 * @property string $name
 * @property integer $created_by
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Owner $createdBy
 */
class OwnerRole extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ownerRole the static model class
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
		return 'owner_role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'unique', 'criteria'=>array('condition'=>'status="1"')),
			array('owner_role_id, created_by', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('name', 'length', 'max'=>50),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('owner_role_id, name, created_by, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'owner' => array(self::HAS_MANY, 'Owner', 'owner_role_id'),
			'createdBy' => array(self::BELONGS_TO, 'Owner', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'owner_role_id' => 'Owner Role',
			'name' => 'Name',
			'created_by' => 'Created By',
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

		$criteria->compare('owner_role_id',$this->owner_role_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');
		$criteria->AddCondition('name!="Default_Superadmin"');
              
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
				'defaultOrder'=>'owner_role_id DESC'
				),
		));
	}
}