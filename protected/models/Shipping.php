<?php

/**
 * This is the model class for table "shipping".
 *
 * The followings are the available columns in table 'shipping':
 * @property integer $shipping_id
 * @property string $order_no
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $mobile_no
 * @property string $address
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Shipping extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Shipping the static model class
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
		return 'shipping';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, mobile_no, address', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('email', 'email', 'message'=>'Email id is not valid'),
			array('order_no, name, email', 'length', 'max'=>50),
			array('mobile_no', 'length', 'max'=>15,'min'=>7),
			array('mobile_no', 'checkMobileNo'),
			array('status', 'length', 'max'=>1),
			array('address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shipping_id, order_no, user_id, name, email, mobile_no, address, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			// 'order' => array(self::HAS_MANY, 'Order', 'order_no'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shipping_id' => 'Shipping',
			'order_no' => 'Order No',
			'user_id' => 'User',
			'name' => 'Name',
			'email' => 'Email',
			'mobile_no' => 'Mobile No',
			'address' => 'Address',
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

		$criteria->compare('shipping_id',$this->shipping_id);
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile_no',$this->mobile_no,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*Amit
	*@checkMobileNo : validates entered mobile valid or not
	*/
	public function checkMobileNo($attribute,$params)
	{
		$str = $this->mobile_no;
	
		if(!preg_match('/^(NA|[0-9+-]+)$/',$str) || $str < 1 ) {
				$this->addError('mobile_no','Invalid Mobile Number');	
		}
	}
}