<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property integer $admin_id
 * @property integer $role_id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $profile_pic
 * @property integer $created_by
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Admin $createdBy
 * @property Admin[] $admins
 * @property Role $role
 * @property Blog[] $blogs
 * @property Category[] $categories
 */
class Admin extends CActiveRecord
{
        public $new_password;
        public $old_password;
        public $confirm_password;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email,password,role_id,name', 'required'),
			array('new_password,old_password,confirm_password','required','on'=>'changepwd_scenario'),
			array('confirm_password', 'compare', 'compareAttribute'=>'new_password','on'=>'changepwd_scenario','message'=>'Repeat password must be repeated exactly!'),
			array('new_password,confirm_password', 'length', 'min'=>8,'max'=>20,'on'=>'changepwd_scenario'),
			array('old_password', 'checkOldPassword','on'=>'changepwd_scenario'),
			array('new_password', 'checkIfNew','on'=>'changepwd_scenario'),
			array('password', 'length', 'min'=>8),
			array('password', 'length', 'max'=>20,'on'=>'insert'),
			array('email','email'),
			array('email','unique','criteria'=>array('condition'=>'status="1" ')),
			array('name', 'match' ,'pattern'=>'/^[a-zA-Z ]*$/',
				 'message'=> 'Name can contain only alphabets!'),
			array('role_id, created_by', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('profile_pic','file', 'allowEmpty'=>true, 'types'=>'jpg, gif, png, jpeg', 'maxSize'=>1024 * 1024 * 2, 'tooLarge'=>'File has to be smaller than 2MB'),
			array('name, email, username, password, profile_pic', 'length', 'max'=>100),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('admin_id, role_id, name, email, username, password, profile_pic, created_by, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'createdBy' => array(self::BELONGS_TO, 'Admin', 'created_by'),
			'admins' => array(self::HAS_MANY, 'Admin', 'created_by'),
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
			'blogs' => array(self::HAS_MANY, 'Blog', 'admin_id'),
			'categories' => array(self::HAS_MANY, 'Category', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'admin_id' => 'Admin',
			'role_id' => 'Role',
			'name' => 'Name',
			'email' => 'Email',
			'username' => 'Username',
			'password' => 'Password',
			'profile_pic' => 'Profile Pic',
			'created_by' => 'Created By',
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
		$admin_id = ApplicationSessions::run()->read('admin_id');
		$criteria=new CDbCriteria;

		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('profile_pic',$this->profile_pic,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');
		$criteria->AddCondition('admin_id!='.$admin_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
				'defaultOrder'=>'admin_id DESC'
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function checkOldPassword($attribute,$params)
	{
		$old_password = $this->old_password;
		$admin_id = ApplicationSessions::run()->read('admin_id');
		
		if(!empty($old_password) && !empty($admin_id))
		{
			$data = $this->findByPK($admin_id);
			if(!empty($data) && $data->password!=md5($old_password))
			{
				$this->addError('old_password','Old password is invalid');
			}
		}
	}	
	public function checkIfNew($attribute,$params)
	{
		$new_password = $this->new_password;
		$old_password = $this->old_password;
		
		if(!empty($new_password) && !empty($old_password))
		{
			if($new_password==$old_password)
			{
				$this->addError('new_password','New password must not match old one.');
			}
		}
	}
}
