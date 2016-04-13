<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property double $dob
 * @property string $contact_no
 * @property string $profile_pic
 * @property string $address
 * @property integer $locality_id
 * @property integer $city_id
 * @property integer $state_id
 * @property integer $country_id
 * @property string $type
 * @property string $username
 * @property string $password
 * @property string $send_newsletter
 * @property string $active_status
 * @property double $added_on
 * @property double $updated_on
 * @property string $status
 *
 * The followings are the available model relations:
 * @property BlogComment[] $blogComments
 * @property Comment[] $comments
 * @property Rating[] $ratings
 * @property Shop[] $shops
 * @property City $city
 * @property Country $country
 * @property Locality $locality
 * @property State $state
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public $old_password;
	public $new_password;
	public $repeat_password;
    public $iagree;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, name, password, repeat_password', 'required','on'=>'register'),
			array('name', 'required','on'=>'update_myprofile'),
			array('dob', 'checkDob','on'=>'update_myprofile'),
			array('name', 'match' ,'pattern'=>'/^[a-zA-Z ]*$/',
				 'message'=> 'Name can contain only alphabets!'),
			// array('iagree', 'required','on'=>'register','message'=>'In order to register, you must agree to our terms of services !'),
			array('repeat_password', 'compare', 'compareAttribute'=>'password','on'=>'register','message'=>'Repeat password must be repeated exactly!'),
			array('email','unique','on'=>'register','criteria'=>array('condition'=>'status="1"')),
			array('password', 'length', 'max'=>20,'min'=>8,'on'=>'register'),
			array('name', 'length', 'max'=>50,'min'=>5,'on'=>'register'),
			array('email', 'email', 'message'=>'Email id is not valid','on'=>'register'),
			array('username, email, name', 'required','on'=>'social_media_register'),
			array('locality_id, city_id, state_id, country_id', 'numerical', 'integerOnly'=>true),
			array('added_on, updated_on, last_login', 'numerical'),			
			array('username','unique','criteria'=>array('condition'=>'status="1"'),'on'=>'register'),
			//array('profile_pic', 'length', 'max'=>250),			
			array('name, username, password', 'length', 'max'=>50),			
			array('email', 'length', 'max'=>50),
			array('contact_no', 'length', 'max'=>15,'min'=>7),
			array('contact_no', 'checkContact'),
			array('type,gender, send_newsletter, active_status, status', 'length', 'max'=>1),
			array('address', 'safe'),
			array('old_password, new_password, repeat_password', 'required','on'=>'change_password_scene1'),
			array('new_password, repeat_password', 'required','on'=>'change_password_scene2'),
			array('repeat_password', 'compare', 'compareAttribute'=>'new_password','on'=>'change_password_scene1,change_password_scene2','message'=>'Repeat password must be repeated exactly!'),
			array('old_password, new_password, repeat_password', 'length', 'max'=>20,'min'=>8,'on'=>'change_password_scene1,change_password_scene2'),
			array('old_password','checkOldPassword','on'=>'change_password_scene1'),
			array('new_password','checkIfNew','on'=>'change_password_scene1'),
			array('username','checkIfExists','on'=>'forgotpassword'),
			array('username','required','on'=>'forgotpassword'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, name,gender, email, dob, contact_no, profile_pic, address, locality_id, city_id, state_id, country_id, type, username, password, send_newsletter, active_status, added_on, updated_on, status', 'safe', 'on'=>'search'),
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
			'blogComments' => array(self::HAS_MANY, 'BlogComment', 'user_id'),
			'socialMedia' => array(self::HAS_MANY, 'UserSocialmedia', 'user_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
			'ratings' => array(self::HAS_MANY, 'Rating', 'user_id'),
			'shops' => array(self::HAS_MANY, 'Shop', 'user_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'locality' => array(self::BELONGS_TO, 'Locality', 'locality_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'accesstoken' => array(self::BELONGS_TO, 'AccessToken', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User Id',
			'name' => 'Name',
			'email' => 'Email',
			'dob' => 'Date of birth',
			'contact_no' => 'Contact No',
			'profile_pic' => 'Profile Pic',
			'address' => 'Address',
			'locality_id' => 'Locality',
			'city_id' => 'City',
			'state_id' => 'State',
			'country_id' => 'Country',
			'type' => 'Type',
			'username' => 'Username',
			'password' => 'Password',
			'send_newsletter' => 'Send Newsletter?',
			'active_status' => 'Active Status',
			'added_on' => 'Added On',
			'updated_on' => 'Updated On',
			'iagree' => 'I agree',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('dob',$this->dob);
		$criteria->compare('contact_no',$this->contact_no,true);
		$criteria->compare('profile_pic',$this->profile_pic,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('locality_id',$this->locality_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('send_newsletter',$this->send_newsletter,true);
		$criteria->compare('active_status',$this->active_status,true);
		$criteria->compare('added_on',$this->added_on);
		$criteria->compare('updated_on',$this->updated_on);
		$criteria->compare('status',$this->status,true);
		$criteria->AddCondition('status="1"');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'user_id DESC',
				),
		));
	}
	
	public function checkOldPassword($attribute,$params)
	{
		$old_password = $this->old_password;
		$user_id = (int)$this->user_id;
		
		if(!empty($old_password) && !empty($user_id))
		{
				$data = $this->findByPK($user_id);
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
	public function checkIfExists($attribute,$params)
	{
		$username = $this->username;		
		if(!empty($username))
		{
			$data = $this->findByAttributes(array('username'=>$username),'status=1');
			if(empty($data))
			{
				$this->addError('username','Enter valid username.');
			}else{
				if($data->active_status=='H')
				{
					$this->addError('username','Couldn\'t process request.This user is blocked or deactivated for some reasons.');
				}
			}
		}
	}
	
	/*Amit
	*@checkDob : validates entered DOB
	*/
	public function checkDob($attribute,$params)
	{
		$dob = $this->dob;		
		if(!empty($dob))
		{
			if($dob > strtotime('-10 year'))
			{
				$this->addError('dob','Minimum age should be 10 Years.');
			}
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