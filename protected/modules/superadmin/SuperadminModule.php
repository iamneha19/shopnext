<?php

class SuperadminModule extends CWebModule
{
	public function init()
	{
		Yii::app()->theme = "superadminTheme";
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'superadmin/default/error'),
            'user' => array(
                'class' => 'CWebUser',             
                'loginUrl' => Yii::app()->createUrl('superadmin/default/login'),
                'returnUrl' => Yii::app()->createUrl('superadmin/default/index'),
            ),
            
        ));

		// import the module-level models and components
		$this->setImport(array(
			'superadmin.models.*',
			'superadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			//$plan_module = ApplicationSessions::run()->read('plan_module');
			$route = $controller->id . '/' . $action->id;
			
			$publicPages = array(
                            'default/login',
                            'default/logout',
                            'default/requestnewpassword'
                        );
			
			$id = ApplicationSessions::run()->read('admin_id');
			
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			if (empty($id) && !in_array($route, $publicPages))
			{            
				Yii::app()->getModule('superadmin')->user->loginRequired();                
			}
			else
			{       
				$admin_detail = Admin::model()->findByPk($id);
                if(!in_array($route, $publicPages) && ($admin_detail->status== 0 || $admin_detail->active_status=='H') )
				{
									
					ApplicationSessions::run()->delete('admin_id');
					ApplicationSessions::run()->delete('admin_username');
					ApplicationSessions::run()->delete('admin_email');
					ApplicationSessions::run()->delete('admin_name');
					ApplicationSessions::run()->delete('admin_type');
					ApplicationSessions::run()->delete('admin_pic');
					Yii::app()->getModule('superadmin')->user->loginRequired();     
				}else
				{		
					if(isset($admin_detail->role->permissions) && !empty($admin_detail->role->permissions) && count($admin_detail->role->permissions)==1)
					{						
						$permission_data = $admin_detail->role->permissions;		
						$permission = array();
						if(!empty($permission_data->permission_name))
						{
							ApplicationSessions::run()->delete('role_permission');
							ApplicationSessions::run()->delete('menu_options');
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
							ApplicationSessions::run()->write('role_permission', $permission);													
							ApplicationSessions::run()->write('menu_options',Controller::getSAMenus($permission));
						}
					}					
					return true;
				}
			}
		}
		else
			return false;
	}
}
