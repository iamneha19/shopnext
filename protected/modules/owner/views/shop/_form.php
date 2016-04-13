<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'fileupload',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>
		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'name',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'name',array('class'=>'form-control input-large','size'=>60,'maxlength'=>200)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
		</div>	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'category_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php $this->widget('ext.MyAutoComplete', array(
					'model'=>$model,
					'attribute'=>'category_id',
					'htmlOptions'=>array('Placeholder'=>'Type to get list of categories....','class'=>'form-control input-large'),
					'value'=>(!empty($model->category_id))?$model->category->category:'',
					'source'=>$this->createUrl('shop/AutocompleteCategory'),
					'options'=>array(
							'showAnim'=>'fold',
							'minLength'=>'1',
					),
				)); ?>
				<?php echo $form->error($model,'category_id'); ?>
			</div>
		</div>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'contact_no',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'contact_no',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'contact_no'); ?>
			</div>
		</div>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'description',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php echo $form->textarea($model,'description',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		</div>	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'address',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php echo $form->textarea($model,'address',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'address'); ?>
			</div>
		</div>	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'state_id',array('class'=>'col-md-2 control-label')); ?>		
			<div class="col-md-6" style="width:426px!important">
				<?php echo $form->dropDownList($model,'state_id', $states, array('class'=>'form-control input-large','empty'=>'-- Select State --','ajax' => array(
				'type'=>'POST', 
				'url'=>CController::createUrl('Shop/GetDynamicCity'), 
				'data'=>array('state_id'=>'js:this.value'),
				'update'=>'#Shop_city_id', 
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
				'url'=>CController::createUrl('Shop/GetDynamicLocality'), 
				'data'=>array('city_id'=>'js:this.value'),
				'update'=>'#Shop_locality_id', 
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
		<div class="form-group row">
			<?php echo $form->labelEx($model,'zip_code',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'zip_code',array('class'=>'form-control input-small','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'zip_code'); ?>
			</div>
		</div>	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				
				<?php echo $form->dropDownList($model,'active_status', array('S'=>'Active','H'=>'Deactive'), array('class'=>'form-control input-small')); ?>
				<?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>	
		<?php 
			if($model->shop_image_id!='' && file_exists(Yii::app()->basePath."/../upload/shop/".$model->shopImage->image)){ 
				$image 	   = Yii::app()->baseUrl."/upload/shop/".$model->shopImage->image;
				$cur_state = "fileinput-exists";
			}else{
				$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
				$cur_state = "fileinput-new";
			}
		?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'shop_image_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-6">
				<div class="fileinput <?php echo $cur_state?>" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
						<?php echo CHtml::image("http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",$model->shop_image_id) ;?>
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
						<?php echo CHtml::image($image,($model->shop_image_id!='') ?$model->shopImage->image:"") ;?>
					</div>
					<div>
						<span class="btn green btn-file">
						<span class="fileinput-new">Select image </span>
						<span class="fileinput-exists">	Change </span>
						<?php echo $form->fileField($model,'shop_image_id'); ?>
						</span>
						<a href="#" class="btn blue fileinput-exists" data-dismiss="fileinput">
						Remove </a>					
					</div>
					<?php echo $form->hiddenField($model, 'shop_image_id'); ?>
				</div>		
			</div>
		</div>
			
		<div class="form-group row">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/shop/admin'">Cancel</button>
			</div>
		</div>	
	<?php $this->endWidget(); ?>
</div>
<?php 	$cs = Yii::app()->getClientScript();$baseUrl = Yii::app()->theme->baseUrl; ?>
<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>
<script type='text/javascript'>	
	$(document).ready(function()
	{
		$('#Shop_category_id_autocomplete').val('<?php echo $model->category_id; ?>'); 
		$('#Shop_user_id_autocomplete').val('<?php echo $model->user_id; ?>');
		$('input').removeClass('radio');
		
		$('.fileinput').fileinput().on('clear.bs.fileinput',function(){
			$('#shop_image_id_hidden').val('');
		});
	});
</script>	
<script>
$('#Shop_state_id').on('change',function()
{
	$('#Shop_city_id').val('');
	$('#Shop_locality_id').val('');
});
</script>


