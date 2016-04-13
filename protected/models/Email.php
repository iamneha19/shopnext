<?php

/**
 * This is the model class for table "email".
 *
 * The followings are the available columns in table 'email':
 * @property integer $mail_id
 * @property integer $from_id
 * @property string $from_email
 * @property integer $to_id
 * @property string $to_email
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property User $to
 * @property User $from
 */
class Email extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Email the static model class
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
		return 'email';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_id, to_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('from_email, to_email', 'length', 'max'=>100),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('mail_id, from_id, from_email, to_id, to_email, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'to' => array(self::BELONGS_TO, 'User', 'to_id'),
			'from' => array(self::BELONGS_TO, 'User', 'from_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mail_id' => 'Mail',
			'from_id' => 'From',
			'from_email' => 'From Email',
			'to_id' => 'To',
			'to_email' => 'To Email',
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

		$criteria->compare('mail_id',$this->mail_id);
		$criteria->compare('from_id',$this->from_id);
		$criteria->compare('from_email',$this->from_email,true);
		$criteria->compare('to_id',$this->to_id);
		$criteria->compare('to_email',$this->to_email,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}