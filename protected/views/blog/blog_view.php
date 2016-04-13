<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h3> Blog details </h3>
		</div>
		<div class="panel-body noPad clearfix">
			<h4><p><?php echo $model->title; ?></p></h4>	
			<b><p>Blog Description :-</b> <?php echo $model->description; ?></p>		 
			<b><p>Blog Comment :-</b>
				<div class="comment">
					<textarea id="reply" name="abc"></textarea>
					<input type="submit" id="bt_submit" value="submit">
				</div>
				<div id="blog_reply">
					<?php foreach($blog_comments as $comments){?>
						<?php if($comments->parent_id==''){ ?>
							<p><div class="user-name" style="float: left;"><?php echo $comments->user->name; ?>: </div></p>
                            <div class="user-pic"><?php echo $this->getUserImage($comments->user); ?></div>
							<p style=" font-weight: bold;"> <?php echo $comments->comment; ?> </p>
							<button type="button" name="reply" class="bt_reply" value="<?php echo  $comments->blog_comment_id;?>">Reply</button>
							 
							<div style="display:none;" id="reply_<?php echo  $comments->blog_comment_id;?>">
								<textarea name="abc" class="reply_box" id="txt_<?php echo $comments->blog_comment_id;?>"></textarea>
								<input type="submit" id="bt_submit_<?php echo  $comments->blog_comment_id;?>" data-value="<?php echo $comments->blog_comment_id; ?>"name="submit" class="reply_button" value="submit">
							</div>
							<?php if(!empty($comments->reply)) {?>
								<?php $replies = explode("~",$comments->reply); ?>
								<?php foreach($replies as $reply){?>
									<?php $reply_data = explode(":",$reply); ?>
									<?php $user = $this->getUserInfo($reply_data[0]);?>
									<p><div class="user-info" style="float: left;"><?php echo $user->name; ?>:</div></p>
									<div class="user-pic"><?php echo $this->getUserImage($user); ?></div>
									<p style=" margin-left: 1cm";><?php echo $reply_data[2]; ?></p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
			</p>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		blog_id = <?php echo $model->blog_id; ?>;
		$('.bt_reply').click(function(){
			<?php if($this->user_id==''){ ?>
				loadModal("login","Please login to add comment");
			<?php } else { ?>
				$('#reply_'+$(this).val()).toggle();
				<?php } ?>
			
		});
		$( "#reply" ).focus(function() {
			<?php if($this->user_id==''){ ?>
				loadModal("login","Please login to add comment");
			<?php } else { ?>
					return true;
				<?php } ?>
			});
		
			$('#bt_submit').click(function(){
				<?php if($this->user_id!=''){ ?>
					var comment =  $('#reply').val();
					if(comment == ''){
						alert("Comment cannot be blank!");
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
										alert('Comment will be approved by Admin!.');    
									}else{
									    alert("Something went wrong! Please enter valid data.");
										$('#reply').val('');
									}
								}
							});
						}  
				<?php }else{ ?>
					loadModal("login","Please login to add comment");
				<?php } ?>    
				return false;
			}); 		
		
		// $('#blog_reply').on('keydown','.reply_box',function(e){
			// if(e.keyCode == 13) {
			$('#blog_reply').on('click','.reply_button',function(){
				<?php if($this->user_id!=''){ ?>
					
					var blog_comment_id = $(this).attr('data-value');
					var comment =  $('#txt_'+blog_comment_id).val();
					if(comment == ''){
						alert("Comment cannot be blank!");
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
										alert('Comment will be approved by Admin!.');   
									}else{
									   alert("Something went wrong! Please enter valid data.");
											$('.reply_box').val('');
											
									}
								}
							});
						}  
				<?php }else{ ?>
					loadModal("login","Please login to add reply");
				<?php } ?>    
				return false;
			      
		});
	});
</script>
<style>
	.hidden
	{
		display:none;
	}
</style>




