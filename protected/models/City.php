<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property integer $city_id
 * @property integer $state_id
 * @property string $city
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property State $state
 * @property Locality[] $localities
 * @property User[] $users
 */
class City extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $country_id = "";
	public function tableName()
	{
		return 'city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id,state_id,city','required'),
			array('city', 'match' ,'pattern'=>'/^[a-zA-Z ]*$/',
			'message'=> 'city can contain only alphabets.'),
			array('country_id,state_id', 'numerical', 'integerOnly'=>true),
			array('city', 'checkIfExists'),
			array('added_on, updated_on', 'numerical'),
			 array('city, latitude, longitude', 'length', 'max'=>50),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('city_id, state_id, city, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'localities' => array(self::HAS_MANY, 'Locality', 'city_id'),
			'users' => array(self::HAS_MANY, 'User', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'city_id' => 'City Id',
			'country_id' => 'Country',
			'state_id' => 'State',
			'city' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
			'active_status' => 'Active Status',
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
		$criteria->with = array('state');
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('`t`.state_id',$this->state_id);
		$criteria->compare('city',$this->city,true);
        $criteria->compare('latitude',$this->latitude,true);
        $criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');
		/*
		If user want to search with starting words only. Ex 'pu' for pune.
		*/
		// $criteria->addSearchCondition('city', $this->city.'%', false, 'AND');  

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'state_id'=>array(
						'asc'=>'state.state',
						'desc'=>'state.state desc',
					),
					'*',
				),	
				'defaultOrder'=>'city_id DESC'
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*Garima
	*@checkIfExists : validates if city exists already for selected state
	*/
	public function checkIfExists($attribute,$params)
	{
		$city = $this->city;
		$state_id = (int)$this->state_id;
		$city_id = (int)$this->city_id;
		
		if(!empty($city) && !empty($state_id))
		{									
			$data = $this->findAll(array('condition'=>'status = "1" and state_id="'.$state_id.'" and city="'.$city.'" and city_id!="'.$city_id.'"'));
			
			if(!empty($data))
			{
				$this->addError('city','This city exists already for the selected state.');
			}
		}
	}
}
