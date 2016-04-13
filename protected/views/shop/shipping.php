<?php
$this->pageTitle = 'My profile';
$themeBaseUrl = Yii::app()->theme->baseUrl;
$baseUrl = Yii::app()->baseUrl;
$basePath = Yii::app()->basePath; 	
?>

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
    <article class="products">
				<div class="prod_pic">
				<div class="u_cont">
	  <div class="prod_title">Shipping Details</div><br>
      <div class="post_separator"></div>
      </div>
      </div>
     
		<?php if(Yii::app()->user->hasFlash('user_msg')): ?>
			<div class="flash-success">
				<?php echo Yii::app()->user->getFlash('user_msg'); ?>
			</div>
		<?php endif; ?>
		<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'shipping-form',
				'enableClientValidation'=>true,
				'enableAjaxValidation'=>false,
				'clientOptions'=>array(
							'validateOnSubmit'=>false,
							'validateOnChange'=>true,
					),
			)); ?>
          
				<div class="row mandatory_msg"><p class="note">Fields with <span class="required">*</span> are required.</p></div>
				<div class="row">
					<?php echo $form->labelEx($model,'name'); ?>
					<?php echo $form->textField($model,'name',array('class'=>'form-control input-medium','value'=>$user->name)); ?>
					<?php echo $form->error($model,'name'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'email'); ?>
					<?php echo $form->textField($model,'email',array('class'=>'form-control input-medium','value'=>$user->email)); ?>
					<?php echo $form->error($model,'email'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'mobile_no'); ?>
					<?php echo $form->textField($model,'mobile_no',array('class'=>'form-control input-medium','value'=>$user->contact_no)); ?>
					<?php echo $form->error($model,'mobile_no'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'address'); ?>
					<?php echo $form->textArea($model,'address',array('class'=>'form-control input-medium','rows'=>5,'cols'=>10,'value'=>$user->address)); ?>
					<?php echo $form->error($model,'address'); ?>
				</div>
				<div class="row buttons save_btn">
					<?php echo CHtml::submitButton('Save'); ?>
				</div>
			<?php $this->endWidget(); ?>
		</div><!-- form -->
        
 
        </article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $themeBaseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	</div>
</div>	