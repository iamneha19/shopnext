<?php 
	$themeBaseUrl = Yii::app()->theme->baseUrl;
	$baseUrl = Yii::app()->baseUrl;
	$basePath = Yii::app()->basePath; 	
	if($model->shop_image_id!='' && file_exists($basePath."/../upload/shop/".$model->shopImage->image)) 
	{ 
		$image = $baseUrl."/upload/shop/".$model->shopImage->image;
		$style = "background:url(".$baseUrl."/upload/shop/".$model->shopImage->image.")center;background-size: 100%;";		
	}
	else{
		$style ="";
		$image = "";
	} 
	// Meta data for social media share
		
	$this->page_title = 'Shopnext - '.$model->name;
	$this->page_type = 'Shop detail';
	$this->page_description = $model->description;
	$this->page_image = $image;
	
?>

<div class="banner" style="<?php echo $style;?>">
	<div class="banner_inner">
		<div class="banner_cont">
			<div class="banner_btxt"><?php echo $model->name;?></div>
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
												data: "ratings=" +rating+"&shop_id='.$model->shop_id.'",
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
					<div class="ct_box"><?php echo number_format($model->rating,1);?></div>
					<div class="rt_txt"><?php echo $model->rating_count;?>votes</div>
					
				</div>
			</div>   
		</div>
	</div>
