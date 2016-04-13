<?php

class BlogCommentController extends ApiController
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
	/* 
		create blogcomment
	*/
	public function actionCreate()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$model = new BlogComment;
			//mendatory fields
			$model->blog_id = $_REQUEST['blog_id'];
			$model->user_id = $_REQUEST['user_id'];
			$model->comment = $_REQUEST['comment'];
			if(!empty($_REQUEST['parent_id']))
			{
				$model->parent_id = $_REQUEST['parent_id'];
			}
			$model->added_on = time();
			$model->updated_on = time();
			
			if($model->validate())
			{
				if($model->save())
				{	//if saved successfully
					$resp = array('code'=>$resp_code);
				}else{
					//if save fails.
					$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
					$resp = array('code'=>$resp_code);
				}
			}else{
				//if validate fails.
				
				$resp_code = $this->status_code['BAD_REQUEST'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
	
	/* 
		Delete BlogComment.
	*/
	 public function actionDelete()
	 {
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$blog_comment_id = $_REQUEST['blog_comment_id'];
			$model = BlogComment::model()->find(array('condition'=>'active_status = "S" and status = "1" and blog_comment_id='.$blog_comment_id));
			if(!empty($model))
			{
				$model->status = "0";
				if($model->save())
				{
					$resp= array('code'=>$resp_code);
				}else{
					//if save fails.
					$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
					$resp= array('code'=>$resp_code);
				}
			}else{
				//if id not exist.
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp= array('code'=>$resp_code);
			}
		}else{
			$resp= array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
	
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			$criteria->select = "c.blog_comment_id,c.blog_id,c.user_id,c.comment,GROUP_CONCAT(r.user_id,':',r.blog_comment_id,':',r.comment SEPARATOR '~') AS reply";
			$criteria->alias ='c';
			$criteria->join = "LEFT JOIN blog_comment r ON c.blog_comment_id=r.parent_id AND r.status = 1 AND r.active_status = 'S'";
			$criteria->group = 'c.blog_comment_id';
			
			if(!empty($_REQUEST['blog_id']))
			{
			   $criteria->condition = "c.parent_id IS NULL AND c.blog_id=".$_REQUEST['blog_id']." AND c.status = 1 AND c.active_status = 'S'";
			}
			
			$blogcomments = BlogComment::model()->findAll($criteria);
			$i = 0;
			$data = array();
			
			if(empty($_REQUEST['blog_id']))
			{
				$resp_code = $this->status_code['BAD_REQUEST'];
                $resp = array('code'=>$resp_code);
			}
			else
			{
				if(!empty($blogcomments))
				{
					foreach($blogcomments as $val)
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
								$comment_reply[$j]['blog_comment_id'] = $reply_arr[1];
								$comment_reply[$j]['comment'] = $reply_arr[2];
								$j++;
							}
						}
						
						$data[$i]['blog_id'] = $val->blog_id;
						$data[$i]['blog_comment_id'] = $val->blog_comment_id;
						$data[$i]['comment'] = $val->comment;
						$data[$i]['name'] = $val->user->name;
						if(!empty($val->user->profile_pic)){
							  $data[$i]['image'] = $this->getApiUserImage($val->user->profile_pic);
						}else{
							  $data[$i]['image'] = Yii::app()->params['SERVER'].'upload/user/default.png';
						}
						$data[$i]['reply'] = $comment_reply;
						
						$i++;
					}
				}else if(BlogComment::model()->findAll(array('condition'=>'status="0"and blog_id='.$_REQUEST['blog_id'])))
				{
					// if record is deleted.
					$resp_code = $this->status_code['RECORD_DELETED'];
					$resp = array('code'=>$resp_code);
				}else{
					$resp_code = $this->status_code['NOT_FOUND'];
					$resp = array('code'=>$resp_code);
				}
				
				$resp = array('code'=>$resp_code,'data'=>$data);
			}
			
		}
		else
		{	
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code,$this->action->Id);
	}
}