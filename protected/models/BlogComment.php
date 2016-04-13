<?php

/**
 * This is the model class for table "blog_comment".
 *
 * The followings are the available columns in table 'blog_comment':
 * @property integer $blog_comment_id
 * @property integer $blog_id
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $comment
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Blog $blog
 * @property BlogComment $parent
 * @property BlogComment[] $blogComments
 * @property User $user
 */
class BlogComment extends CActiveRecord
{
	// public $parent_id_autocomplete = '';
	// public $blog_id_autocomplete = '';
	// public $comment_on = '';
	/**
	 * @return string the associated database table name
	 */

	public $reply;

	public function tableName()
	{
		return 'blog_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment','required'),			
			array('blog_id, user_id, parent_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('active_status, status', 'length', 'max'=>1),
			array('comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('blog_comment_id, blog_id, user_id, parent_id, comment, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'blog' => array(self::BELONGS_TO, 'Blog', 'blog_id'),
			'parent' => array(self::BELONGS_TO, 'BlogComment', 'parent_id'),
			'blogComments' => array(self::HAS_MANY, 'BlogComment', 'parent_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'blog_comment_id' => 'Blog Comment',
			'blog_id' => 'Blog',
			'user_id' => 'User',
			'parent_id' => 'Parent Comment',
			'comment' => 'Comment',
			'active_status' => 'Active Status',
			'added_on' => 'Added On',
			'updated_on' => 'Updated On',
			'status' => 'Status',
			'comment_on' => 'Comment on',
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
		$criteria->with = array( 'blog','user' );
		$criteria->compare('blog_comment_id',$this->blog_comment_id);
		$criteria->compare('`t`.blog_id',$this->blog_id);
		$criteria->compare('`t`.user_id',$this->user_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'blog_id'=>array(
						'asc'=>'blog.title',
						'desc'=>'blog.title desc',
					),
					
					'user_id'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name desc',
					),
					
					'*',
				),
				'defaultOrder'=>'blog_comment_id DESC'
			),
		));
	}
	
	public function afterSave()
	{
		parent::afterSave();
		$blog_id = $this->blog_id;
		$count = BlogComment::model()->count(array('condition'=>"blog_id=".$blog_id." and active_status='S' and status=1 and parent_id IS NULL"));
		Blog::model()->updateByPk($blog_id,array('total_comments'=>$count));
		
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BlogComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
