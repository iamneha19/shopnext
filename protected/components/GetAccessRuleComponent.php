<?php
/*
 * if you want to access this just use the below code in accessRules() function of any controller
 *     return Yii::app()->GetAccessRule->get();
 * */
class GetAccessRuleComponent extends CApplicationComponent
{
    public function init()
    {
    }

    public function get($arrViewAction = null, $arrUpdateAction = null)
    {
		$result_arr = array();
		$permission = ApplicationSessions::run()->read('role_permission');
		$access_arr = array();
		//var_dump($permission);exit;
		$controllerName = 	strtoupper(Yii::app()->controller->id);	
		
		
		if(!empty($permission[$controllerName]))
		{
			
			if(empty(Yii::app()->params['ALLOW_CONTROLLER_ACTION'][$controllerName]))
			{
				$result_arr = array(
					array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions'=>array_unique($permission[$controllerName]),
						'users'=>array('@'),
					),
					array('deny',  // deny all users
						'users'=>array('*'),
					),
				);
			}
			else 
			{
				$result_arr = array(
					array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions'=>array_merge(array_unique($permission[$controllerName]),Yii::app()->params['ALLOW_CONTROLLER_ACTION'][$controllerName]),
						'users'=>array('@'),
					),
					array('deny',  // deny all users
						'users'=>array('*'),
					),
				);
			}
			  
    	} 
    	else 
    	{
    		 $result_arr = array(
				array('deny',  // deny all users
					'users'=>array('*'),
				),
			);
    	}
    	return $result_arr;

    }
	
	public function getOwner($arrViewAction = null, $arrUpdateAction = null)
    {
		$result_arr = array();
		$permission = ApplicationSessions::run()->read('owner_role_permission');
		
		// $access_arr = array();
		//var_dump($permission);exit;
		$controllerName = 	strtoupper(Yii::app()->controller->id);	
		
		
		if(!empty($permission[$controllerName]))
		{
			
			if(empty(Yii::app()->params['ALLOW_CONTROLLER_ACTION'][$controllerName]))
			{
				$result_arr = array(
					array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions'=>array_unique($permission[$controllerName]),
						'users'=>array('@'),
					),
					array('deny',  // deny all users
						'users'=>array('*'),
					),
				);
			}
			else 
			{
				$result_arr = array(
					array('allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions'=>array_merge(array_unique($permission[$controllerName]),Yii::app()->params['ALLOW_CONTROLLER_ACTION'][$controllerName]),
						'users'=>array('@'),
					),
					array('deny',  // deny all users
						'users'=>array('*'),
					),
				);
			}
			  
    	} 
    	else 
    	{
    		 $result_arr = array(
				array('deny',  // deny all users
					'users'=>array('*'),
				),
			);
    	}
    	return $result_arr;

    }


}
