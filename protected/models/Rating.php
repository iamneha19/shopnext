<?php

/**
 * This is the model class for table "rating".
 *
 * The followings are the available columns in table 'rating':
 * @property integer $rating_id
 * @property integer $shop_id
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $rating
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Shop $shop
 * @property Product $product
 * @property User $user
 */
class Rating extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Rating the static model class
	 */
        
         /**
          *
          * @var avgRate int to calculate average rating. 
          */
        public $avgRate;
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rating';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, product_id, user_id, rating', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('rating_id, shop_id, product_id, user_id, rating, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rating_id' => 'Rating',
			'shop_id' => 'Shop',
			'product_id' => 'Product',
			'user_id' => 'User',
			'rating' => 'Rating',
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

		$criteria->compare('rating_id',$this->rating_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'rating_id DESC'
			 ),
		));
	}
        
        public function afterSave()
        {        
            parent::afterSave();
            $shop_id = $this->shop_id;
            if($shop_id){
                $rating = Rating::model()->find(array('select'=>"AVG(rating) as avgRate",'condition'=>"shop_id=".$shop_id." and active_status='S' and status=1"));
                $avg = round($rating->avgRate);
                Shop::model()->updateByPk($shop_id,array('rating'=>$avg));
            }
            
        }
}