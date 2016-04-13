<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $order_id
 * @property string $order_no
 * @property integer $shop_id
 * @property integer $product_id
 * @property double $unit_price
 * @property integer $quantity
 * @property double $sub_total
 * @property integer $user_id
 * @property string $order_status
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Shop $shop
 * @property User $user
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Order the static model class
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
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, product_id, quantity, user_id', 'numerical', 'integerOnly'=>true),
			array('unit_price, sub_total, added_on, updated_on', 'numerical'),
			array('order_no', 'length', 'max'=>50),
			array('active_status, status', 'length', 'max'=>1),
			array('order_status','length','max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('order_id, order_no, shop_id, product_id, unit_price, quantity, sub_total, user_id, order_status, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order_id' => 'Order',
			'order_no' => 'Order No',
			'shop_id' => 'Shop',
			'product_id' => 'Product',
			'unit_price' => 'Unit Price',
			'quantity' => 'Quantity',
			'sub_total' => 'Sub Total',
			'user_id' => 'User',
			'order_status' => 'Order Status',
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
		if(!empty($shop_ids))
		{
			$shop_ids_string = implode(",",$shop_ids);
		}
		
		$criteria=new CDbCriteria;
		$criteria->with = array('user');
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('unit_price',$this->unit_price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('sub_total',$this->sub_total);
		$criteria->compare('`t`.user_id',$this->user_id);
		$criteria->compare('order_status',$this->order_status,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1" and order_status!="NC"');
		
		if(!empty($shop_ids_string))
		{
			$criteria->AddCondition('shop_id in ('.$shop_ids_string.')');
		}else{
			$criteria->AddCondition('shop_id in ("")');
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'user_id'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name desc',
					),
					'*',
				),	
				'defaultOrder'=>'order_id DESC',
			),
		));
	}
	public function getOptions($val)
	{
		$options = array(
				'P' => 'Pending',
				'PR' => 'Processing',
				'I' => 'InTransit',
				'C' => 'Complete',
				'NC'=>'Not Confirm',
		);
		
		return $options[$val];
	}
}