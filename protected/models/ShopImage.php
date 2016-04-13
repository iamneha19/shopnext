<?php

/**
 * This is the model class for table "shop_image".
 *
 * The followings are the available columns in table 'shop_image':
 * @property integer $shop_image_id
 * @property integer $shop_id
 * @property string $image
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Shop[] $shops
 * @property Shop $shop
 */
class ShopImage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shop_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('image', 'length', 'max'=>100),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shop_image_id, shop_id, image, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'shops' => array(self::HAS_MANY, 'Shop', 'shop_image_id'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shop_image_id' => 'Shop Image',
			'shop_id' => 'Shop',
			'image' => 'Image',
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

		$criteria->compare('shop_image_id',$this->shop_image_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
