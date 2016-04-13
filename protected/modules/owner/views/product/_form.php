<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form CActiveForm */
?>

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
		<?php echo $form->labelEx($model,'product_category_id',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			<?php $this->widget('ext.MyAutoComplete', array(
				'model'=>$model,
				'attribute'=>'product_category_id',
				'htmlOptions'=>array('Placeholder'=>'Type to get list of categories....','class'=>'form-control input-large'),
				'value'=>(!empty($model->product_category_id))?$model->productCategory->product_category:'',
				'source'=>$this->createUrl('product/AutocompleteCategory'),
				'options'=>array(
						'showAnim'=>'fold',
						'minLength'=>'1',
				),
			)); ?>
			<?php echo $form->error($model,'product_category_id'); ?>
		</div>
	</div>
	
	<div class="form-group row">
			<?php echo $form->labelEx($model,'shop_id',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
							<?php $this->widget('ext.MyAutoComplete', array(
					'model'=>$model,
					'attribute'=>'shop_id',
					'htmlOptions'=>array('Placeholder'=>'Type to get list of shops....','class'=>'form-control input-large'),
					'value'=>(!empty($model->shop_id))?$model->shop->name:'',
					'source'=>$this->createUrl('product/AutocompleteShop'),
					'options'=>array(
							'showAnim'=>'fold',
							'minLength'=>'1',
					),
				)); ?>
				<?php echo $form->error($model,'shop_id'); ?>
			</div>
		</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'name',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'name',array('maxlength'=>50,'size'=>60,'class'=>'form-control input-large')); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'description',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textArea($model,'description',array('maxlength'=>200,'rows'=>6, 'cols'=>50,'class'=>'form-control input-large')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'price',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'price',array('maxlength'=>20,'size'=>10,'class'=>'form-control input-small')); ?> 
			<?php echo $form->error($model,'price'); ?>
		</div>
	</div>
	
	<div class="form-group row">
			<?php echo $form->labelEx($model,'discount',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'discount', array('class'=>'form-control input-small',)); ?>
				<?php echo $form->error($model,'discount'); ?>				
			</div>
		</div>	
	
	<div class="form-group row">
			<?php echo $form->labelEx($model,'discount_type',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'discount_type', array('P'=>'Percentage','R'=>'Rupees'), array('empty'=>'-- Select --','class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'discount_type'); ?>
			</div>
		</div>
	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'online',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			
			<?php echo $form->dropDownList($model,'online', array('Y'=>'Yes','N'=>'No'), array('class'=>'form-control input-small')); ?>
			<?php echo $form->error($model,'online'); ?>
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
		if($model->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$model->productImage->image)){ 
			$image 	   = Yii::app()->baseUrl."/upload/product/".$model->productImage->image;
			$cur_state = "fileinput-exists";
		}else{
			$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
			$cur_state = "fileinput-new";
		}
	?>
	<div class="form-group row">
		<?php echo $form->labelEx($model,'product_image_id',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			<div class="fileinput <?php echo $cur_state?>" data-provides="fileinput">
				<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
					<?php echo CHtml::image("http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",$model->product_image_id) ;?>
				</div>
				<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
					<?php echo CHtml::image($image,($model->product_image_id!='') ?$model->productImage->image:"") ;?>
				</div>
				<div>
					<span class="btn green btn-file">
					<span class="fileinput-new">Select image </span>
					<span class="fileinput-exists">	Change </span>
					<?php echo $form->fileField($model,'product_image_id'); ?>
					</span>
					<a href="#" class="btn blue fileinput-exists" data-dismiss="fileinput">
					Remove </a>					
				</div>
				<?php echo $form->hiddenField($model, 'product_image_id'); ?>
			</div>		
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-lg-offset-2">
			<button class="btn btn-default marginR10" type="submit">Save changes</button>
			<button class="btn btn-danger" type="button"  onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/product/admin'">Cancel</button>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php 	$cs = Yii::app()->getClientScript();$baseUrl = Yii::app()->theme->baseUrl; ?>
<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

<script type='text/javascript'>	
	$(document).ready(function()
	{		
		$('#Product_product_category_id_autocomplete').val('<?php echo $model->product_category_id; ?>'); 
		$('#Product_shop_id_autocomplete').val('<?php echo $model->shop_id; ?>'); 		
		$('.fileinput').fileinput().on('clear.bs.fileinput',function(){
			$('#Product_product_image_id').val('');
		});
	});
</script>	