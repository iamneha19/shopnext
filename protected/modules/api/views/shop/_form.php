<?php
/* @var $this ShopController */
/* @var $model Shop */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shop-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'category_id'); ?>
			<?php echo $form->error($model,'category_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'user_id'); ?>
			<?php echo $form->error($model,'user_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'name'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'contact_no'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'contact_no',array('size'=>15,'maxlength'=>15)); ?>
			<?php echo $form->error($model,'contact_no'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'description'); ?>
		<div class="col-lg-10">
			<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'address'); ?>
		<div class="col-lg-10">
			<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'address'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'locality_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'locality_id'); ?>
			<?php echo $form->error($model,'locality_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'city_id'); ?>
			<?php echo $form->error($model,'city_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'state_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'state_id'); ?>
			<?php echo $form->error($model,'state_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'latitude',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'latitude'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'longitude',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'longitude'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'zip_code'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'zip_code',array('size'=>6,'maxlength'=>6)); ?>
			<?php echo $form->error($model,'zip_code'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'shop_image_id'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'shop_image_id'); ?>
			<?php echo $form->error($model,'shop_image_id'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'rating'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'rating'); ?>
			<?php echo $form->error($model,'rating'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'total_comments'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'total_comments'); ?>
			<?php echo $form->error($model,'total_comments'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'active_status'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'active_status',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'active_status'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'added_on'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'added_on'); ?>
			<?php echo $form->error($model,'added_on'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'updated_on'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'updated_on'); ?>
			<?php echo $form->error($model,'updated_on'); ?>
		</div>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'status'); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'status',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-lg-offset-2">
			<button class="btn btn-default marginR10" type="submit">Save changes</button>
			<button class="btn btn-danger">Cancel</button>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->