<?php

/**
 * SiteLoginForm class.
 * SiteLoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SiteUserLogin extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;
	public $errorCode;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that Email Id and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// email and password are required
			array('email, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{       
            
		if(!$this->hasErrors())
		{
			$identity=new UserIdentity($this->email,$this->password);
			$identity->frontendAuthenticate();
			switch($identity->errorCode)
			{
				case UserIdentity::ERROR_STATUS_DELETED:
					$this->errorCode = UserIdentity::ERROR_STATUS_DELETED;
					break;
                case UserIdentity::ERROR_STATUS_DEACTIVATED:
					$this->errorCode = UserIdentity::ERROR_STATUS_DEACTIVATED;
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->errorCode = UserIdentity::ERROR_USERNAME_INVALID;
					break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$this->errorCode = UserIdentity::ERROR_PASSWORD_INVALID;
					break;
			}
		}
	}

	/**
	 * Logs in the user using the given email and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
                
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->frontendAuthenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
