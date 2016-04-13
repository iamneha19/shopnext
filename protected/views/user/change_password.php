<?php
	$this->pageTitle = 'Change password';
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 	
?>
<div id="wrapper">
	<div id="main_div">
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
			} ?>
		</aside>
    
    <aside id="center_div">
    <article class="products">
				<div class="prod_pic">
				<div class="u_cont">
	  <div class="prod_title">Change password</div><br>
      <div class="post_separator"></div>
      </div>
      </div>
     

		<?php if(Yii::app()->user->hasFlash('user_msg')): ?>

		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('user_msg'); ?>
		</div>

		<?php else: ?>

		<div class="form">

		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'changepass-form',
			'enableClientValidation'=>true,
			'enableAjaxValidation'=>true,
			'clientOptions'=>array(
								'validateOnSubmit'=>true,
							),
		)); ?>

			<div class="row mandatory_msg">
            <p class="note ">Fields with <span class="required">*</span> are required.</p>
            </div>
			<?php if($model->password=='' && $model->register_type!='Registration') {?>
				<div class="row">
					Registered through <?php echo $model->register_type?>, create your login password now :
				</div>
			<?php } else {?>
			<div class="row">
				<?php echo $form->labelEx($model,'old_password'); ?>
				<?php echo $form->passwordField($model,'old_password',array('class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'old_password'); ?>
			</div>
			<?php } ?>
			<div class="row">
				<?php echo $form->labelEx($model,'new_password'); ?>
				<?php echo $form->passwordField($model,'new_password',array('class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'new_password'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'repeat_password'); ?>
				<?php echo $form->passwordField($model,'repeat_password',array('class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'repeat_password'); ?>
			</div>
			<div class="row buttons change_pass">
				<?php echo CHtml::submitButton('Change Password'); ?>
			</div>

		<?php $this->endWidget(); ?>

		</div><!-- form -->

		<?php endif; ?>
        
          </article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
        
	</div>
</div>
