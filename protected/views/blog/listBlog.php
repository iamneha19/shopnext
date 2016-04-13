<?php 
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 	
 ?>
<div id="wrapper">
	<div id="main_div">
		<div class="center"></div>
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
<!-- ******************************************************************************************************** -->
		<aside id="center_div">
			<?php 
			if(isset($blogs) && !empty($blogs)){
				$this->widget('zii.widgets.CListView', array(
							'id'=>'site-list',
							'dataProvider'=>$blogs,
							'itemView'=>'webroot.protected.views.blog.latest_blogs',
							'ajaxUpdate'=>true,
							'enablePagination'=>false,
							'template'=>'{items}',
				));
				}?>
				<div id="load_more_blogs"><a href="javascript:void(0);">LOAD MORE BLOGS</a></div>
				<span class="no_more_data"></span>
			
		</aside>
<!-- ******************************************************************************************************** -->

	<aside id="right_side">
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
	</aside>
	<div class="clr"></div>
	</div>
</div>
<script>
var offset = '<?php echo $offset+$limit; ?>';
var limit = '<?php echo $limit; ?>';

	$('#center_div').on('click','#load_more_blogs',function(){
	$('#load_more_blogs').html("<a href = '#'> LOADING PLEASE WAIT....</a>").css('background','#50618E');
	$.ajax({
			type:"POST",
			url:site_url+"/blog/AjaxBlogs",
			data:{pagination:offset},
			dataType:"html",
			success:function(result)
			{
				if(result!='')
				{
					$('#load_more_blogs').before(result);
					$('#load_more_blogs').html('<a href="javascript:void(0);">LOAD MORE BLOGS</a>').css('background','#1e3060');
					offset = parseInt(offset)+parseInt(limit);
					// alert(offset);
				}else{
					$('#load_more_blogs').hide();
					$('.no_more_data').html("NO MORE BLOGS TO DISPLAY....");
				}	
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
					$('#center_div').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
					// window.location.reload();
				}
		});
	});
</script>
