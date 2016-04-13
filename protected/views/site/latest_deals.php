<?php $deal_status = $this->getLikeStatus('deal',$data['deal_id']);
	$baseUrl = Yii::app()->theme->BaseUrl;
	$imageUrl = Yii::app()->baseUrl; 
	$imagePath = Yii::app()->basePath; 
?>
<article class="posts">
	<div class="art_wraper">
		<a href='<?php echo Yii::app()->params['SERVER'];?>deal/detail/<?php echo urlencode($data['title']);?>'>
			<div class="art_img">
				<?php
					$deal_img = $imagePath."/../upload/deal/".$data['deal_image'];
					if(file_exists($deal_img) && !empty($data['deal_image']))
					{
						$fb_img = Yii::app()->params['SERVER']."upload/deal/".$data['deal_image'];
				?>
						<img src="<?php echo $imageUrl."/upload/deal/".$data['deal_image']; ?>"/>
				<?php
					}
					else
					{	
						$shop_data = Shop::model()->find(array('condition'=>'shop_id='.$data['shop_id'].' and status="1"'));
						
						if(!empty($shop_data->shop_image_id) && file_exists($imagePath."/../upload/shop/".$shop_data->shopImage->image))
						{
							$fb_img = Yii::app()->params['SERVER']."upload/shop/".$shop_data->shopImage->image;
					?>
							<img src="<?php echo $imageUrl."/upload/shop/".$shop_data->shopImage->image; ?>"/>);
					<?php
						}
						else
						{
							$fb_img = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
					?>
							<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/>
					<?php
						}
					}
				?>
			</div>
		</a>
		<div class="art_details">
			<div class="article_title"><h2>
				<a href='<?php echo Yii::app()->params['SERVER'];?>deal/detail/<?php echo urlencode($data['title']);?>'><?php echo $data['title']; ?></a>
			</h2></div>
			
			<div class="artical_desc">
				<?php
					$desc_link='<a href="'.Yii::app()->params['SERVER'].'deal/detail/'.urlencode($data["title"]).'" style = "color:red;"> readmore </a>';
					echo Controller::formatShortText($data['desc'], 85).$desc_link;
				?>
			</div>
		</div>
		<div class="clr"></div>
	</div>

	
	<div class="art_footer">
		<!--Share --><a href="javascript:void(0);" onclick="postToFeed('<?php echo $data['title']; ?>','<?php echo str_replace(array("\r", "\n", "'"), "", $data['desc']); ?>','<?php echo Yii::app()->params['SERVER']; ?>deal/detail/<?php echo urlencode($data['title']);?>','<?php echo $fb_img; ?>');"><img src="<?php echo $baseUrl; ?>/images/facebook-20.png"/></a> 
		<a class="twitter popup" href="http://twitter.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>deal/detail/<?php echo urlencode($data['title']);?>"><img src="<?php echo $baseUrl; ?>/images/twitter-20.png"/></a>
		<a href="https://plus.google.com/share?url=<?php echo Yii::app()->params['SERVER']; ?>deal/detail/<?php echo urlencode($data['title']);?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
  src="<?php echo $baseUrl; ?>/images/google-20.png" alt="Share on Google+"/></a>
		<!-- <a href="#"><img src="<?php echo $baseUrl; ?>/images/google-20.png"/></a> -->
		<a href="javascript:void(0);" class="email"><img src="<?php echo $baseUrl; ?>/images/mail-20.png"/></a>
		<div class="footer_r_link">
			<div class="deal_like"><a href="javascript:void(0);" class="favourite deal" data-value="<?php echo $data['deal_id']; ?>" data-user="<?php  echo ApplicationSessions::run()->read('user_id');?>"><img src="<?php echo $baseUrl; ?>/images/favourite_icon.jpg" style="visibility:hidden"/></a>
				<span class="like_text ">
					<?php if($deal_status['status']==1) {?>
						<span style="color:#b5291c;"> Unlike</span>
					<?php } else { ?>
						Like
					<?php } ?>
				</span>
			</div>
		</div>
	</div>
</article>
