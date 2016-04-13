<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $log_id
 * @property string $email
 * @property string $contact_no
 * @property integer $response_code
 * @property string $description
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 */
class Log extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('response_code, description, added_on, updated_on', 'required'),
			array('response_code', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('section, email', 'length', 'max'=>50),
			array('method_name, description', 'length', 'max'=>100),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('log_id, contact_no, response_code, description, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'log_id' => 'Log',
			'email' => 'Email',
			'contact_no' => 'Contact No',
			'method_name' => 'Method Name',
			'response_code' => 'Response Code',
			'description' => 'Description',
			'added_on' => 'Added On',
			'updated_on' => 'Updated On',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('contact_no',$this->contact_no,true);
		$criteria->compare('method_name',$this->method_name,true);
		$criteria->compare('response_code',$this->response_code);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'log_id DESC'
			 ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
