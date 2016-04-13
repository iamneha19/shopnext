<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
<?php 
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath;

	if($shop_data->shop_image_id!='' && file_exists($imagePath."/../upload/shop/".$shop_data->shopImage->image)) 
	{ 
		$image = $imageUrl."/upload/shop/".$shop_data->shopImage->image;
		$style = "background:url(".$imageUrl."/upload/shop/".$shop_data->shopImage->image.")center;background-size: 100%;";		
	}
	else{
		$style ="";
		$image = "";
	} 	
	?>

<div class="banner" style="<?php echo $style;?>">
	<div class="banner_inner">
		<div class="banner_cont">
			<div class="banner_btxt"><?php echo $shop_data->name;?></div>
			<div class="banner_btn">
				<div class="lt_cont1">
					<div class="lt_img1">
						<!-- <img src="<?//php //echo $themeBaseUrl.'/images/bookmark.png';?>"/>
						<img src="<?//php //echo $themeBaseUrl.'/images/markasunread.png';?>"/>
						<img src="<?//php// echo $themeBaseUrl.'/images/review.png';?>"/> -->
					</div>
				</div>
				<div class="ct_cont1">
					<div class="ct_txtf">Your Rating</div>
					<div class="ct_ratings1">
						<?php			 
						$this->widget('CStarRating',array(
							'name'	  =>'mystar',
							'value'	  =>$user_rating,							
							'allowEmpty'=>true,							
							'minRating'=>1,
							'maxRating'=>5,
							'starCount'=>5,
							'resetText'=>'Cancel your rating',
							'resetValue'=>'0',
							'titles'=>array(
										'1'=>'Normal',
										'2'=>'Average',
										'3'=>'OK',
										'4'=>'Good',
										'5'=>'Excellent'
									),
							'callback'=>'function() {
										rating =  $(this).val();
										$.ajax({
												type: "POST",
												url: "'.Yii::app()->createUrl('shop/rating').'",
												dataType: "json",    
												data: "ratings=" +rating+"&shop_id='.$shop_data->shop_id.'",
												success: function(data) {													
													if(!data.success && data.status=="2") 
													{
														$("#overlay").fadeIn();
														$("#login_main").fadeIn();
														$("#login_main").animate({top:50},300);
														$("div.rating-cancel").click();
													}else{
														 $("#my_rate").html(rating);
														$(".rt_txt").html(data.count +" votes");
													}
												}
										}); }',
						  ));
						echo "<div id='mystar'></div>";			
						?>	
					</div>
					<div class="ct_txtl" id="my_rate"><?php echo ($user_rating>0) ? $user_rating : "-";?></div>
				</div>
				<div class="rt_cont1">
					<div class="ct_box"><?php echo number_format($shop_data->rating,1);?></div>
					<div class="rt_txt"><?php echo $shop_data->rating_count;?>votes</div>
					
				</div>
			</div>   
		</div>
	</div>
</div>

<div id="wrapper">
	<div id="main_div">
		<!--*************** Banners image left div     **********************-->
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

		<!--*************** Deal details center div     **********************-->

		<aside id="center_div">
			<article class="products">
				<div class="describe_main">
					<div class="u_cont">
						<div class="describe_title"><?php echo $model->title;?></div>
						<div class="describe_subtitle">
							<a class='shop_name' href="<?php echo Yii::app()->createUrl('shop/detail/'.$model->shop->state->state.'/'.$model->shop->city->city.'/'.$model->shop->locality->locality.'/'.urlencode($model->shop->name))?>"><?php echo $model->shop->name;?> </a>
						</div>
					</div>
					<div class="l_cont">
					    <div class="decribe_cont">
							<div class="describe_img">
							<?php
							$deal_img = $imagePath."/../upload/deal/".$model->deal_image;
							if(file_exists($deal_img) && !empty($model->deal_image))
							{	
							?>
								<img src="<?php echo $imageUrl."/upload/deal/".$model->deal_image; ?>" width="250px" height="250px" />
							<?php
							}
							else
							{	
								$shop_data = Shop::model()->find(array('condition'=>'shop_id='.$model->shop_id.' and status="1"'));
								
								if(!empty($shop_data->shop_image_id) && file_exists($imagePath."/../upload/shop/".$shop_data->shopImage->image))
								{
									
							?>
									<img src="<?php echo $imageUrl."/upload/shop/".$shop_data->shopImage->image; ?>" width="250px" height="250px" />
							<?php
								}
								else
								{
									
							?>
									<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" width="250px" height="250px"/>
							<?php
								}
							}
							?>
							<?php //echo  CHtml::image($image, $model->deal_image,array("width"=>"250px" ,"height"=>"250px")) ?></div>
							<div class="describe_imgtext">
								<div class="u_imgtext">
									<div class="prod_title">Description</div>
									<div class="describe_subtitle"><?php echo $model->desc; ?></div>
								</div>
								<div class="l_imgtxt">
									<div class="prod_title">Deal Amount</div>
									<div class="describe_price"><?php echo $model->amount; ?></div> 
								</div>
							</div>
						</div>
					</div>
					 <div class="describe_info">
						<div class="info_cont">
							<div class="prod_title"><?php echo $model->title;?></div>
							<div class="prod_features">
								<div id="validity">
									<span class="validity_txt"><?php echo $model->validity; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</article>

			<article class="reviews">
				<div class="add_review">
					<div class="prod_title">Reviews</div>
					<div class="prod_subtitle">Write a review</div>
					<div class="add_review_input">
						<textarea rows="6"  id="review-box" name="review"></textarea>
						<button id="review-submit-btn" class="btn btn-warning">POST REVIEW</button> 
					</div>
				</div>

				<?php if(count($comments)>0) {?>				
					<div class="all_reviews">
						<div class="prod_title">All Reviews</div>
						<div class="prod_subtitle"><?php echo ($model->total_comments) ? $model->total_comments : 0; ?> +</div>
						<?php foreach($comments as $comment) { ?>	
						<div class="review_cont">
							<div class="sender_img">
								<?php echo $this->getUserImage($comment->user,'64','64'); ?>
								<?php echo $comment->user_name; ?>
							</div>
							<?php
								$this->widget('ext.timeago.JTimeAgo', array(
									'selector' => ' .timeago',
								));
							?>
							<div class="time">
								<abbr class="timeago" title="<?php echo date('Y-m-d h:i:s a',$comment->added_on);  ?>"></abbr>
							</div>
							<div class="status"> 
								<?php if (isset($comment->rate) and !empty($comment->rate)) {?>
								Rated <span><?php echo number_format($comment->rate,1);?></span>
								<?php }?>						
								<?php echo $comment->comment; ?>
							</div>
						</div>
						<div class="post_separator"></div>
						<?php } ?>
						<div id="append_more"></div>
					</div>				
				<?php } ?>
				
			</article>
			<?php if(count($comments)>=5){?>		
			<div id="load_more"><a href="javascript:void(0);">SEE ALL REWIEWS</a></div>
			<?php } ?>
			<span class ="load_more_msg"></span>
		</aside>


		<aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
		<div class="clr"></div>
	</div>
</div>
<?php $deal_id = $model->deal_id;?>
<div id="dealId" style="visibility:hidden;"><input type="text" class="dealId" value="<?php echo $deal_id;?>"></div>

<?php $this->renderPartial('/shop/review',array('offset'=>$offset,'limit'=>$limit)); ?>

