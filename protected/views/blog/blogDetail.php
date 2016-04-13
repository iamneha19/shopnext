<?php 
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 	
 ?>
<div id="wrapper">
	<div id="main_div">
		<div class="center"></div>
		<aside id="left_side">
		 <?php foreach($banners as $banner){ 
			if($banner->type == 'I')
			{
				$banner_img = $imagePath."/../upload/banner/".$banner->banner;
				if($banner->banner!='' && file_exists($banner_img)){
				?>
					<div class="local_ads"><img src="<?php echo $imageUrl."/upload/banner/".$banner->banner; ?>"/></div>
				<?php 
				}else
				{ ?>
					<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/></div>
				<?php 	
				} 
			}
			else 
			{ ?>
				<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=code+image"/></div>	
			<?php 
			} 
		 } 
		 ?>
			
			
		</aside>
<!-- ******************************************************************************************************** -->
		<aside id="center_div">
			<article class="reviews">
				<div class="col-lg-12">
					<div class="panel panel-default gradient">
						<div class="panel-heading">
							<h3> Blog details </h3>
						</div>
						<div class="panel-body noPad clearfix">
							<h4><p><?php echo $model->title; ?></p></h4>	
							<b><p>Blog Description :-</b> <?php echo $model->description; ?></p>		 
							<div class="add_review">
								<div class="prod_title">Comments</div>
								<div class="prod_subtitle">Write a comment</div>
								<div class="add_review_input">
									<textarea rows="6"  id="reply" name="text_btn"></textarea>
									<button id="review-submit-btn" class="btn btn-warning">Post comment</button> 
								</div>
							</div>
							<div class="all_reviews">
								<div class="prod_title">All Comments</div>
								<div class="prod_subtitle"><?php echo ($model->total_comments) ? $model->total_comments : 0; ?> +</div>
								<div id="blog_reply">
									<?php foreach($blog_comments as $comments){?>
										<?php if($comments->parent_id==''){ ?>
											<div class="review_cont">
												<div class="sender_img">
													<?php echo $this->getUserImage($comments->user,'64','64'); ?>
													<?php echo $comments->user->name; ?>
												</div>
												<?php
													$this->widget('ext.timeago.JTimeAgo', array(
														'selector' => ' .timeago',
													));
												?>
												
												<div class="time">
													<abbr class="timeago" title="<?php echo date('Y-m-d h:i:s a',$comments->added_on);  ?>"></abbr>
												</div>
												<p style=" font-weight: bold;"> <?php echo $comments->comment; ?> </p>
												<button type="button" name="reply" class="bt_reply" value="<?php echo  $comments->blog_comment_id;?>">Reply</button>
									 
												<div style="display:none;" id="reply_<?php echo  $comments->blog_comment_id;?>">
													<textarea name="text_btn" class="reply_box" id="txt_<?php echo $comments->blog_comment_id;?>"></textarea>
													<button id="review-submit-btn-<?php echo  $comments->blog_comment_id;?>" data-value="<?php echo $comments->blog_comment_id; ?>" class="btn btn-warning reply_button">Post comment</button> 
												</div>
											</div>
											<div class="post_separator"></div>
											<?php if(!empty($comments->reply)) {?>
												<?php $replies = explode("~",$comments->reply); ?>
												<?php foreach($replies as $reply){?>
													<?php $reply_data = explode(":",$reply); ?>
													<?php $user = $this->getUserInfo($reply_data[0]);?>
													<div class="review_cont">
														<div class="sender_img">
															<?php echo $this->getUserImage($user,'64','64'); ?>
															<?php echo $user->name; ?>
														</div>
													
													<?php
													$this->widget('ext.timeago.JTimeAgo', array(
														'selector' => ' .timeago',
													));
												?>
												<?//php echo date('Y-m-d h:i:s a',$comments->added_on); exit; ?>
												<div class="time">
													<abbr class="timeago" title="<?php echo date('d-m-Y h:i:s a',$comments->added_on);  ?>"></abbr>
												</div>
													<?php echo $reply_data[2]; ?></p>
												</div>
												<div class="post_separator"></div>
												<?php } ?>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
							<div id="append_more"></div>
						</div>
					</div>
				</div>
			</article>
			<?php if(count($blog_comments)>1){ ?>
				<div id="load_more_comments"><a href="javascript:void(0);">SEE ALL COMMENTS</a></div>
			<?php } ?>
			<span class ="load_more_msg"></span>
		</aside>
<!-- ******************************************************************************************************** -->

	<aside id="right_side">
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
	</aside>
	<div class="clr"></div>
	</div>
</div>
<!-- ** alert div ** -->
	<div class="alert_box review_alert">
		<div class="alert_title">Comments<div class="alert_close"></div></div>
		<div class="msg_div">
		  <p>Your comment has submitted successfully. Comment will be approved by Admin!</p>
		</div>
	</div>
<!-- *** -->
<script>
	$(document).ready(function(){
		blog_id = <?php echo $model->blog_id; ?>;
		$('#center_div').on('click','.bt_reply',function(){
		// $('.bt_reply').click(function(){
			<?php if($this->user_id==''){ ?>
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			<?php } else { ?>
				$('#reply_'+$(this).val()).toggle();
				<?php } ?>
			
		});
		$( "#reply" ).focus(function() {
			<?php if($this->user_id==''){ ?>
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			<?php } else { ?>
					return true;
				<?php } ?>
			});
		
			$('#review-submit-btn').on('click', function(){
				<?php if($this->user_id!=''){ ?>
					var comment =  $('#reply').val();
					if(comment == ''){
						$('#reply').attr('placeholder','Add your comment to post');
						$('#reply').click();
						$('#reply').focus();
						}else{
						$.ajax({
								type: "POST",
								url: '<?php echo Yii::app()->createUrl('blog/blogcomments') ?>',
								dataType: "json",
								data: {comment:comment,blog_id:blog_id},
								success: function(data)
								{    
									if(data.success){
										$('#reply').val('');
										$('#overlay').fadeIn();
										$('.review_alert').fadeIn();
										$('.review_alert').animate({top:160},300);
									}else{
									    alert("Something went wrong! Please enter valid data.");
										$('#reply').val('');
									}
								}
							});
						}  
				<?php }else{ ?>
					$('#overlay').fadeIn();
					$('#login_main').fadeIn();
					$('#login_main').animate({top:50},300);
				<?php } ?>    
				return false;
			}); 		
		
		// $('#blog_reply').on('keydown','.reply_box',function(e){
			// if(e.keyCode == 13) {
			$('#center_div').on('click','.reply_button',function(){
				<?php if($this->user_id!=''){ ?>
					
					var blog_comment_id = $(this).attr('data-value');
					var comment =  $('#txt_'+blog_comment_id).val();
					if(comment == ''){
						$('#txt_'+blog_comment_id).attr('placeholder','Add your comment to post');
						$('#txt_'+blog_comment_id).click();
					 }else {
						$.ajax({
								type: "POST",
								url: '<?php echo Yii::app()->createUrl('blog/replycomments') ?>',
								dataType: "json",
								data: {comment:comment,blog_id:blog_id,blog_comment_id:blog_comment_id},
								success: function(data)
								{    
									if(data.success){
										$('.reply_box').val('');
										$('#overlay').fadeIn();
										$('.review_alert').fadeIn();
										$('.review_alert').animate({top:160},300);
									}else{
									   alert("Something went wrong! Please enter valid data.");
											$('.reply_box').val('');
											
									}
								}
							});
						}  
				<?php }else{ ?>
					$('#overlay').fadeIn();
					$('#login_main').fadeIn();
					$('#login_main').animate({top:50},300);
				<?php } ?>    
				return false;
			      
		});
	});
</script>
<script>
var offset = '<?php echo $offset+$limit; ?>';
var limit = '<?php echo $limit; ?>';

	$('#center_div').on('click','#load_more_comments',function(){
	$('#load_more_comments').html("<a href = '#'> LOADING PLEASE WAIT....</a>").css('background','#50618E');
	$.ajax({
			type:"POST",
			url:site_url+"/blog/CommentList",
			data:{pagination:offset,blog_id:blog_id},
			success:function(result){
				var obj = jQuery.parseJSON( result );
				if(obj.data!='')
				{
					$('#append_more').append(obj.data);
					// $('#append_more').find('abbr.timeago').timeago();
					$('#load_more_comments').html('<a href="javascript:void(0);">LOAD MORE COMMENTS</a>').css('background','#1e3060');
					offset = parseInt(offset)+parseInt(limit);
				}else{
					$('#load_more_comments').hide();
					$('.load_more_msg').html("NO MORE COMMENTS TO DISPLAY....");				
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
					$('#center_div').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
					// window.location.reload();

				}
		});
	});
</script>