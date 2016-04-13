<?php

/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property integer $shop_id
 * @property integer $category_id
 * @property integer $user_id
 * @property string $name
 * @property string $contact_no
 * @property string $description
 * @property string $address
 * @property integer $locality_id
 * @property string $latitude
 * @property string $langitude
 * @property integer $zip_code
 * @property integer $shop_image_id
 * @property integer $total_comments
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property AdsBanner $adsBanner
 * @property Comment[] $comments
 * @property Deal[] $deals
 * @property Discount[] $discounts
 * @property Product[] $products
 * @property Rating[] $ratings
 * @property Category $category
 * @property ShopImage $shopImage
 * @property Locality $locality
 * @property User $user
 * @property ShopImage[] $shopImages
 */
class Shop extends CActiveRecord
{
	public $category_search;
	/**
	 * @return string the associated database table name
	 */
	var $category_id_autocomplete = "";
	var $owner_id_autocomplete     = "";

	public function tableName()
	{
		return 'shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, user_id, owner_id, admin_id, locality_id, shop_image_id, total_comments', 'numerical', 'integerOnly'=>true),
			array('category_id, owner_id, description, name, address, city_id, state_id, locality_id, zip_code, active_status, ', 'required'),
			array('name', 'checkIfExists'),
			array('added_on, updated_on', 'numerical'),
			array('name', 'length', 'max'=>50,'min'=>5),
			array('latitude, longitude', 'length', 'max'=>50),
			// array('contact_no', 'match','pattern'=>'/^\+?(\(?[0-9]{3}\)?|[0-9]{3})[-\.\s]?[0-9]{3}[-\.\s]?[0-9]{4}$/'),
			array('contact_no', 'length', 'max'=>15,'min'=>7),
			array('zip_code', 'checkZipcode'),
			array('contact_no', 'checkContact'),
			array('zip_code', 'length', 'max'=>6,'min'=>6),
			array('mark_invalid, home_delivery, active_status, status', 'length', 'max'=>1),
			array('invalid_remarks', 'length', 'max'=>500),
			array('description, address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shop_id, category_id, user_id, name, contact_no, description, address, locality_id, latitude, longitude, zip_code, shop_image_id, total_comments ,mark_invalid, invalid_remarks, active_status, added_on, updated_on, status, category_search', 'safe', 'on'=>'search'),
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
			'adsBanner' => array(self::HAS_ONE, 'AdsBanner', 'ads_banner_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'shop_id'),
			'deals' => array(self::HAS_MANY, 'Deal', 'shop_id'),
			'discounts' => array(self::HAS_MANY, 'Discount', 'shop_id'),
			'products' => array(self::HAS_MANY, 'Product', 'shop_id'),
			'ratings' => array(self::HAS_MANY, 'Rating', 'shop_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'shopImage' => array(self::BELONGS_TO, 'ShopImage', 'shop_image_id'),
			'locality' => array(self::BELONGS_TO, 'Locality', 'locality_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'user' => array(self::BELONGS_TO, 'Owner', 'user_id'),
			'shopImages' => array(self::HAS_MANY, 'ShopImage', 'shop_id'),
			'owner' => array(self::BELONGS_TO, 'Owner', 'owner_id'),
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'rating_count' => array(self::STAT, 'Rating', 'shop_id','condition'=>'status="1" and active_status = "S"','group'=>'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shop_id' => 'Shop Id',
			'category_id' => 'Category',
			'user_id' => 'Owner',
			'owner_id' => 'Owner',
			'name' => 'Shop name',
			'contact_no' => 'Contact No',
			'description' => 'Description',
			'address' => 'Address',
			'locality_id' => 'Locality',
			'city_id' => 'City',
			'state_id' => 'State',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'zip_code' => 'Zip Code',
			'shop_image_id' => 'Shop Image',
			'total_comments' => 'Total Comments',
			'home_delivery' => 'Home Delivery',
			'active_status' => 'Active Status',
			'mark_invalid' => 'Mark Invalid?',
			'invalid_remarks' => 'Remarks for invalid details',
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
		$criteria->with = array( 'category','owner' );
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('`t`.category_id',$this->category_id);
		$criteria->compare('`t`.owner_id',$this->owner_id);
		$criteria->compare('`t`.name',$this->name,true);
		$criteria->compare('contact_no',$this->contact_no,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('locality_id',$this->locality_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('zip_code',$this->zip_code);
		$criteria->compare('shop_image_id',$this->shop_image_id);
		$criteria->compare('total_comments',$this->total_comments);
		$criteria->compare('home_delivery',$this->home_delivery,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('mark_invalid',$this->mark_invalid,true);
		$criteria->compare('invalid_remarks',$this->invalid_remarks,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'category_id'=>array(
						'asc'=>'category.category',
						'desc'=>'category.category desc',
					),
					
					'owner_id'=>array(
						'asc'=>'owner.name',
						'desc'=>'owner.name desc',
					),
					
					'*',
				),
				'defaultOrder'=>'shop_id DESC',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*Garima
	*@checkIfExists : validates if shop exists already in same locality and city
	*/
	public function checkIfExists($attribute,$params)
	{
		$name = $this->name;
		$shop_id = (int)$this->shop_id;
		$category_id = (int)$this->category_id;
		$locality_id = (int)$this->locality_id;
		$city_id = (int)$this->city_id;

		if(!empty($name) && !empty($category_id) && !empty($locality_id) && !empty($city_id))
		{
				$data = $this->findAll(array('condition'=>'status = "1" and category_id="'.$category_id.'" and locality_id="'.$locality_id.'" and city_id="'.$city_id.'" and  name like "'.$name.'" and shop_id!="'.$shop_id.'"'));

				if(!empty($data))
				{
					$this->addError('name','It seems that the shop exists already in selected locality.');
				}
		}
	}
	
	/*Rohan
	*@checkZipcode : validates enter zipcode valid or not
	*/
	public function checkZipcode($attribute,$params)
	{
		if($this->zip_code < 1 or strlen($this->zip_code) > 6)
		{
			$this->addError('zip_code','Invalid zip code');
			return false;
		}
		else
		{
			return true;
		}
	}
	/*Neha
	*@checkContact : validates enter contact valid or not
	*/
	public function checkContact($attribute,$params)
	{
		$str = $this->contact_no;
		if($str!='')
		{
			if(!preg_match('/^(NA|[0-9+-]+)$/',$str) || $str < 1)
			{
				$this->addError('contact_no','Invalid contact number');
				return false;
			}
			else
			{
				return true;
			}
		}else{
			return true;
		}
	}
	
}
