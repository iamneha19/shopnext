<?php

/**
 * This is the model class for table "owner".
 *
 * The followings are the available columns in table 'owner':
 * @property integer $owner_id
 * @property integer $owner_role_id
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
 * @property OwnerRole $ownerRole
 */
class Owner extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Owner the static model class
	 */
		
		public $new_password;
        public $old_password;
        public $confirm_password;
		
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'owner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_role_id,name,email,password','required'),
			array('new_password,old_password,confirm_password','required','on'=>'changepwd_scenario'),
			array('confirm_password', 'compare', 'compareAttribute'=>'new_password','on'=>'changepwd_scenario','message'=>'Repeat password must be repeated exactly!'),
			array('new_password,confirm_password', 'length', 'min'=>8,'max'=>20,'on'=>'changepwd_scenario'),
			array('old_password', 'checkOldPassword','on'=>'changepwd_scenario'),
			array('new_password', 'checkIfNew','on'=>'changepwd_scenario'),
			array('password', 'length', 'min'=>8),
			array('password', 'length', 'max'=>20,'on'=>'insert'),
			array('owner_id, owner_role_id, created_by', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on', 'numerical'),
			array('profile_pic','file', 'allowEmpty'=>true, 'types'=>'jpg, gif, png, jpeg', 'maxSize'=>1024 * 1024 * 2, 'tooLarge'=>'File has to be smaller than 2MB'),
			array('name, email, username, password, profile_pic', 'length', 'max'=>100),
			array('active_status, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('owner_id, owner_role_id, name, email, username, password, profile_pic, created_by, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'createdBy' => array(self::BELONGS_TO, 'Owner', 'created_by'),
			'ownerRole' => array(self::BELONGS_TO, 'OwnerRole', 'owner_role_id'),
			'ownerRoles' => array(self::HAS_MANY, 'OwnerRole', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'owner_id' => 'Owner Id',
			'owner_role_id' => 'Owner Role',
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
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		// $owner_id = ApplicationSessions::run()->read('owner_id');
		$criteria=new CDbCriteria;
		$criteria->with = array('ownerRole');
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('`t`.owner_role_id',$this->owner_role_id);
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
		$criteria->AddCondition('`t`.status="1"');
		// $criteria->AddCondition('owner_id!='.$owner_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'owner_role_id'=>array(
						'asc'=>'ownerRole.name',
						'desc'=>'ownerRole.name desc',
					),
					'*',
				),	
				'defaultOrder'=>'owner_id DESC'
			),
		));
	}
	public function checkOldPassword($attribute,$params)
	{
		$old_password = $this->old_password;
		$owner_id = ApplicationSessions::run()->read('owner_id');
		
		if(!empty($old_password) && !empty($owner_id))
		{
			$data = $this->findByPK($owner_id);
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