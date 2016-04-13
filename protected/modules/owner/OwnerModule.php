<?php

class OwnerModule extends CWebModule
{
	public function init()
	{
		Yii::app()->theme = "superadminTheme";
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'owner/default/error'),
            'user' => array(
                'class' => 'CWebUser',             
                'loginUrl' => Yii::app()->createUrl('owner/default/login'),
                'returnUrl' => Yii::app()->createUrl('owner/default/index'),
            ),
            
        ));

		// import the module-level models and components
		$this->setImport(array(
			'owner.models.*',
			'owner.components.*',
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
			
			$id = ApplicationSessions::run()->read('owner_id');
			
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			if (empty($id) && !in_array($route, $publicPages))
			{            
				Yii::app()->getModule('owner')->user->loginRequired();                
			}
			else
			{
				if(!empty($user_detail))
				{
					$user_detail = User::model()->findByPk($id);
					if(!in_array($route, $publicPages) && ($user_detail->status== 0 || $user_detail->active_status=='H') )
					{					
						ApplicationSessions::run()->delete('owner_id');
						ApplicationSessions::run()->delete('owner_username');
						ApplicationSessions::run()->delete('owner_email');
						ApplicationSessions::run()->delete('owner_fullname');
						ApplicationSessions::run()->delete('owner_pic');
						Yii::app()->getModule('owner')->user->loginRequired();     
					}else
					{		
						ApplicationSessions::run()->write('owner_id', $user_detail->user_id);
						ApplicationSessions::run()->write('owner_username', $user_detail->username);
						ApplicationSessions::run()->write('owner_email', $user_detail->email);
						ApplicationSessions::run()->write('owner_fullname', $user_detail->name);
						ApplicationSessions::run()->write('owner_pic',$user_detail->profile_pic);						
						return true;
					}
				}
				
				return true;
			}
		}
		else
			return false;
	}
}
