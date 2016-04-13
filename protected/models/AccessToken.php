<?php

/**
 * This is the model class for table "access_token".
 *
 * The followings are the available columns in table 'access_token':
 * @property integer $access_token_id
 * @property string $client_id
 * @property string $client_secrete
 * @property integer $user_id
 * @property string $access_token
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 */
class AccessToken extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AccessToken the static model class
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
		return 'access_token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('client_id, client_secret', 'length', 'max'=>100),
			array('access_token', 'length', 'max'=>250),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('access_token_id, client_id, client_secrete, user_id, access_token, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'access_token_id' => 'Access Token',
			'client_id' => 'Client',
			'client_secret' => 'Client Secret',
			'user_id' => 'User',
			'access_token' => 'Access Token',
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

		$criteria->compare('access_token_id',$this->access_token_id);
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('client_secret',$this->client_secret,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}