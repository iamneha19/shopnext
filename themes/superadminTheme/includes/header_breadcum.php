<?php
	$header_data = Controller::getHeaderBreadcrumb(Yii::app()->controller->id,Yii::app()->controller->action->id);
	if(!empty($header_data)) 
{
?>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">		
			<?php if(array_key_exists('page_header',$header_data)) echo $header_data['page_header']; ?>
		</h3>		
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<?php if(Yii::app()->controller->module->id=='superadmin') { ?>
						<a href="<?php echo Yii::app()->createUrl('superadmin/default/index'); ?>">Home</a>
					<?php } else { ?>
						<a href="<?php echo Yii::app()->createUrl('owner/default/index'); ?>">Home</a>
					<?php } ?>
					<i class="fa fa-angle-right"></i>
				</li>				
				<?php if(array_key_exists('menu',$header_data) && is_array($header_data['menu']) && !empty($header_data['menu'])) {?>
				<li>
					<?php if(array_key_exists('icon',$header_data['menu']) && !empty($header_data['menu']['icon'])){?>
						<i class="fa <?php echo $header_data['menu']['icon'];?>"></i>
					<?php } ?>
					
					<?php if(array_key_exists('name',$header_data['menu']) && !empty($header_data['menu']['name'])) 
						{	
							echo $header_data['menu']['name'];
						}
					?>	
					
					<?php if(array_key_exists('breadcrumb_li_1',$header_data) && !empty($header_data['breadcrumb_li_1'])) {?>
						<i class="fa fa-angle-right"></i>
					<?php }?>
				</li>				
				<?php } ?>				
				<?php if(array_key_exists('breadcrumb_li_1',$header_data) && !empty($header_data['breadcrumb_li_1'])) {?>
				<li>
					<?php if(array_key_exists('li_icon',$header_data['breadcrumb_li_1']) && !empty($header_data['breadcrumb_li_1']['li_icon'])){?>
						<i class="fa <?php echo $header_data['breadcrumb_li_1']['li_icon'];?>"></i>
					<?php } ?>
					
					<?php if(array_key_exists('li_data',$header_data['breadcrumb_li_1']) && !empty($header_data['breadcrumb_li_1']['li_data'])) {?>
					
					<?php if(array_key_exists('li_link',$header_data['breadcrumb_li_1']) && !empty($header_data['breadcrumb_li_1']['li_link'])){?>						
						<a href="<?php echo $header_data['breadcrumb_li_1']['li_link'];?>">
					<?php }?>
						
					<?php echo $header_data['breadcrumb_li_1']['li_data'];?>
						
					<?php if(array_key_exists('li_link',$header_data['breadcrumb_li_1']) && !empty($header_data['breadcrumb_li_1']['li_link'])){?>						
						</a>
					<?php }?>
					
					<?php } ?>
					<?php if(array_key_exists('breadcrumb_li_2',$header_data) && !empty($header_data['breadcrumb_li_2'])) {?>
						<i class="fa fa-angle-right"></i>
					<?php }?>
				</li>				
				<?php } ?>	
				
				<?php if(array_key_exists('breadcrumb_li_2',$header_data) && !empty($header_data['breadcrumb_li_2'])){?>
				<li>
					<?php if(array_key_exists('li_icon',$header_data['breadcrumb_li_2']) && !empty($header_data['breadcrumb_li_2']['li_icon'])){?>
						<i class ="fa <?php echo $header_data['breadcrumb_li_2']['li_icon'];?>"></i>
					<?php } ?>
					
					<?php if(array_key_exists('li_data',$header_data['breadcrumb_li_2']) && !empty($header_data['breadcrumb_li_2']['li_data'])){?>
						
						<?php if(array_key_exists('li_link',$header_data['breadcrumb_li_2']) && !empty($header_data['breadcrumb_li_2']['li_link'])){?>						
							<a href="<?php echo $header_data['breadcrumb_li_2']['li_link'];?>">
						<?php }?>
							
						<?php echo $header_data['breadcrumb_li_2']['li_data'];?>
							
						<?php if(array_key_exists('li_link',$header_data['breadcrumb_li_2']) && !empty($header_data['breadcrumb_li_2']['li_link'])){?>						
							</a>
						<?php }?>
					<?php } ?>
				</li>
				<?php } ?>			
						
			</ul>
		</div>
			
	</div>
</div>
<?php }?>
<?php if(Yii::app()->user->hasFlash('pageMessage')):?>
	<div  id="statusMsg" class="Metronic-alerts alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<i class="fa-lg fa fa-check"></i> 
		<?php echo Yii::app()->user->getFlash('pageMessage'); ?>
	</div>
<?php endif; ?>	