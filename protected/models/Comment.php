<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $comment_id
 * @property integer $parent_id
 * @property integer $shop_id
 * @property integer $product_id
 * @property integer $deal_id
 * @property integer $user_id
 * @property string $comment
 * @property string $type
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Comment $parent
 * @property Comment[] $comments
 * @property Deal $deal
 * @property Product $product
 * @property Shop $shop
 * @property User $user
 */
class Comment extends CActiveRecord
{
	public $reply;
	public $rate;
	public $user_name;
	
	/**

	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, shop_id, product_id, deal_id, user_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('comment','required'),
			array('type, active_status, status', 'length', 'max'=>1),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('comment_id, parent_id, shop_id, product_id, deal_id, user_id, comment, type, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Comment', 'parent_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'parent_id'),
			'deal' => array(self::BELONGS_TO, 'Deal', 'deal_id'),
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
			'comment_id' => 'Comment Id',
			'parent_id' => 'Parent Comment',
			'shop_id' => 'Shop',
			'product_id' => 'Product',
			'deal_id' => 'Deal',
			'user_id' => 'User name',
			'comment' => 'Comment',
			'type' => 'Type',
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

		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('deal_id',$this->deal_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'comment_id DESC'
			 ),
		));
	}
	
	public function shopSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array( 'shop','user' );
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('`t`.shop_id',$this->shop_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('deal_id',$this->deal_id);
		$criteria->compare('`t`.user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1" and product_id is null and deal_id is null');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'shop_id'=>array(
						'asc'=>'shop.name',
						'desc'=>'shop.name desc',
					),
					
					'user_id'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name desc',
					),
					
					'*',
				),
				'defaultOrder'=>'comment_id DESC',
			 ),
		));
	}
	
	public function productSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('product','user');
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('`t`.product_id',$this->product_id);
		$criteria->compare('deal_id',$this->deal_id);
		$criteria->compare('`t`.user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1" AND `t`.product_id is not null and deal_id is null');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'product_id'=>array(
						'asc'=>'product.name',
						'desc'=>'product.name desc',
					),
					
					'user_id'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name desc',
					),
					
					'*',
				),
				'defaultOrder'=>'comment_id DESC'
			 ),
		));
	}
	
	public function dealSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array( 'deal','user' );
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('`t`.deal_id',$this->deal_id);
		$criteria->compare('`t`.user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1" AND `t`.deal_id is not null and product_id is null');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'deal_id'=>array(
						'asc'=>'deal.title',
						'desc'=>'deal.title desc',
					),
					
					'user_id'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name desc',
					),
					
					'*',
				),
				'defaultOrder'=>'comment_id DESC'
			 ),
		));
	}
        
	public function afterSave()
	{        
		parent::afterSave();

		if(!empty($this->shop_id))
		{
			$shop_id = $this->shop_id;
			if($shop_id && $this->parent_id == NULL ){
				$count = Comment::model()->count(array('condition'=>"shop_id=".$shop_id." and active_status='S' and status=1 and parent_id IS NULL"));
				Shop::model()->updateByPk($shop_id,array('total_comments'=>$count));
			}
		}

		if(!empty($this->deal_id))
		{
			$deal_id = $this->deal_id;
			
			if($deal_id && $this->parent_id == NULL ){
				$count = Comment::model()->count(array('condition'=>"deal_id=".$deal_id." and active_status='S' and status=1 and parent_id IS NULL"));
				Deal::model()->updateByPk($deal_id,array('total_comments'=>$count));
			}
		}

		if(!empty($this->product_id))
		{
			$product_id = $this->product_id;
			
			if($product_id && $this->parent_id == NULL ){
				$count = Comment::model()->count(array('condition'=>"product_id=".$product_id." and active_status='S' and status=1 and parent_id IS NULL"));
				Product::model()->updateByPk($product_id,array('total_comments'=>$count));
			}
		}
		
	}
}