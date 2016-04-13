<?php
/**
 * This is the model class for table "deal".
 *
 * The followings are the available columns in table 'deal':
 * @property integer $deal_id
 * @property integer $shop_id
 * @property string $title
 * @property string $desc
 * @property string $code
 * @property double $start_date
 * @property double $end_date
 * @property double $amount
 * @property string $type
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Shop $shop
 */
class Deal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Deal the static model class
	 */
	
	var $shop_id_autocomplete = "";
	var $navigator_location_details = "";
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'deal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('shop_id, title, desc, start_date, end_date, amount, type,is_hot_deal', 'required'),
            array('amount', 'numerical', 'integerOnly'=>true),
			array('shop_id', 'numerical', 'integerOnly'=>true),
            array('code','unique'),
			array('start_date, end_date, amount, added_on, updated_on', 'numerical'),
			array('end_date','compare', 'compareAttribute'=>'start_date','operator'=>'>=','message'=>'End date should be greater than or equal to Start date!'),
			array('title, code, deal_image', 'length', 'max'=>100),
			array('type, active_status,status', 'length', 'max'=>1),
			array('desc,validity', 'safe'),
			array('is_hot_deal', 'length', 'max'=>3,'min'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('deal_id, shop_id, title, desc, code, start_date, end_date,deal_image, amount, type, active_status,owner_active_status,is_hot_deal, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'deal_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'deal_id' => 'Deal',
			'shop_id' => 'Shop',
			'title' => 'Title',
			'desc' => 'Description',
			'validity' =>'Validity',
			'code' => 'Code',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'amount' => 'Deal Amount',
			'type' => 'Amount Type',
			'is_hot_deal' => 'Hot Deal',
			'deal_image'  => 'Deal Image',
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
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$created_by = ApplicationSessions::run()->read('created_by');
		if(empty($created_by)){
			$user_id = null;
		}else{
			$user_id = $owner_id;
			$owner_id = null;
		}	
		$shop_ids = Controller::getOwnerShopsIds($owner_id,$user_id);
		if(!empty($shop_ids)){
			$shop_ids_string = implode(",",$shop_ids);
		}
		
		$criteria=new CDbCriteria;
		$criteria->with = array('shop' );
		$criteria->compare('deal_id',$this->deal_id);
		$criteria->compare('`t`.shop_id',$this->shop_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('start_date',$this->start_date);
		$criteria->compare('end_date',$this->end_date);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_hot_deal',$this->is_hot_deal,true);
		$criteria->compare('deal_image',$this->deal_image,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
   	   $criteria->AddCondition('`t`.status="1"');
	   	if(!empty($shop_ids_string)){
			$criteria->AddCondition('`t`.shop_id in('.$shop_ids_string.')');
		}else{
			$criteria->AddCondition('`t`.shop_id in("")');
            } 
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
				'attributes'=>array(
					'shop_id'=>array(
						'asc'=>'shop.name',
						'desc'=>'shop.name desc',
					),
					'*',
				),	
				'defaultOrder'=>'deal_id DESC'
				),
		));
	}
}