<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>Shop details</h4>
		</div>
		
		<div class="panel-body noPad clearfix">
                    <p>Shop Name : <?php echo $model->name; ?></p>
                    <label>Avg Rating : </label><?php			 
			$this->widget('CStarRating',array(
				'name'	  =>'ratings',
				'value'	  =>$model->rating,
                                'readOnly'=>true,
                                'minRating'=>1,
                                'maxRating'=>5,
                                'starCount'=>5,
			  ));
			echo "<div id='ratings'></div>";
			
			?>
			<br>
			<label>Total Comments:</label><span><?php echo ($model->total_comments) ? $model->total_comments : 0; ?></span>
			<br><br>
			<?php echo CHtml::image('https://maps.googleapis.com/maps/api/staticmap?center='.$model->latitude.','.$model->longitude.'&size=240x150&maptype=roadmap\&markers=scale:2|icon:|'.$model->latitude.','.$model->longitude.'|format=png32&sensor=false&scale=2&zoom=14&language=en', $model->name); ?>
			<br><br>
			<label>My Rating :</label>
			<?php			 
			$this->widget('CStarRating',array(
				'name'	  =>'mystar',
				'value'	  =>$user_rating,
				'minRating'=>1,
				'maxRating'=>5,
				'starCount'=>5,
				'callback'=>'function() {
							$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('shop/rating').'",
									dataType: "json",    
									data: "ratings=" + $(this).val()+"&shop_id='.$model->shop_id.'",
									success: function(data) {
										if(!data.success && data.status=="2"){
												loadModal("login","Login");
										}
									}
							}); }',
			  ));
			echo "<div id='mystar'></div>";			
			?>	<br>		
			<h3>Reviews:</h3>
            <hr>					
			<div class="bs-docs-example" id='review-section'>
				<?php foreach($comments as $comment) { ?>					
						<div class="media comment review">
							<a class="pull-left user-pic" href="#">
								<?php echo $this->getUserImage($comment->user,'64','64'); ?>
							</a>
							<div class="media-body user-name">
								<h5 class="media-heading"><?php echo $comment->user->name; ?></h5>
								<span class="comment-text"><?php echo $comment->comment; ?></span>	
							</div>
							<div class="media reply" style="margin-top: 0px;">
								<div style="display:block;">
									<button class='reply-btn btn btn-mini btn-primary' data-value="<?php echo $comment->comment_id; ?>">Reply</button><br><br>
								</div>
								 <?php 
									if($comment->reply) 
									{
										$replies = explode("~", $comment->reply);
										foreach($replies as $reply)
										{
											$reply_data = explode(":", $reply);
											$user = $this->getUserInfo($reply_data[0]);
								?>	
									<a class="pull-left user-pic" href="#">
										<?php echo $this->getUserImage($user,'64','64'); ?>
									</a>
									<div class="media-body">
										<h6 class="media-heading user-info"><?php echo $user->name; ?></h6>
										<span class="reply-text"><?php echo $reply_data[2]; ?></span>
									</div>	<br>								
								<?php 
										}
									} 
								?>
							</div>
						</div>	<br>
				<?php } ?>
			</div>
			
			<label>Add your review :</label>			
			<div class="media">
				<a class="pull-left user-pic" href="#">
					<img src="<?php echo ( ApplicationSessions::run()->read('profile_pic')!='' ) ? ApplicationSessions::run()->read('profile_pic') : Yii::app()->baseUrl."/themes/classic/img/default.png"; ?>" alt="Garima Singh" title="Garima Singh" width="64" height="64">
				</a>
				<div class="media-body">
					<h6 class="media-heading user-info">
						<?php echo ( ApplicationSessions::run()->read('fullname')!='' ) ? ApplicationSessions::run()->read('fullname') : "&nbsp;"; ?>
					</h6>
					<textarea id='review-box' name="review"></textarea>
					<button id="review-submit-btn" class="btn btn-success">Submit</button>   
				</div>
			</div>
			 
		</div>
	</div>
</div>
<div style='display: none;'>
    <div id='reply-box'>		
		<a class="pull-left user-pic" href="#">
			<img src="<?php echo ( ApplicationSessions::run()->read('profile_pic')!='' ) ? ApplicationSessions::run()->read('profile_pic') : Yii::app()->baseUrl."/themes/classic/img/default.png"; ?>" width="64" height="64">
		</a>
		<div class="media-body">
			<h6 class="media-heading user-info">
				<?php echo ( ApplicationSessions::run()->read('fullname')!='' ) ? ApplicationSessions::run()->read('fullname') : " "; ?>
			</h6>
			<span class="reply-text"> <textarea class='reply-box' name="reply"></textarea></span>
		</div>		
    </div>     
</div>
<script>
$('document').ready(function(){
    shop_id = <?php echo $model->shop_id; ?>;
    <?php if(empty($this->user_id)){ ?>
        $('#review-box').focusin(function() {
            loadModal("login","Please login to add review");
         });
    <?php } ?>    
     
    $('#review-submit-btn').on('click',function(){
        var review =  $('#review-box').val();
        if(review != ''){
            $.ajax({
                        type: "POST",
                        url: '<?php echo Yii::app()->createUrl('shop/review') ?>',
                        dataType: "json",
                        data: {review:review,shop_id:shop_id},
                        success: function(data)
                                {    
                                    if(data.success){
                                        $('#review-box').val('');
                                        alert('Review will be approved by Admin!.');    
                                    }else{
                                       alert('Something went wrong!! Please enter valid data.');
                                    }
                                }
            });
        } 
    }); 
    
    
    $('.reply-btn').on('click',function(){
        <?php if($this->user_id){ ?>
               var comment_id = $(this).attr('data-value');
                $('#reply-box .reply-box').attr('data-value',comment_id);
                $('.new-reply').remove();				
                $(this).parent().after('<span class="new-reply" style="margin-top:10px;">'+$('#reply-box').html()+'</span>'); 
        <?php }else{ ?>
                loadModal("login","Please login to give reply"); 
        <?php } ?>    
        
    });
    
    $('#review-section').on('keydown','.reply-box',function(e){
        if(e.keyCode == 13){
            <?php if($this->user_id){ ?>
                var reply =  $(this).val();
                var comment_id = $(this).attr('data-value');
                if(reply != ''){
                    $.ajax({
                                type: "POST",
                                url: '<?php echo Yii::app()->createUrl('shop/reply') ?>',
                                dataType: "json",
                                data: {reply:reply,shop_id:shop_id,parent_id:comment_id},
                                success: function(data)
                                        {    
                                            if(data.success){
                                                $('#review-section .reply-box').val('');
                                                alert('Reply will be approved by Admin!.');    
                                            }else{
                                               alert('Something went wrong!! Please enter valid data.');
                                            }
                                        }
                    });
                }  
            <?php }else{ ?>
                loadModal("login","Please login to give reply"); 
            <?php } ?>    
            return false;
        }        
    });
});
</script>
