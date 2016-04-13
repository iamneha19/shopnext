<?php
	$themeBaseUrl = Yii::app()->theme->baseUrl;
	$baseUrl = Yii::app()->baseUrl;
	$basePath = Yii::app()->basePath; 

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
		<aside id="left_side">
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
			<?php if(count($model)>0){?>
			<article class="products">
				<div class="prod_pic">
					<div class="l_cont">
						<?php
							$i = 1;
							$user_id = ApplicationSessions::run()->read('user_id');
						foreach($model as $row) {?>
						<div class="prod_cont">
							<div class="product_ic">
								<div class="prod_img">
									<?php 
										if(!empty($row->productImage->image)){
											if($row->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$row->productImage->image)){ 
												$prod_url = Yii::app()->params['SERVER']."upload/product/".$row->productImage->image;
											}else{
												$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
											}
										}else{
											$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
										}
									?>
									<a href='<?php echo Yii::app()->params['SERVER']; ?>product/detail/<?php echo $row->name;?>'>
										<img src="<?php echo $prod_url;?>" alt="<?php echo $row->name;?>" style="height:150px;width:150px;"/> 
									</a>
									<?php if($row->online == 'Y'){ ?>
									<div onclick="addToCart(this);" class="over_img cart_btn" data-product="<?php if(!empty($row->product_id)){ echo $row->product_id; } ?>" data-shop="<?php if(!empty($model->shop_id)){ echo $model->shop_id; } ?>" data-user="<?php if(!empty($user_id)){ echo $user_id; } ?>">
										<a href="javascript:void(0);">Add To Cart</a>
									</div>
									<?php } ?>
								</div>
								<div class="prod_cost">
									<div class="price_title"><?php echo $row->name = Controller::formatShortText($row['name'], 20); ?></div>
									<div class="price">Rs. <?php echo $row->price;?> INR</div>
									<div class="art_footerlist">

										<!--Share --><a href="javascript:void(0);" onclick='postToFeed("<?php echo $row["name"]; ?>","<?php echo str_replace(array("\r", "\n", "'"), "", $row["description"]); ?>","<?php echo Yii::app()->params["SERVER"].'product/detail/'.$row["name"];  ?>","<?php echo $prod_url; ?>");'><img src="<?php echo $themeBaseUrl; ?>/images/facebook-20.png"/></a> 

											<a class="twitter popup" href="http://twitter.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>product/detail/<?php echo $row['name']; ?>" onclick="javascript:window.open(this.href,
												'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img src="<?php echo $themeBaseUrl; ?>/images/twitter-20.png"/></a>
											<a href="https://plus.google.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>product/detail/<?php echo $row['name']; ?>" onclick="javascript:window.open(this.href,
												'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
												src="<?php echo $themeBaseUrl; ?>/images/google-20.png" alt="Share on Google+"/></a>
										<!-- <a href="#"><img src="<?php echo $themeBaseUrl; ?>/images/google-20.png"/></a> -->
									</div>
								</div>
							</div>
						</div>
						<?php if($i%3==0){ ?>
						<div class="clr"></div>	
						<?php } ?>
						<?php if($i==9){ break;}?>
						<?php $i++;}?>
					</div>

					<div class="load_more"><a href="javascript:void(0);"> LOAD MORE PRODUCTS</a></div>
					<div class ="load_more_msg"></div>

				</div>
			</article>
			<?php } ?>
		</aside>
		<aside id="right_side">
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	<div class="clr"></div>
	</div>
</div>
<?php $shop_id = $shop_id;?>
<div id="shopId" style="visibility:hidden;"><input type="text" class="shopId" value="<?php echo $shop_id;?>"></div>

<script>
var offset = '<?php echo $offset+$limit; ?>';
var limit = '<?php echo $limit; ?>';
var shop_id = $('.shopId').val();

	$('.prod_pic').on('click','.load_more',function(){
	$('.load_more').html("<a href = '#'> LOADING PLEASE WAIT....</a>").css('background','#50618E');
	$.ajax({
			type:"POST",
			url:site_url+"/product/ProductList",
			data:{pagination:offset,shopId:shop_id},
			success:function(result){
				var obj = jQuery.parseJSON( result );
				if(obj.data!='')
				{
					$('.l_cont').append(obj.data);
					$('.load_more').html('<a href="javascript:void(0);">LOAD MORE PRODUCTS</a>').css('background','#1e3060');
					offset = parseInt(offset)+parseInt(limit);
				}
				else{
					$('.load_more').hide();
					$('.load_more_msg').html("NO MORE PRODUCTS TO DISPLAY....");				
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
					$('#center_div').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
					// window.location.reload();

				}
		});
	});
</script>