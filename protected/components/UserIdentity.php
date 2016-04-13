<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
         
    const ERROR_STATUS_DELETED=5;
    const ERROR_STATUS_DEACTIVATED=6;
		
	public function frontendAuthenticate()
	{
                
		$user = User::model()->find(array('condition'=>'username="'.$this->username.'" and type="M" and status="1"'));
		
		if(empty($user))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($user->password!==md5($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		elseif($user->status == 0)
		{
			$this->errorCode=self::ERROR_STATUS_DELETED;
		}
		elseif($user->active_status == 'H')
		{
			$this->errorCode=self::ERROR_STATUS_DEACTIVATED;
		}
		else
		{
			if($user->profile_pic)
			{				
				if(filter_var($user->profile_pic, FILTER_VALIDATE_URL))
				{
					$profile_pic = $user->profile_pic;					
				}else
				{
					if(file_exists(Yii::app()->basePath."/../upload/user/".$user->profile_pic))
					{
						$profile_pic = Yii::app()->baseUrl.'/upload/user/'.$user->profile_pic;
					}	else	{
						 $profile_pic = Yii::app()->baseUrl."/themes/classic/img/default.png";
					}					
				}
			}else
			{
			   $profile_pic = Yii::app()->baseUrl."/upload/user/default.png";
			}
			ApplicationSessions::run()->write('user_id', $user->user_id);
            ApplicationSessions::run()->write('user_email', $user->email);
            ApplicationSessions::run()->write('username', $user->username);
            ApplicationSessions::run()->write('fullname', $user->name);
            ApplicationSessions::run()->write('profile_pic', $profile_pic);
			
			$this->errorCode=self::ERROR_NONE;
		}
			
		return !$this->errorCode;
	}
         
	public function authenticate()
	{
		$user = Admin::model()->find(array('condition'=>'username="'.$this->username.'"','order'=>'admin_id desc'));
		
		if(empty($user))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($user->username!=$this->username)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($user->password!==md5($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		elseif($user->status == 0)
		{
			$this->errorCode=self::ERROR_STATUS_DELETED;
		}elseif($user->active_status == 'H')
		{
			$this->errorCode=self::ERROR_STATUS_DEACTIVATED;
		}else
		{
			ApplicationSessions::run()->write('admin_id', $user->admin_id);
			ApplicationSessions::run()->write('admin_username', $user->username);
			ApplicationSessions::run()->write('admin_email', $user->email);
			ApplicationSessions::run()->write('admin_name', $user->name);
			ApplicationSessions::run()->write('admin_type', $user->role_id);
			ApplicationSessions::run()->write('role_name', $user->role->name);
			ApplicationSessions::run()->write('admin_pic',$user->profile_pic);
			ApplicationSessions::run()->write('type','admin');		
			
			$permission_data = Permission::model()->find(array('condition'=>'role_id='.$user->role_id));
			$permission = array();
	 		if(!empty($permission_data) && !empty($permission_data->permission_name))
			{
				$permission_data = explode(",",$permission_data->permission_name);
				
				foreach($permission_data as $val)
				{
					$controller = explode(".",$val);
					
					if(empty($pre_controller) || !in_array($pre_controller,$controller))
					{
						$i = 0;
						$pre_controller = $controller[0];
					}
					
					$permission[strtoupper($controller[0])][$i] = $controller[1];
					$i++;
				}
			}
			
			ApplicationSessions::run()->write('role_permission', $permission);
			
			ApplicationSessions::run()->write('menu_options',Controller::getSAMenus($permission));
			
			$this->errorCode=self::ERROR_NONE;
		}
			
		return !$this->errorCode;
	}
	/*
	*@ownerAuthenticate : Garima
	*/
	public function ownerAuthenticate()
	{       
		$owner = Owner::model()->find(array('condition'=>'username="'.$this->username.'"','order'=>'owner_id desc'));
		
		if(empty($owner))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($owner->username!=$this->username)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($owner->password!==md5($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		elseif($owner->status == 0)
		{
			$this->errorCode=self::ERROR_STATUS_DELETED;
		}elseif($owner->active_status == 'H')
		{
			$this->errorCode=self::ERROR_STATUS_DEACTIVATED;
		}else
		{
	
			/******** Logic to find top most parent(owner) of sub-owner  ****/
			$parent_id = '';
			if(!empty($owner->created_by))
			{
				$parent_id = $owner->created_by;
				do {
					$parent = Owner::model()->find(array('condition'=>'owner_id="'.$parent_id.'"'));
					$parent_id = $parent->created_by;
				} while (!empty($parent_id));
				
				$parent_id = $parent->owner_id;
			}
			/***** ****/
			ApplicationSessions::run()->write('owner_id', $owner->owner_id);
			ApplicationSessions::run()->write('created_by', $owner->created_by);
			ApplicationSessions::run()->write('parent_id', $parent_id);
			ApplicationSessions::run()->write('owner_username', $owner->username);
			ApplicationSessions::run()->write('owner_email', $owner->email);
			ApplicationSessions::run()->write('owner_fullname', $owner->name);
			ApplicationSessions::run()->write('owner_role_name', $owner->ownerRole->name);
			ApplicationSessions::run()->write('owner_pic',$owner->profile_pic);			
			ApplicationSessions::run()->write('type','owner');

			$owner_permission_data = OwnerPermission::model()->find(array('condition'=>'owner_role_id='.$owner->owner_role_id));
			$owner_permission = array();
	 		if(!empty($owner_permission_data) && !empty($owner_permission_data->permission_name))
			{
				$owner_permission_data = explode(",",$owner_permission_data->permission_name);
				
				foreach($owner_permission_data as $val)
				{
					$controller = explode(".",$val);
					
					if(empty($pre_controller) || !in_array($pre_controller,$controller))
					{
						$i = 0;
						$pre_controller = $controller[0];
					}
					
					$owner_permission[strtoupper($controller[0])][$i] = $controller[1];
					$i++;
				}
			}
			
			ApplicationSessions::run()->write('owner_role_permission', $owner_permission);
			ApplicationSessions::run()->write('owner_menu_options',Controller::getOwnerMenus());
			$this->errorCode=self::ERROR_NONE;
		}
			
		return !$this->errorCode;
	}
}