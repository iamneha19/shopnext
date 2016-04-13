<?php

class RatingController extends ApiController
{	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array();
	}
	
	public function actionCreate()
	{
		$resp_code = $this->validateRequest();

		if($resp_code=='200')
		{
			$shop_id = $_REQUEST['shop_id'];
			$user_id = $_REQUEST['user_id'];
			$rating = $_REQUEST['rating'];
			
			$isUser = $this->isUserExist($user_id);
			$isShop = $this->isShopExist($shop_id);
			
			if(!$isUser || !$isShop )
			{
				if(!$isUser && !$isShop )
				{
					// If User and Shop does not exist. 
					$resp_code = $this->status_code['NOT_FOUND'];
					$data = array('message'=>'User and Shop does not exist');
					$resp = array('code'=>$resp_code,'data'=>$data);
				}
				elseif(!$isUser)
				{
					// If User does not exist. 
					$resp_code = $this->status_code['NOT_FOUND'];
					$data = array('message'=>'User does not exist');
					$resp = array('code'=>$resp_code,'data'=>$data);
				}
				else
				{
					// If Shop does not exist. 
					$resp_code = $this->status_code['NOT_FOUND'];
					$data = array('message'=>'Shop does not exist');
					$resp = array('code'=>$resp_code,'data'=>$data);
				}
			}
			else
			{
				$model = Rating::model()->findByAttributes(array('user_id'=>$user_id,'shop_id'=>$shop_id,'active_status'=>'S','status'=>1));
				if(!empty($model))
				{			
					$model->rating = $rating;
					if($model->save()){
						// If saved successfully.
						$resp = array('code'=>$resp_code);
					}else{
						// If saving process fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code);
					}		
					
				}
				else
				{
					$model=new Rating;
					$model->product_id = null;				
					$model->shop_id = $shop_id;			
					$model->user_id = $user_id;		
					$model->rating = $rating;			
					$model->added_on = time();
					$model->updated_on = time();
					if($model->save()){
						// If saved successfully.
						$resp = array('code'=>$resp_code);
					}else{
						// If saving process fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code);
					}
				}
			}
		}	
		else
		{
			 $resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}

	public function actionView()
	{
		$resp_code = $this->validateRequest();

		if($resp_code=='200')
		{
			$shop_id = $_REQUEST['shop_id'];
			$user_id = $_REQUEST['user_id'];
			
			$model = Rating::model()->findByAttributes(array('user_id'=>$user_id,'shop_id'=>$shop_id,'active_status'=>'S','status'=>1));
			
			if(!empty($model))
			{	
				$data = array('rating'=>$model->rating);
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else{
				$data = array('rating'=>0);
				$resp = array('code'=>$resp_code,'data'=>$data);
			}		
		}	
		else
		{
			 $resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}

	
}