<?php 
	$product_status = $this->getLikeStatus('product',$model->product_id);
	$themeBaseUrl = Yii::app()->theme->baseUrl;
	$baseUrl = Yii::app()->baseUrl;
	$basePath = Yii::app()->basePath; 
	$user_id = ApplicationSessions::run()->read('user_id');

	if($shop_data->shop_image_id!='' && file_exists($basePath."/../upload/shop/".$shop_data->shopImage->image)) 
	{ 
		$image = $baseUrl."/upload/shop/".$shop_data->shopImage->image;
		$style = "background:url(".$baseUrl."/upload/shop/".$shop_data->shopImage->image.")center;background-size: 100%;";		
	}
	else{
		$style ="";
		$image = "";
	} 	
?>

<!--************    image slider popup  div     ****************-->

<div class="img_popup">
<div class="imgpopup_main"></div>
<div class="close_div"></div>
</div>

<!--************    image slider popup  div     ****************-->

<!--************* start middle div ************-->

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

<!--***************  center div     **********************-->
<aside id="center_div1">
<article class="productdetails">
 <div class="productdetails_main">
 
  <div class="l_cont">
  <div class="decribe_cont">
  
  <div class="describe_img">
  	<!-- slider start -->
 		<div id="container">
			<div id="slideshow" class="fullscreen">
                <!-- Below are the images in the gallery -->
                <?php
					
                    $i = 1;
					if(empty($model->productImages))
					{
						$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";?>
						<div id="img-1" data-img-id="1" class="img-wrapper active" style="background-image:url('<?php echo $prod_url;?>')"></div>	
				<?php }else {
						foreach($model->productImages as $img) {
							if($img->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$img->image)){ 
								$prod_url = Yii::app()->baseUrl."/upload/product/".$img->image;
							}else{
								$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
							}
							
							?>
							<div id="img-<?php echo $i;?>" data-img-id="<?php echo $i;?>" class="img-wrapper active" style="background-image:url('<?php echo $prod_url;?>')"></div>	
							<?php
							$i++;
						}
					}
				?>
                               <!-- Below are the thumbnails of above images -->
                <!--***************************************************************************-->
                <div class="thumbs-container bottom">
                    <div id="prev-btn" class="prev">
						<?php if (count($model->productImages)>4){ ?>
							<i class="fa fa-chevron-left fa-3x"></i>
						<?php } ?>
                    </div>                    <ul class="thumbs">
                        <?php  
                            $i = 1;
                            foreach($model->productImages as $img) {
								if($img->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$img->image)){ 
									$prod_url = Yii::app()->baseUrl."/upload/product/".$img->image;
								}else{
									$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
								}
								?>
								<li data-thumb-id="<?php echo $i;?>" class="thumb active" style="background-image: url('<?php echo $prod_url; ?>')"></li>
								<?php
								$i++;
                            }
                        ?>
                    </ul>                    <div id="next-btn" class="next">
					<?php if (count($model->productImages)>3){ ?>
                        <i class="fa fa-chevron-right fa-3x"></i>
					<?php } ?>
                    </div>
                </div>
            </div> 
		</div>
  <!-- slider end -->      
