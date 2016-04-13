<?php // $deal_status = $this->getLikeStatus('deal',$data['deal_id']);
	$baseUrl = Yii::app()->theme->BaseUrl;
	$imageUrl = Yii::app()->baseUrl; 
	$imagePath = Yii::app()->basePath; 
?>
<article class="posts">
	<div class="art_wraper">
		
		<div class="art_details">
			<div class="article_title"><h2><?php echo $data['title']; ?></h2></div>
			<div class="artical_desc">
				<?php
				$desc_link='<a href="'.Yii::app()->createUrl('blog/BlogcommentsList/'.$data["blog_id"]).'" style = "color:red;"> readmore </a>';
				echo Controller::formatShortText($data['description'], 85).$desc_link;
				?>
			</div>
			<?php if(!empty($data->admin->name)){ ?>
				<div class="artical_desc"><h4>Created By:- </h4><?php echo $data->admin->name;?></div>
			<?php } ?>
			<div class="artical_img">
				<?php
					if(!empty($data->admin->profile_pic))
					{
						$blog_path = $imagePath."/../upload/admin/".$data->admin->profile_pic;
						if(!empty($data->admin_id) && file_exists($blog_path))
						{
							$fb_img = Yii::app()->params['SERVER']."/upload/admin/".$data->admin->profile_pic;
				?>
							<img src="<?php echo $imageUrl."/upload/admin/".$data->admin->profile_pic; ?>"/>
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
			
		</div>
		
		<div class="clr"></div>
	</div>

	
	
</article>
