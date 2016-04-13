<?php

class DealController extends ApiController
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
			//'postOnly + delete', // we only allow deletion via POST request
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
	/**
	 * List of all deals.
	 * @param 
	 */
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			if(!empty($_REQUEST['shop_id'])){
			   $criteria->compare('shop_id',$_REQUEST['shop_id']);
			}
			$criteria->AddCondition('active_status="S" and status=1');
			$deals = Deal::model()->findAll($criteria);
			if(!empty($deals)){
				
				foreach($deals as $key=>$deal)
				{
					$data[$key]		 			= $deal->attributes;
					$data[$key]['shop']			= $deal->shop->name;
					$data[$key]['added_on']		= $this->dateConvert($deal->added_on);
					$data[$key]['updated_on']	= $this->dateConvert($deal->updated_on);
					$data[$key]['start_date']	= $this->dobConvert($deal->start_date);
					$data[$key]['end_date']		= $this->dobConvert($deal->end_date);
					if(!empty($deal->deal_image)){
						$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/deal/'.$deal->deal_image;
					}else{
						$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/deal/default.png';
					}
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else if(Deal::model()->findAll(array('condition'=>'status="0" and shop_id ='.$_REQUEST['shop_id']))) 
			{
				// if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
	/**
	 * view deals.
	 * @param 
	 */
	public function actionView()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$deal_id = $_REQUEST['deal_id'];
			$deal = Deal::model()->find(array('condition'=>'active_status="S" and status="1" and deal_id ='.$deal_id)); 
		
			if(!empty($deal)){
				$data				 =	$deal->attributes;
				$data['shop'] 		 =  $deal->shop->name; 
				$data['added_on']	 =  $this->dateConvert($deal->added_on);
				$data['updated_on']	 =  $this->dateConvert($deal->updated_on);
				$data['start_date']  =  $this->dobConvert($deal->start_date);
				$data['end_date']	 =  $this->dobConvert($deal->end_date);
				if(!empty($deal->deal_image)){
					$data['image'] = Yii::app()->params['SERVER'].'upload/deal/'.$deal->deal_image;
				}else{
					$data['image'] = Yii::app()->params['SERVER'].'upload/deal/default.png';
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
				
			}else if(Deal::model()->find(array('condition'=>'status="0" and deal_id ='.$deal_id))) 
			{
				//if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
			}else{
				//if deal_id doesn't exist.
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
	    }else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
	  /**
	 * Create New Shop.
	 * @param 
	 */
	 public function actionCreate()
	 {
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$model = new Deal;
			////Mandetory Fields
			$model->shop_id		=	$_REQUEST['shop_id'];
			$model->title		=	$_REQUEST['title'];
			$model->desc		=	$_REQUEST['desc'];
			$model->code		=	$_REQUEST['code'];
			$model->start_date	=	strtotime($_REQUEST['start_date']);
			$model->end_date	=	strtotime($_REQUEST['end_date'].' 23:59:59');
			$model->amount		=	$_REQUEST['amount'];
			$model->type		=	$_REQUEST['deal_type'];
			$model->is_hot_deal	=	$_REQUEST['is_hot_deal'];
			
			$model->added_by	=	'O';
			$model->added_on	=	time();
			$model->updated_on	=	time();
			if ($model->validate()) {
				if($model->save()){
					// If saved suucessfully.
					if(!empty($_REQUEST['deal_image']))
					{
						$path = 'deal';
						$deal_image = $this->uploadPic($model->deal_id,$_REQUEST['deal_image'],$path);
					}
					if(!empty($deal_image))
					{
						$model->deal_image = $deal_image;
						$model->save();
					}
					$resp = array('code'=>$resp_code);
				}else{
					// If savings fails. 
					$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
					$resp = array('code'=>$resp_code); 
				}
			}else{
				 // If validation fails. 
				 $resp_code = $this->status_code['BAD_REQUEST'];
				 $resp = array('code'=>$resp_code); 
			}
			
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
	
	/* 
		Delete Deal
	*/
	public function actionDelete()
	{
		$resp_code = $this->validateRequest();
		if($resp_code == '200')
		{
			$deal_id = $_REQUEST['deal_id'];
			$model = Deal::Model()->find(array('condition'=>'active_status="S" and status="1" and deal_id='.$deal_id));
			if(!empty($model)){
				$model->status="0";
				if($model->save())
				{
					$resp = array('code'=>$resp_code);
				}else{
					//if save fails.
					$resp_code=$this->status_code['INTERNAL_SERVER_ERROR'];
					$resp = array('code'=>$resp_code);
				}
			}else{
				//if deal_id doesn't exist.
				$resp_code=$this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	/* 
		*Update deal
	*/
		public function actionUpdate()
		{
			$resp_code = $this->validateRequest();
			if($resp_code == '200')
			{
				$model = Deal::model()->find(array('condition'=>'active_status="S" and status="1" and deal_id ='.$_REQUEST['deal_id'])); 
				if(!empty($model)){
					$model->shop_id		=	$_REQUEST['shop_id'];
					$model->title		=	$_REQUEST['title'];
					$model->desc		=	$_REQUEST['desc'];
					$model->code		=	$_REQUEST['code'];
					$model->start_date	=	strtotime($_REQUEST['start_date']);
					$model->end_date	=	strtotime($_REQUEST['end_date'].' 23:59:59');
					$model->amount		=	$_REQUEST['amount'];
					$model->type		=	$_REQUEST['deal_type'];
					//Optional Fields
					if(!empty($_REQUEST['is_hot_deal']))
					{
						$model->is_hot_deal	=	$_REQUEST['is_hot_deal'];
					}
					$model->updated_on	=	time();
					if($model->validate()){
						if($model->save()){
							if(!empty($_REQUEST['deal_image']))
						{
							$path = 'deal';
							$old_pic = '';
							 if(!empty($model->deal_image))
							 {
								 $old_pic = $model->deal_image;
							 }
							$deal_image = $this->uploadPic($model->deal_id,$_REQUEST['deal_image'],$path,$old_pic);
						}
						 if(!empty($deal_image))
						 {
							$model->deal_image = $deal_image;
							$model->save();
						 }
						//saved successfully
							$resp= array('code'=>$resp_code);
						}else{
							///If saved fails
							$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
							$resp = array('code'=>$resp_code);
						}
					}else{
						//// If validations fails
						$resp_code = $this->status_code['BAD_REQUEST'];
						$resp = array('code'=>$resp_code);
					}
				}else{
					/// If shop_id doesn't exist
					$resp_code = $this->status_code['NOT_FOUND'];
					$resp = array('code'=>$resp_code);
				}
			}else{
				$resp = array('code'=>$resp_code);
			}
		 $this->apiResponse($resp,$this->type);
		 $this->writeLog($resp_code);
		}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$resp_code = $this->validateRequest();
		$this->apiResponse(array('code'=>$resp_code),$this->type,'E');
		$this->writeLog($resp_code,$this->action);
	}
}