<?php

/**
 * This is the model class for table "location_search".
 *
 * The followings are the available columns in table 'location_search':
 * @property integer $id
 * @property string $geo_location
 * @property string $latitude
 * @property string $longitude
 * @property double $added_on
 * @property integer $added_by
 * @property string $other_details
 * @property string $status
 * @property string $active_status
 *
 * The followings are the available model relations:
 * @property User $addedBy
 */
class LocationSearch extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LocationSearch the static model class
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
		return 'location_search';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('geo_location, latitude, longitude', 'required'),
			array('id, added_by', 'numerical', 'integerOnly'=>true),
			array('added_on', 'numerical'),
			array('geo_location, other_details', 'length', 'max'=>100),
			array('latitude, longitude', 'length', 'max'=>50),
			array('status, active_status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, geo_location, latitude, longitude, added_on, added_by, other_details, status, active_status', 'safe', 'on'=>'search'),
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
			'addedBy' => array(self::BELONGS_TO, 'User', 'added_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'geo_location' => 'Geo Location',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'added_on' => 'Added On',
			'added_by' => 'Added By',
			'other_details' => 'Other Details',
			'status' => 'Status',
			'active_status' => 'Active Status',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('geo_location',$this->geo_location,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('added_by',$this->added_by);
		$criteria->compare('other_details',$this->other_details,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('active_status',$this->active_status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}