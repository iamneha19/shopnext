<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $product_id
 * @property integer $product_category_id
 * @property integer $shop_id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property integer $product_image_id
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Discount[] $discounts
 * @property ProductCategory $productCategory
 * @property Shop $shop
 * @property ProductImage $productImage
 * @property ProductImage[] $productImages
 * @property Rating[] $ratings
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	var $product_category_id_autocomplete = "";
	var $shop_id_autocomplete     = "";
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_category_id, shop_id, name, active_status, price, added_on, updated_on', 'required'),
			array('product_category_id, shop_id, product_image_id, total_comments', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on, discount', 'numerical'),			
			array('name', 'checkIfExists'),
			array('price', 'validatePrice'),
			array('discount', 'validateDiscount'),
			array('name', 'length', 'max'=>50),
			array('description', 'length', 'max'=>200,'min'=>5),
			array('price, discount, discount_price', 'length', 'max'=>20,'min'=>1),
			// array('price', 'match', 'pattern'=>'/^[0-9]{1,12}(\.[0-9]{0,4})?$/'),
			array('discount_type, active_status, status, online', 'length', 'max'=>1),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, product_category_id, shop_id, name, description, price, discount_price, discount, discount_type, product_image_id, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
		);
	}
	
	public function validatePrice($attribute,$params)
	{
		if (preg_match("/^[0-9]{1,12}(\.[0-9]{0,4})?$/", $this->$attribute)) 
		{
			if ($this->$attribute<=0)
				$this->addError($attribute, 'Price (INR) has to be greater than 0');
		} else 
		{
			$this->addError($attribute, 'Invalid Price (INR) entered !');
		}
	}
	
	public function validateDiscount($attribute,$params)
	{
		$price = $this->price;
		if(!empty($this->$attribute) && $this->$attribute!="0.00")
		{	
			if (preg_match("/^[0-9]{1,12}(\.[0-9]{0,4})?$/", $this->$attribute)) 
			{
				if ($this->$attribute<=0)
				{
					$this->addError($attribute, 'Discount has to be greater than 0');
				}elseif(($price <= $this->discount) && $this->discount_type == 'R')
				{
					$this->addError($attribute, 'Invalid Discount entered !');
				}elseif(($this->discount > 90) && $this->discount_type == 'P')
				{
					$this->addError($attribute, 'Max discount limit is 90% !');
				}		
					
			} else 
			{
				$this->addError($attribute, 'Invalid Discount entered !');
			}
		}	
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'product_id'),
			'discounts' => array(self::HAS_MANY, 'Discount', 'product_id'),
			'productCategory' => array(self::BELONGS_TO, 'ProductCategory', 'product_category_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'productImage' => array(self::BELONGS_TO, 'ProductImage', 'product_image_id'),
			'productImages' => array(self::HAS_MANY, 'ProductImage', 'product_id'),
			'ratings' => array(self::HAS_MANY, 'Rating', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product Id',
			'product_category_id' => 'Product Category',
			'shop_id' => 'Shop',
			'name' => 'Product',
			'description' => 'Description',
			'price' => 'Price (INR)',
			'discount_price' => 'Discount Price',
			'discount' => 'Discount',
			'discount_type' => 'Discount Type',
			'online' => 'Available Online',
			'product_image_id' => 'Product Image',
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
		$criteria->with = array( 'productCategory','shop' );
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('`t`.product_category_id',$this->product_category_id);
		$criteria->compare('`t`.shop_id',$this->shop_id);
		$criteria->compare('`t`.name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('discount_price',$this->discount_price);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('discount_type',$this->discount_type,true);
		$criteria->compare('product_image_id',$this->product_image_id);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'product_category_id'=>array(
						'asc'=>'productCategory.product_category',
						'desc'=>'productCategory.product_category desc',
					),
					'shop_id'=>array(
						'asc'=>'shop.name',
						'desc'=>'shop.name desc',
					),
					'*',
				),
				'defaultOrder'=>'`t`.product_id DESC',
			),
		));
	}
	
	public function checkIfExists($attribute,$params)
	{
		$name = $this->name;
		$shop_id = (int)$this->shop_id;
		$product_category_id = (int)$this->product_category_id;
		$product_id = (int)$this->product_id;	
		
		if(!empty($shop_id) && !empty($product_category_id))
		{			
			if(!empty($name))
			{								
				$data = $this->findAll(array('condition'=>'shop_id="'.$shop_id.'" and status = "1" and name like "'.$name.'" and product_id!="'.$product_id.'"'));
				
				if(!empty($data))
				{
					$this->addError('name','This product exists already in selected shop.');
				}
			}
		}
	}
}