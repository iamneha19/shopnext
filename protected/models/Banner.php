<?php


class Banner extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Banner the static model class
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
		return 'banner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, location, active_status, added_on, updated_on', 'required'),
			array('banner_id','numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),			
			array('location', 'length', 'max'=>50),
			array('banner', 'length', 'max'=>100),
			array('banner', 'checkbanner'),
			array('code', 'checkbanner'),
			array('banner', 'safe'),
			array('type, active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('banner_id, banner, type, location, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'banner_id' => 'Banner Id',
			'banner' => 'Banner',
			'type' => 'Type',
			'location' => 'Location',
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

		$criteria->compare('banner_id',$this->banner_id);
		$criteria->compare('banner',$this->banner,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'banner_id DESC'
			 ),
		));
	}
	
	/*Amit
	*@checkIfExists : validate code or image respective to type(I=>image,C=>code) selection.
	*/
	public function checkBanner($attribute,$params)
	{
		$type = $this->type;
		$banner = $this->banner;
		$code = $this->code;
		
		if(!empty($type) && $type=='I' && empty($banner))
		{									
			
			$this->addError('banner','Image cannot be empty.');
			
		}
		
		if(!empty($type) && $type=='C' && empty($code))
		{									
			
			$this->addError('code','Code cannot be empty.');
			
		}
	}
	
	
}