</div>
<div id="wrapper">
	<div id="main_div">
		<aside id="left_side">
			<div class="ph_div">
				<div class="inner_cont">
					<div class="ph_no"><span class="address_title">Contact No </span><img src="<?php echo $themeBaseUrl.'/images/ph_mark.png';?>"/><?php echo $model->contact_no!='' ? $model->contact_no:"Not set"; ?></div>
					<div class="cont_separator"></div>
					<div class="ph_no"><span class="address_title">Home Delivery :- </span><?php if($model->home_delivery=='Y'){ echo "Yes"; }else{ echo "No"; } ?></div>
				</div>
			</div>
			<div class="address_l">
				<div class="inner_cont">
					<div class="address_title"><?php echo $model->city->city;?></div>
					<div class="cont_separator"></div>
					<div class="address_cont">
						<?php echo $this->formatedAddress($model->name,$model->address,$model->zip_code);?>
					</div>
					<div class="address_cont">
						<?php echo CHtml::image('https://maps.googleapis.com/maps/api/staticmap?center='.$model->latitude.','.$model->longitude.'&size=240x150&maptype=roadmap\&markers=scale:2|icon:|'.$model->latitude.','.$model->longitude.'|format=png32&sensor=false&scale=2&zoom=15&language=en', $model->name); ?>
					</div>
				</div>
			</div>
			
			<div class="openhr">
				<div class="inner_cont">
					<div class="address_title">
						Photos 
						<span class="title_r">
						
								<?php $count =0;
									foreach($model->shopImages as $shopImg)
										{
											if($shopImg->status=='1' && $shopImg->active_status=='S' )
											{
												$count++;
											}
										}?>
										
										<?php 
										if(count($model->shopImages)>0){
											print"(over ".$count." + shop photos)"; 
										}else{
											print "(no photos found!)";
										}	
											?>
						</span>
					</div>
					<div class="cont_separator"></div>
					<?php if(count($model->shopImages)>0) { ?>
					<div class="address_cont">
						<div class="img_popup1">
							<?php 
								if(count($model->shopImages)>1 && $model->shopImages[1]->shop_image_id != $model->shop_image_id)
								{
									$image = $baseUrl."/upload/shop/".$model->shopImages[1]->image;
								}else{
									$image = $baseUrl."/upload/shop/".$model->shopImages[0]->image;
								}
							?>
							<img src="<?php echo $image;?>"/>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			
			<?php 
				foreach($banners as $banner)
				{ 
					if($banner->type == 'I')
					{
						$banner_img = $basePath."/../upload/banner/".$banner->banner;
						if($banner->banner!='' && file_exists($banner_img)){
						?>
							<div class="local_ads"><img src="<?php echo $baseUrl."/upload/banner/".$banner->banner; ?>"/></div>
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
		
		<aside id="center_div">
			
			<article class="products">
			<div class="shop_details">
			    <div class="prod_title">Description</div>
				 <div class="artical_desc"><?php echo $model->description;?></div>
			</div>
			</article>
			<?php if(count($model->products)>0){?>
			<article class="products">
				<div class="prod_pic">
					<div class="u_cont">
						<div class="title_cont">
							<div class="prod_title">Products</div>
							<?php $count = 0;
								foreach($model->products as $pr_row){
									if($pr_row->active_status=="S" and $pr_row->status=="1"){
										$count++;
									}
								}?>
							<div class="prod_subtitle">Over <?php echo $count;?> Products</div>
						</div>
						
					</div>
					<div class="l_cont">
						<?php
							$i = 1;
							$user_id = ApplicationSessions::run()->read('user_id');
							foreach($model->products as $row) {
								if($row->status=="1" && $row->active_status=='S'){?>
									<div class="prod_cont">
										<div class="product_ic">
											<div class="prod_img">
												
												<?php
													if($row->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$row->productImage->image)){ 
														$prod_url = Yii::app()->baseUrl."/upload/product/".$row->productImage->image;
														
													}else{
														$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
													}
												?>
												
												<a href='<?php echo Yii::app()->params['SERVER']; ?>product/detail/<?php echo urlencode($row->name);?>'>
												<img src="<?php echo $prod_url;?>" alt="<?php echo $row->name = Controller::formatShortText($row['name'], 20);?>" style="height:150px;width:150px;"/> 
												</a>
												<?php if($row->online == 'Y'){ ?>
												<div onclick="addToCart(this);" class="over_img cart_btn" data-product="<?php if(!empty($row->product_id)){ echo $row->product_id; } ?>" data-shop="<?php if(!empty($model->shop_id)){ echo $model->shop_id; } ?>" data-user="<?php if(!empty($user_id)){ echo $user_id; } ?>">
													<a href="javascript:void(0);">Add To Cart</a>
												</div>
												<?php } ?>
											</div>
											<div class="prod_cost">
												<div class="price_title"><?php echo $row->name;?></div>
												<div class="price">Rs. <?php echo $row->price;?> INR</div>
											</div>
										</div>
									</div>
									<?php if($i%3==0){ ?>
									<div class="clr"></div>	
									<?php } ?>
									<?php if($i==6){ break;}?>
								<?php $i++;}?>
						<?php  } ?>
					</div>
					<?php  if(count($model->products)>6){?>
					<div class="load_more"><a href='<?php echo Yii::app()->params['SERVER']; ?>product/ShopProducts/<?php echo  $model->name; ?>'> SEE ALL PRODUCTS</a></div>
					<?php  }?>
				</div>
			</article>
			<?php } ?>
			
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
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
		<div class="clr"></div>
	</div>
</div>
<?php $shop_id = $model->shop_id;?>
<div id="shopId" style="visibility:hidden;"><input type="text" class="shopId" value="<?php echo $shop_id;?>"></div>


<?php $this->renderPartial('/shop/review',array('offset'=>$offset,'limit'=>$limit)); ?>

<script>
$('document').ready(function() 
{
	<?php if(count($model->shopImages)>0) { ?>
	var owl = $("#owl-shop-images");

	owl.owlCarousel({

	items :1, //10 items above 1000px browser width
	itemsDesktop : [1000,1], //5 items between 1000px and 901px
	itemsDesktopSmall : [900,1], // 3 items betweem 900px and 601px
	itemsTablet: [600,1], //2 items between 600 and 0;
	itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option

	});

	// Custom Navigation Events
	$(".next").click(function(){
	owl.trigger('owl.next');
	})
	$(".prev").click(function(){
	owl.trigger('owl.prev');
	})
	$(".play").click(function(){
	owl.trigger('owl.play',1000);
	})
	$(".stop").click(function(){
	owl.trigger('owl.stop');
	});
	
	$("#overlay").hide();
	
	$(".img_popup1").click(function() {
		$(".img_popup_cont").animate({top:50},500);
		$("#overlay").attr('style','z-index:1;');
		$("#overlay").show();
		$("#overlay").fadeIn(500);
	});
	$("#overlay , .close_btn_popup").click(function() {
		$(".img_popup_cont").animate({top:-600},500);
		$("#overlay").fadeOut(500);
		$("#l_close").click();
		$("#r_close").click();
	});
	<?php } ?>
});
</script>
<script>
	function resetRating()
	{
		$.ajax({
				type: "POST",
				url: "<?php echo Yii::app()->createUrl('shop/rating');?>",
				dataType: "json",    
				data: "ratings=0&shop_id=<?php echo $model->shop_id?>",
				success: function(data) {													
					if(!data.success && data.status=="2") 
					{
						$("#overlay").fadeIn();
						$("#login_main").fadeIn();
						$("#login_main").animate({top:50},300);
					}else{
						$("#my_rate").html("-");
						$(".rt_txt").html(data.count +" votes");
					}
					$('div.star-rating.rater-0').removeClass('star-rating-on');
				}
		});
	}
</script>

