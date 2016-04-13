<?php

/**
 * This is the model class for table "blog".
 *
 * The followings are the available columns in table 'blog':
 * @property integer $blog_id
 * @property string $title
 * @property string $description
 * @property integer $admin_id
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Admin $admin
 * @property BlogComment[] $blogComments
 */
class Blog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description','required'),
			array('title', 'unique', 'criteria'=>array('condition'=>'status="1" ')),
			array('title', 'match' ,'pattern'=>'/^[a-zA-Z0-9 ]*$/',
			'message'=> 'Special characters are not allowed!'),
			array('admin_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('title', 'length', 'max'=>100),
			array('active_status, status', 'length', 'max'=>1),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('blog_id, title, description, admin_id, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'blogComments' => array(self::HAS_MANY, 'BlogComment', 'blog_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'blog_id' => 'Blog',
			'title' => 'Title',
			'description' => 'Description',
			'admin_id' => 'Admin',
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
		$criteria->with = array( 'admin' );
		$criteria->compare('blog_id',$this->blog_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('`t`.admin_id',$this->admin_id);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('`t`.status="1"');
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'admin_id'=>array(
						'asc'=>'admin.name',
						'desc'=>'admin.name desc',
					),
					'*',
				),	
				'defaultOrder'=>'blog_id DESC'
			),
		));
	}
	

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Blog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
