<?php
	class BlogController extends Controller
    {
		 public function actionIndex()
		 {
			$this->render('index');
		 }
		 /* 
			*List of comments and reply on comments for blog.
			*@param integer $id the ID of the model to be view.
		 */
		public function actionBlogcommentsList($id=13)
		{
			$model = $this->loadModel($id);
			$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
			$criteria=new CDbCriteria;
			$criteria->select="b.user_id,b.blog_comment_id,b.comment,GROUP_CONCAT(c.user_id,':',c.blog_comment_id,':',c.comment SEPARATOR '~') AS reply";
			$criteria->alias="b";
			$criteria->limit=2;
			$criteria->join="LEFT OUTER JOIN blog_comment c ON b.blog_comment_id=c.parent_id AND c.active_status='S' AND c.status=1";
			$criteria->condition="b.blog_id=".$id." AND b.parent_id IS NULL AND b.active_status='S' AND b.status='1'";
			$criteria->group="b.blog_comment_id";
			$blog_comments = BlogComment::model()->findAll($criteria);
			$this->render('blogDetail',array(
				'model'=>$model,
				'blog_comments'=>$blog_comments,'limit'=>'2','offset'=>'0',
				'banners'=>$banners,
			));
		}
		/* 
			*Save comment for blog in database through AjaxCall.
		*/
		public function actionBlogcomments()
		{
			$return=false;
			if(isset($_POST) && !empty($this->user_id))
			 {
				 $p = new CHtmlPurifier();
						$p->options = array(
							'URI.AllowedSchemes'=>array(
								'http' => true, 
								'https' => true,
							),
							'HTML.Allowed' => 'p,a[href],b,i', 
						);
						$comment = $p->purify($_POST['comment']);
				
				$blog_model=new BlogComment;
				$blog_model->parent_id = null;
				$blog_model->blog_id = $_POST['blog_id'];			
				$blog_model->comment = $comment;
				$blog_model->user_id = $this->user_id;
				$blog_model->active_status = 'H';
				$blog_model->added_on = time();
				$blog_model->updated_on = time();
				if($blog_model->save())
					$return= true;
            }                
            print json_encode(array('success'=>$return));            
		}
		/* 
			*Save reply on comment for blog in database through AjaxCall.
		*/
		public function actionReplycomments()
		{
			$return=false;
			if(isset($_POST) && !empty($this->user_id))
			 {
				$p = new CHtmlPurifier();
                    $p->options = array(
                        'URI.AllowedSchemes'=>array(
                            'http' => true, 
                            'https' => true,
                        ),
                        'HTML.Allowed' => 'p,a[href],b,i', 
                    );
                    $comment = $p->purify($_POST['comment']);
				
				$blog_model=new BlogComment;
				$blog_model->parent_id = $_POST['blog_comment_id'];
				$blog_model->blog_id = $_POST['blog_id'];			
				$blog_model->comment = $comment;
				$blog_model->user_id = $this->user_id;	
				$blog_model->active_status = 'H';
				$blog_model->added_on = time();
				$blog_model->updated_on = time();
				if($blog_model->save())
					$return= true;
            }                
            print json_encode(array('success'=>$return));            
		}
		/* 
			*List of all blogs.
		*/
		public function actionBlogview()
		{
			$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
			$blogs = Blog::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'blog_id DESC','limit'=>4));
			$blogdataProvider =  new CActiveDataProvider('Blog',array('data'=>$blogs));
			$this->render('listBlog',array(
				'blogs'=>$blogdataProvider,'limit'=>'4','offset'=>'0',
				'banners'=>$banners,
			));
			
		}
		
		/* 
		This is the action to handle ajax request for deals pagination.
	*/
	public function actionAjaxBlogs()
	{
		$this->layout = false;	
		if(isset($_POST) && !empty($_POST))		
		{
			$offset = $_POST['pagination'];
			$blogs = Blog::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'blog_id DESC','limit'=>4,'offset'=>$offset));
			if(!empty($blogs))
			{
				$dataProvider =  new CActiveDataProvider('Blog',array('data'=>$blogs));
				$this->render('blog_page',array(
									'blogs'=>$dataProvider,
								));	
			}
		}
		echo "";	
	}
	
	/* 
	Neha
	*This is the action to handle ajax request for comments pagination.
	*/
	public function actionCommentList()
	{
		$return = "";
		$pagination = $_POST['pagination'];
		$blog_id = $_POST['blog_id'];
		$criteria=new CDbCriteria;
			$criteria->select="b.user_id,b.blog_comment_id,b.comment,GROUP_CONCAT(c.user_id,':',c.blog_comment_id,':',c.comment SEPARATOR '~') AS reply";
			$criteria->alias="b";
			$criteria->limit=2;
			$criteria->join="LEFT OUTER JOIN blog_comment c ON b.blog_comment_id=c.parent_id AND c.active_status='S' AND c.status=1";
			$criteria->condition="b.blog_id=".$blog_id." AND b.parent_id IS NULL AND b.active_status='S' AND b.status='1'";
			$criteria->group="b.blog_comment_id";
			$criteria->offset = $pagination;
			$blog_comments = BlogComment::model()->findAll($criteria);
		
		if(count($blog_comments>0))
		{
			
			
				// $return.='<div id="blog_reply">';
					foreach($blog_comments as $comments)
					{
					if($comments->parent_id=='')
					{ 
					  $return.='<div class="review_cont">
									<div class="sender_img">'.$this->getUserImage($comments->user,'64','64').'
										'.$comments->user->name.'
									</div>';
									$this->widget('ext.timeago.JTimeAgo', array(
													'selector' => ' .timeago',
												));
													
									$return.='<div class="time">
													<abbr class="timeago" title="'.date('Y-m-d h:i:s a',$comments->added_on).'"></abbr>
												</div>
									<p style=" font-weight: bold;">'.$comments->comment.'</p>
									<button type="button" name="reply" class="bt_reply" value="'.$comments->blog_comment_id.'">Reply</button>
									<div style="display:none;" id="reply_'.$comments->blog_comment_id.'">
										<textarea name="text_btn" class="reply_box" id="txt_'.$comments->blog_comment_id.'"></textarea>
										<button id="review-submit-btn-'.$comments->blog_comment_id.'" data-value="'.$comments->blog_comment_id.'" class="btn btn-warning reply_button">Post comment</button> 
									</div>
								</div>
								<div class="post_separator"></div>';
						if(!empty($comments->reply)) {
							$replies = explode("~",$comments->reply); 
							foreach($replies as $reply){
								$reply_data = explode(":",$reply); 
								$user = $this->getUserInfo($reply_data[0]);
								$return.='<div class="review_cont">
												<div class="sender_img">'.$this->getUserImage($user,'64','64').'
													'.$user->name.'
												</div>';
												$this->widget('ext.timeago.JTimeAgo', array(
																'selector' => ' .timeago',
												));
												$return.='<div class="time">
															<abbr class="timeago" title="'.date('Y-m-d h:i:s a',$reply->added_on).'"></abbr>
														</div>'
												.$reply_data[2].'
											</div>
								<div class="post_separator"></div>';
							} 
						} 
					} 
				}
			// $return.='</div>';
		}
		echo json_encode(array('data'=>$return));
		
	}
		
		/**
		 * Returns the data model based on the primary key given in the GET variable.
		 * If the data model is not found, an HTTP exception will be raised.
		 * @param integer $id the ID of the model to be loaded
		 * @return BlogComment the loaded model
		 * @throws CHttpException
	 */
		public function loadModel($id)
		{
			$model=Blog::model()->findByPk($id);
			if($model===null)
				throw new CHttpException(404,'The requested page does not exist.');
			return $model;
		}
	}
?>