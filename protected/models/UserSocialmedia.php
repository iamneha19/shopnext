<?php

/**
 * This is the model class for table "user_socialmedia".
 *
 * The followings are the available columns in table 'user_socialmedia':
 * @property integer $user_socialmedia_id
 * @property integer $user_id
 * @property string $socialmedia_id
 * @property string $email_id
 * @property string $type
 * @property string $token
 * @property string $refresh_token
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserSocialmedia extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSocialmedia the static model class
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
		return 'user_socialmedia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('socialmedia_id, email_id', 'length', 'max'=>150),
			array('type', 'length', 'max'=>10),
			array('token, refresh_token', 'length', 'max'=>765),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_socialmedia_id, user_id, socialmedia_id, email_id, type, token, refresh_token, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_socialmedia_id' => 'User Socialmedia',
			'user_id' => 'User',
			'socialmedia_id' => 'Socialmedia',
			'email_id' => 'Email',
			'type' => 'Type',
			'token' => 'Token',
			'refresh_token' => 'Refresh Token',
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

		$criteria->compare('user_socialmedia_id',$this->user_socialmedia_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('socialmedia_id',$this->socialmedia_id,true);
		$criteria->compare('email_id',$this->email_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('refresh_token',$this->refresh_token,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'user_socialmedia_id DESC'
			 ),
		));
	}
}