</div>


  <div class="productdetails_imgtext">
  <div class="u_cont">
    <div class="describe_title"><?php echo $model->name;?></div>
    <div class="describe_subtitle">
		<a class='shop_name' href="<?php echo Yii::app()->createUrl('shop/detail/'.$model->shop->state->state.'/'.$model->shop->city->city.'/'.$model->shop->locality->locality.'/'.urlencode($model->shop->name))?>"><?php echo $model->shop->name;?> </a>
	</div>
 </div>
  <div class="u_producttxt">
     <div class="prod_title">Description</div>
     <div class="describe_subtitle"><?php echo $model->description;?></div>
  </div>
  <div class="l_imgtxt">
  <div class="txt_left">
      <div class="prod_title">Product Amount</div>
       <div class="describe_price">Rs.<?php echo $model->price;?></div> 
       <div class="lower_ct">
       <div class="l_ct">
       <div class="describe_offer">Discount</div>
       <div class="describe_price">
	   <?php 
			if(!empty($model->discount) && $model->discount != 0.00)
			{
				if($model->discount_type == 'P')
				{
					echo $model->discount ."%"; 
				}
				else
				{
					$discount = (!empty($model->discount))?$model->discount:0;
					echo 'Rs. '.$discount;
				}
			}
			else
			{
				echo "No Discount";
			}
		?>
	   </div>
       </div>
		<?php if($model->online == 'Y'){ ?>
       <div class="addcart_btn" onclick="addToCart(this);" data-product="<?php if(!empty($model->product_id)){ echo $model->product_id; } ?>" data-shop="<?php if(!empty($model->shop_id)){ echo $model->shop_id; } ?>" data-user="<?php if(!empty($user_id)){ echo $user_id; } ?>"><input type="button" id="add_cart_btn" value="Add Cart"></div>
	   <?php } ?>
       </div>
       <div class="art_footerouter">
    <div class="footer_r_link">
		<div class="product_like"><a href="javascript:void(0);" class="favourite product" data-value="<?php echo $model->product_id; ?>" data-user="<?php  echo ApplicationSessions::run()->read('user_id');?>"><img src="<?php echo $themeBaseUrl; ?>/images/favourite_icon.jpeg" style="visibility:hidden"/></a>
			<span class="like_text ">
				<?php if($product_status['status']==1) {?>
					<span style="color:#b5291c;"> Unlike</span>
				<?php } else { ?>
						Like
				<?php } ?>
			</span>
		</div>
	 </div>
     </div>
       </div>
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
<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>

</aside>
<div class="clr"></div>
</div>
</div>
<?php $product_id = $model->product_id;?>
<div id="productId" style="visibility:hidden;"><input type="text" class="productId" value="<?php echo $product_id;?>"></div>

<?php $this->renderPartial('/shop/review',array('offset'=>$offset,'limit'=>$limit)); ?>

<script>
var check_session='<?php echo ApplicationSessions::run()->read('user_email'); ?>'
		$('.favourite.product').click(function(){
			if(check_session=='')
			{
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			}else{
				var content= $(this);
				var product_id = $(this).attr('data-value');
				var user_id = $(this).attr('data-user'); 
				$.ajax({
					type:"POST",
					url:site_url+"/site/LikeStatus",
					data:{product_id:product_id,user_id:user_id,type:'product'},
					success: function(result){
						var obj = result.split("::");
						if(obj[0]!=false)
						{
							if(obj[1]=='L')
							{
								content.siblings('.like_text').html("<span style='color:#b5291c;'>Unlike</span>");
							}
							else
							{
								content.siblings('.product_like .like_text').html("Like");
							}
						}
					},
				});
			}
		});
</script>


<script type="text/javascript">
 $(document).ready(function() {
      $(".img-wrapper").click(function(){
			$('.img_popup').stop().fadeIn();
			$('.img_popup').animate({top:50},300);
			$('.imgpopup_main').stop().fadeIn();
			$('.imgpopup_main').animate({top:50},300);
			$('.close_div').stop().fadeIn();
			$('#overlay').fadeIn(300);
			
			var bg_url = $(this).css('background-image');   
			bg_url = /^url\((['"]?)(.*)\1\)$/.exec(bg_url);
			bg_url = bg_url ? bg_url[2] : ""; // If matched, retrieve url, otherwise ""
    
			$(".imgpopup_main").each(function(){
				$(this).empty();
				$(this).css({'background-image': 'url(" '+ bg_url +' ")'});
			});
		});
		
		$('.close_div , #overlay').on('click',function(){
			$('#overlay').fadeOut(300);
			$('.img_popup').fadeOut(300);
			$('.imgpopup_main').fadeOut(300);
			$('.close_div').stop().fadeOut();
		});
});

</script>
