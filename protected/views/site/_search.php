<?php $baseUrl = Yii::app()->theme->baseUrl;
		$imageUrl = Yii::app()->baseUrl;
		$imagePath = Yii::app()->basePath; 	
 ?>
<?php if(!empty($shops)){ foreach($shops as $shop){
		$shop_data = Shop::model()->findByPk($shop->id); // to get data from database.
		
		if(!empty($shop_data))
		{
	?>
			<article class="posts">
				<div class="art_wraper">
					<a href='<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $shop_data->state->state."/".$shop_data->city->city."/".$shop_data->locality->locality."/".urlencode($shop_data->name) ?>'>
						<div class="art_img">
							<?php
								if(!empty($shop_data->shopImage->image))
								{
									$shop_path = $imagePath."/../upload/shop/".$shop_data->shopImage->image;
									if(!empty($shop_data->shop_image_id) && file_exists($shop_path))
									{
										//$fb_img = 'http://dev2.taolabs.in'.$imageUrl."/upload/shop/".$shop_data->shopImage->image;
										$fb_img = Yii::app()->params['SERVER']."upload/shop/".$shop_data->shopImage->image;
							?>
										<img src="<?php echo $imageUrl."/upload/shop/".$shop_data->shopImage->image; ?>"/>
								<?php
									}else{
										$fb_img = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
								?>
										<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/>
								<?php
									}
								}else{
									$fb_img = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
								?>
									<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/>
								<?php 
								}
								?>
						</div>
					</a>
					<div class="art_details">
						<div class="article_title"><h2>
						<a href='<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $shop_data->state->state."/".$shop_data->city->city."/".$shop_data->locality->locality."/".urlencode($shop_data->name) ?>'>
						<?php echo $shop_data->name; ?>
						</a>
						</h2></div>
						 <div class="artical_desc">
							<?php
							$desc_link='<a href="'.Yii::app()->createUrl('shop/detail/'.$shop_data->state->state.'/'.$shop_data->city->city.'/'.$shop_data->locality->locality.'/'.urlencode($shop_data->name)).'" style = "color:red;"> readmore </a>';
							echo Controller::formatShortText($shop_data->description, 85).$desc_link;
							?>
						</div>
					</div>
					<div class="clr"></div>
				</div>
				<div class="art_footer">
					<!--Share -->
					<a href="javascript:void(0);" onclick="postToFeed('<?php echo $shop_data->name; ?>','<?php echo str_replace(array("\r", "\n", "'"), "", $shop_data->description); ?>','<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $shop_data->state->state.'/'.$shop_data->city->city.'/'.$shop_data->locality->locality.'/'.$shop_data->name; ?>','<?php echo $fb_img; ?>');"><img src="<?php echo $baseUrl; ?>/images/facebook-20.png"/></a> 
					<a class="twitter popup" href="http://twitter.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $shop_data->state->state.'/'.$shop_data->city->city.'/'.$shop_data->locality->locality.'/'.$shop_data->name; ?>"><img src="<?php echo $baseUrl; ?>/images/twitter-20.png"/></a>
					<a href="https://plus.google.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $shop_data->state->state.'/'.$shop_data->city->city.'/'.$shop_data->locality->locality.'/'.$shop_data->name; ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img src="<?php echo $baseUrl; ?>/images/google-20.png" alt="Share on Google+"/></a>
					
					<!-- <a href="#"><img src="<?php echo $baseUrl; ?>/images/google-20.png"/></a> -->
					<a href="javascript:void(0);" class="email"><img src="<?php echo $baseUrl; ?>/images/mail-20.png"/></a>
					<div class="footer_r_link">
						<div class="shop_like"><a href="javascript:void(0);" class="favourite shop" data-value="<?php echo $shop_data->shop_id; ?>" data-user="<?php  echo ApplicationSessions::run()->read('user_id');?>"><img src="<?php echo $baseUrl; ?>/images/favourite_icon.jpg" style="visibility:hidden"/></a>
						<span class="like_text ">
								 <?php 
								 $shop_status = $this->getLikeStatus('shop',$shop_data->shop_id);
								 if($shop_status['status']==1) {?>
									<span style="color:#b5291c;"> Unlike</span>
								<?php } else { ?>
									Like
								<?php } ?>
							</span>
						
						</div>
					</div>
				</div>
			</article>
	<?php
		}
} }
	?>