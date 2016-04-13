<?php

/**
 * This is the model class for table "shop_visit_details".
 *
 * The followings are the available columns in table 'shop_visit_details':
 * @property integer $shop_visit_id
 * @property integer $shop_id
 * @property integer $user_id
 * @property string $datetime
 * @property string $latitude
 * @property string $longitude
 * @property string $location
 * @property string $ip_address
 * @property string $other_details
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Shop $shop
 */
class ShopVisitDetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShopVisitDetails the static model class
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
		return 'shop_visit_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, datetime', 'required'),
			array('shop_id, user_id', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'length', 'max'=>50),
			array('location', 'length', 'max'=>100),
			array('ip_address', 'length', 'max'=>150),
			array('other_details', 'length', 'max'=>600),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shop_visit_id, shop_id, user_id, datetime, latitude, longitude, location, ip_address, other_details', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shop_visit_id' => 'Shop Visit',
			'shop_id' => 'Shop',
			'user_id' => 'User',
			'datetime' => 'Datetime',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'location' => 'Location',
			'ip_address' => 'Ip Address',
			'other_details' => 'Other Details',
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

		$criteria->compare('shop_visit_id',$this->shop_visit_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('other_details',$this->other_details,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'shop_visit_id DESC'
			 ),
		));
	}
}