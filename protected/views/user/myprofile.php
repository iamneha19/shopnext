<?php
	$this->pageTitle = 'My profile';
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
	  <div class="prod_title">My profile</div><br>
      <div class="post_separator"></div>
      </div>
      </div>
     
		<?php if(Yii::app()->user->hasFlash('user_msg')): ?>
			<div class="flash-success alert alert-success">
				<?php echo Yii::app()->user->getFlash('user_msg'); ?>
			</div>
		<?php endif; ?>
		<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'myprofile-form',
				'enableClientValidation'=>true,
				'enableAjaxValidation'=>true,
				'clientOptions'=>array(
							'validateOnSubmit'=>false,
							'validateOnChange'=>true,
					),
			)); ?>
          
				
				<div class="row">
					<label <?php /*?><?php echo $form->labelEx($model,'username'); ?><?php */?>
					<?php echo $model->username; ?>
					<?php echo $form->error($model,'username'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'name'); ?>
					<?php echo $form->textField($model,'name',array('class'=>'form-control input-medium')); ?>
					<?php echo $form->error($model,'name'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'gender'); ?>
					<?php echo $form->DropDownList($model,'gender',array('F'=>'Female','M'=>'Male',''=>'I\'d rather not mention'),array('size'=>1,'maxlength'=>1,'class'=>'form-control input-medium')); ?>
					<?php echo $form->error($model,'gender'); ?>
				</div>
				<div class="row dob_cont">
					<?php echo $form->labelEx($model,'dob'); ?>	
					<?php
						// Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
					   $this->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$model, //Model object
							'attribute'=>'dob', //attribute name
							'value'=>$model->dob,
							'htmlOptions'=>array('class'=>'input-small','readonly'=>true,'placeholder'=>'Click to select'),
							'language' => '',
							 'options'=>array(
										'changeMonth'=>true,
										'changeYear'=>true,
										'showAnim'=>'drop',
										'yearRange'=>'1950:',
										//'maxDate'=>"-10Y",
										'dateFormat'=>'dd-mm-yy',
										'showButtonPanel'=>true, 
									) // jquery plugin options
						));
					?>
					<?php echo $form->error($model,'dob'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'contact_no'); ?>
					<?php echo $form->textField($model,'contact_no',array('class'=>'form-control input-medium')); ?>
					<?php echo $form->error($model,'contact_no'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'address'); ?>
					<?php echo $form->textarea($model,'address',array('class'=>'form-control input-medium')); ?>
					<?php echo $form->error($model,'address'); ?>
				</div>
				<div class="form-group row">
					<?php echo $form->labelEx($model,'state_id',array('class'=>'col-md-2 control-label')); ?>		
					<div class="col-md-6" style="width:426px!important">
						<?php echo $form->dropDownList($model,'state_id', $states, array('class'=>'form-control input-large','empty'=>'-- Select State --','ajax' => array(
						'type'=>'POST', 
						'url'=>CController::createUrl('User/GetDynamicCity'), 
						'data'=>array('state_id'=>'js:this.value'),
						'update'=>'#User_city_id', 
						)));?>
						<?php echo $form->error($model,'state_id'); ?>
						<?php if(isset($model->state) && $model->state->status=='0'){?><div class="errorMessage">State <b><?php echo $model->state->state."</b> is been deleted. Add new.";?></div><?php }?>
					</div>
				</div>	
				<div class="form-group row">
					<?php echo $form->labelEx($model,'city_id',array('class'=>'col-md-2 control-label')); ?>		
					<div class="col-md-6" style="width:426px!important">
						<?php echo $form->dropDownList($model,'city_id', $cities, array('class'=>'form-control input-large','empty'=>'-- Select City --','ajax' => array(
						'type'=>'POST', 
						'url'=>CController::createUrl('User/GetDynamicLocality'), 
						'data'=>array('city_id'=>'js:this.value'),
						'update'=>'#User_locality_id', 
						)));?>
						<?php echo $form->error($model,'city_id'); ?>
						<?php if(isset($model->city) && $model->city->status=='0'){?><div class="errorMessage">City <b><?php echo $model->city->city."</b> is been deleted. Add new.";?></div><?php }?>
					</div>
				</div>	
				<div class="form-group row">
					<?php echo $form->labelEx($model,'locality_id',array('class'=>'col-md-2 control-label')); ?>
					<div class="col-md-6" style="width:426px!important">
						<?php echo $form->dropDownList($model,'locality_id', $localities, array('class'=>'form-control input-large','empty'=>'-- Select Locality --')); ?>
						<?php echo $form->error($model,'locality_id'); ?>
						<?php if(isset($model->locality) && $model->locality->status=='0'){?><div class="errorMessage">Locality <b><?php echo $model->locality->locality."</b> is been deleted. Add new.";?></div><?php }?>
					</div>
				</div>	
				<div class="row">
					<?php echo $form->labelEx($model,'send_newsletter'); ?>
					<?php echo $form->DropDownList($model,'send_newsletter',array('Y'=>'Yes','N'=>'No'),array('size'=>1,'maxlength'=>1,'class'=>'form-control input-small')); ?>
					<?php echo $form->error($model,'send_newsletter'); ?>
				</div>
				<div class="row buttons save_btn">
					<?php echo CHtml::submitButton('Save'); ?>
				</div>
			<?php $this->endWidget(); ?>
		</div><!-- form -->
        
        </article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	</div>
</div>	
<script>
$('#User_state_id').on('change',function()
{
	$('#User_city_id').val('');
	$('#User_locality_id').val('');
});
</script>
