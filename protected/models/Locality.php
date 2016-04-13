<?php

/**
 * This is the model class for table "locality".
 *
 * The followings are the available columns in table 'locality':
 * @property integer $locality_id
 * @property integer $city_id
 * @property string $locality
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property City $city
 * @property Shop[] $shops
 * @property User[] $users
 */
class Locality extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $country_id = "";
	public $state_id = "";
	public function tableName()
	{
		return 'locality';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id,state_id,locality,city_id','required'),
			array('locality', 'checkIfExists'),
			//array('locality','unique','criteria'=>array('condition'=>'active_status="S" and status="1"')),
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('locality', 'length', 'max'=>100),
			array('active_status, status', 'length', 'max'=>1),
			 array('latitude, longitude', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('locality_id, city_id, locality', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'shops' => array(self::HAS_MANY, 'Shop', 'locality_id'),
			'users' => array(self::HAS_MANY, 'User', 'locality_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'locality_id' => 'Locality Id',
			'country_id' => 'Country',
			'state_id' => 'State',
			'city_id' => 'City',
			'locality' => 'Locality',
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
		$criteria->select='city_id,locality,added_on';
		$criteria->with = array('city');
		$criteria->together = true;
		$criteria->compare('locality_id',$this->locality_id);
		$criteria->compare('`t`.city_id',$this->city_id);
		$criteria->compare('locality',$this->locality,true);
		// $criteria->compare('active_status',$this->active_status,true);
		// $criteria->compare('added_on',$this->added_on);
        // $criteria->compare('latitude',$this->latitude,true);
        // $criteria->compare('longitude',$this->longitude,true);
		// $criteria->compare('updated_on',$this->updated_on);
		// $criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'city_id'=>array(
						'asc'=>'city.city',
						'desc'=>'city.city desc',
					),
					'*',
				),	
				'defaultOrder'=>'locality_id DESC'
			 ),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Locality the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*Garima
	*@checkIfExists : validates if locality exists already for selected city
	*/
	public function checkIfExists($attribute,$params)
	{
		$locality = $this->locality;
		$city_id = (int)$this->city_id;
		$locality_id = (int)$this->locality_id;
		
		if(!empty($locality) && !empty($city_id))
		{									
			$data = $this->findAll(array('condition'=>'status = "1" and city_id="'.$city_id.'" and locality="'.$locality.'" and locality_id!="'.$locality_id.'"'));
			
			if(!empty($data))
			{
				$this->addError('locality','This locality exists already for the selected city.');
			}
		}
	}
}
