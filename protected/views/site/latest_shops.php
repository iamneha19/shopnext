<?php
	$shop_status = $this->getLikeStatus('shop',$data['shop_id']);
	$baseUrl = Yii::app()->theme->BaseUrl;
	$imageUrl = Yii::app()->baseUrl; 
	$imagePath = Yii::app()->basePath;
?>
<article class="posts">
	<div class="art_wraper">
		<a href='<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $data['state']->state."/".$data['city']->city."/".$data['locality']->locality."/".urlencode($data['name']) ?>'>
			<div class="art_img">
				<?php
					if(!empty($data->shopImage->image))
					{
						$shop_path = $imagePath."/../upload/shop/".$data->shopImage->image;
						if(!empty($data->shop_image_id) && file_exists($shop_path))
						{
							$fb_img = Yii::app()->params['SERVER']."/upload/shop/".$data->shopImage->image;
				?>
							<img src="<?php echo $imageUrl."/upload/shop/".$data->shopImage->image; ?>"/>
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
				<a href='<?php echo Yii::app()->params['SERVER']; ?>shop/detail/<?php echo  $data["state"]->state."/".$data["city"]->city."/".$data["locality"]->locality."/".urlencode($data["name"]) ?>'><?php echo $data["name"]; ?></a>
			</h2></div>
			 <div class="artical_desc">
				<?php
				$desc_link='<a href="'.Yii::app()->createUrl('shop/detail/'.$data["state"]->state.'/'.$data["city"]->city.'/'.$data["locality"]->locality.'/'.urlencode($data["name"])).'" style = "color:red;"> readmore </a>';
				echo Controller::formatShortText($data['description'], 85).$desc_link;
				?>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="art_footer">
		<!--Share -->
		<a href="javascript:void(0);" onclick="postToFeed('<?php echo $data['name']; ?>','<?php echo str_replace(array("\r", "\n", "'"), "", $data['description']); ?>','<?php echo Yii::app()->params['SERVER']."/shop/sharing/".$data['shop_id'] ?>','<?php echo $fb_img; ?>');"><img src="<?php echo $baseUrl; ?>/images/facebook-20.png"/></a> 
		<a class="twitter popup" href="http://twitter.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>shop/sharing<?php echo  $data['shop_id'] ?>"><img src="<?php echo $baseUrl; ?>/images/twitter-20.png"/></a>
		<a href="https://plus.google.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>shop/sharing/<?php echo  $data['shop_id'] ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
  src="<?php echo $baseUrl; ?>/images/google-20.png" alt="Share on Google+"/></a>
		<!-- <a href="#"><img src="<?php echo $baseUrl; ?>/images/google-20.png"/></a> -->
		<a href="javascript:void(0);" class="email"><img src="<?php echo $baseUrl; ?>/images/mail-20.png"/></a>
		<div class="footer_r_link">
			<div class="shop_like"><a href="javascript:void(0);" class="favourite shop" data-value="<?php echo $data['shop_id']; ?>" data-user="<?php  echo ApplicationSessions::run()->read('user_id');?>"><img src="<?php echo $baseUrl; ?>/images/favourite_icon.jpg" style="visibility:hidden"/></a>
			<span class="like_text ">
					 <?php if($shop_status['status']==1) {?>
						<span style="color:#b5291c;"> Unlike</span>
					<?php } else { ?>
						Like
					<?php } ?>
				</span>
			
			</div>
		</div>
	</div>
</article>
