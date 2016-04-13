<?php

class ReviewController extends ApiController
{
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
			$model=new Comment;
			//Mandatory fields
			$model->comment=$_REQUEST['comment'];
			$model->user_id=$_REQUEST['user_id'];
			$model->type=$_REQUEST['review_type'];
			
			//Optional fields
			if(!empty($_REQUEST['parent_id']))
			{
				$model->parent_id=$_REQUEST['parent_id'];
			}
			
			if(!empty($_REQUEST['shop_id']))
			{
				$model->shop_id=$_REQUEST['shop_id'];
			}
			
			if(!empty($_REQUEST['product_id']))
			{
				$model->product_id=$_REQUEST['product_id'];
			}
			
			if(!empty($_REQUEST['deal_id']))
			{
				$model->deal_id=$_REQUEST['deal_id'];
			}
			
			$model->added_on = time();
			$model->updated_on = time();
			
			if ($model->validate()) {
				if($model->save()){
					// If saved successfully.
					$resp = array('code'=>$resp_code);
				}
				else{
					// If saving process fails. 
					$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
					$resp = array('code'=>$resp_code); 
				}
			}
			else
			{
				// If validation fails. 
				$resp_code = $this->status_code['BAD_REQUEST'];
				$resp = array('code'=>$resp_code); 
			}        
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
        
        /**
	 * List all review for particular shop.
	 * @param 
	 */
	public function actionList()
	{
        $resp_code = $this->validateRequest();
                
        if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			$criteria->select = "c.shop_id,c.product_id,c.deal_id,c.user_id,c.comment_id,c.comment,GROUP_CONCAT(r.user_id,':',r.comment_id,':',r.comment SEPARATOR '~') AS reply";
			$criteria->alias ='c';
			$criteria->join = "LEFT JOIN comment r ON c.comment_id=r.parent_id AND r.status = 1 AND r.active_status = 'S'";
			
			if(!empty($_REQUEST['shop_id']))
			{
				$criteria->condition = "c.parent_id IS NULL AND c.shop_id=".$_REQUEST['shop_id']." AND c.status = 1 AND c.active_status = 'S'";
			}
			
			if(!empty($_REQUEST['product_id']))
			{
				$criteria->condition = "c.parent_id IS NULL AND c.product_id=".$_REQUEST['product_id']." AND c.status = 1 AND c.active_status = 'S'";
			}
			if(!empty($_REQUEST['deal_id']))
			{
				$criteria->condition = "c.parent_id IS NULL AND c.deal_id=".$_REQUEST['deal_id']." AND c.status = 1 AND c.active_status = 'S'";
			}
			
			$criteria->group = 'c.comment_id';
			$comments =  Comment::model()->findAll($criteria);
			$i = 0;
			$data = array();
			if(!empty($comments))
			{
				foreach($comments as $val)
				{
					$comment_reply = array();
					
					if(!empty($val->reply))
					{
						$j = 0;
						$reply = explode("~",$val->reply);
						
						foreach($reply as $value)
						{
							$reply_arr = explode(":",$value);
							$user = User::model()->findByPk($reply_arr[0]);
							
							$comment_reply[$j]['user_id'] = $user->user_id;
							$comment_reply[$j]['name'] = $user->name;
							if(!empty($user->profile_pic)){
								  $comment_reply[$j]['image'] = $this->getApiUserImage($user->profile_pic);
							}else{
								  $comment_reply[$j]['image'] = Yii::app()->params['SERVER'].'upload/user/default.png';
							}
							$comment_reply[$j]['comment_id'] = $reply_arr[1];
							$comment_reply[$j]['comment'] = $reply_arr[2];
							$j++;
						}
					}
					
					$data[$i]['shop_id'] = $val->shop_id;
					$data[$i]['product_id'] = $val->product_id;
					$data[$i]['deal_id'] = $val->deal_id;
					$data[$i]['user_id'] = $val->user_id;
					$data[$i]['name'] = $val->user->name;
					if(!empty($val->user->profile_pic)){
						  $data[$i]['image'] = $this->getApiUserImage($val->user->profile_pic);
					}else{
						  $data[$i]['image'] = Yii::app()->params['SERVER'].'upload/user/default.png';
					}
					$data[$i]['comment_id'] = $val->comment_id;
					$data[$i]['comment'] = $val->comment;
					$data[$i]['reply'] = $comment_reply;
					
					$i++;
				}
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
			$resp = array('code'=>$resp_code,'data'=>$data);
			
        }
        else
		{
            $resp = array('code'=>$resp_code);
		}
                
        $this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
        
        /**
	 * Delete review.
	 * @param 
	 */
	public function actionDelete()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{    
			$comment = Comment::model()->find(array('condition'=>'active_status="S" and status="1" and comment_id ='.$_REQUEST['review_id'])); 
			if(!empty($comment)){
				$comment->status = 0;
				if($comment->save()){
						// If saved suucessfully.
						$resp = array('code'=>$resp_code);
				}else{
						// If savings fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code); 
					}
			}else{
				// Comment does not exists.
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}           
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
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