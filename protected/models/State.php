<?php

/**
 * This is the model class for table "state".
 *
 * The followings are the available columns in table 'state':
 * @property integer $state_id
 * @property integer $country_id
 * @property string $state
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property City[] $cities
 * @property Country $country
 * @property User[] $users
 */
class State extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state,country_id','required'),
			array('state', 'match' ,'pattern'=>'/^[a-zA-Z ]*$/',
			'message'=> 'State can contain only alphabets.'),
			array('country_id', 'numerical', 'integerOnly'=>true),
			array('state', 'checkIfExists'),
			array('added_on, updated_on', 'numerical'),
			array('state, latitude, longitude', 'length', 'max'=>50),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('state_id, country_id, state, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'cities' => array(self::HAS_MANY, 'City', 'state_id'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'users' => array(self::HAS_MANY, 'User', 'state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'state_id' => 'State Id',
			'country_id' => 'Country',
			'state' => 'State',
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
		$criteria->with = array('country');
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('`t`.country_id',$this->country_id);
		$criteria->compare('state',$this->state,true);
        $criteria->compare('latitude',$this->latitude,true);
        $criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'country_id'=>array(
						'asc'=>'country.country',
						'desc'=>'country.country desc',
					),
					'*',
				),	
				'defaultOrder'=>'state_id DESC'
			 ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return State the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*Garima
	*@checkIfExists : validates if state exists already for selected country
	*/
	public function checkIfExists($attribute,$params)
	{
		$state = $this->state;
		$country_id = (int)$this->country_id;
		$state_id = (int)$this->state_id;
		
		if(!empty($state) && !empty($country_id))
		{									
			$data = $this->findAll(array('condition'=>'status = "1" and country_id="'.$country_id.'" and state="'.$state.'" and state_id!="'.$state_id.'"'));
			
			if(!empty($data))
			{
				$this->addError('state','This state exists already for the selected country.');
			}
		}
	}
}
