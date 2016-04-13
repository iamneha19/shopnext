<!--************* popup div ************-->
<?php if(count($model->shopImages)>0) { ?>
<div class="img_popup_cont">
	<div class="main_div">
		<div id="demo">
			<div class="container">
				<div class="span12">
					<div class="title_cont_popup">
						<div class="prod_title">Shop Photos</div>
						<div class="prod_subtitle">Over <?php echo count($model->shopImages);?> + Photos</div>
					</div>
					<div class="customNavigation">
					<?php //if(count($model->shopImages)>6) { ?>
						<a class="btn prev"><img src="<?php echo $themeBaseUrl.'/images/leftarrow.png';?>"/></a>
						<a class="btn next"><img src="<?php echo $themeBaseUrl.'/images/rightarrow.png';?>"/></a>
					<?php //} ?>
					</div>
					
					
					<div id="owl-shop-images" class="owl-carousel">
						
						<?php $i=1; foreach($model->shopImages as $img) { ?>	
						
								<?php if ($i%7==0 || $i=='1'){?>
								<div class="item">
								<div class="l_cont_popup"> 									
								<?php } ?>
								
									<div class="prod_cont">
										<div class="product_ic">
											<div class="add_img">
												<img src="<?php echo $baseUrl."/upload/shop/".$img->image; ?>" style="width:148px;height:148px;"/>
											</div>
										</div>
									</div> 
									
								<?php if ($i%6==0 || $i==count($model->shopImages)){?>
								</div>
								</div>
								<?php } ?>
								
						<?php $i++;} ?>
							
					</div>  
					<div class="close_btn_popup">close</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<!--************* end popup div ************-->