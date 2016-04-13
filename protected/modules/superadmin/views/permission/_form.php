<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'permission-form',
			'enableAjaxValidation'=>false,
		)); 
	?>

		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'role_id'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'role_id'); ?>
				<?php echo $form->error($model,'role_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'permission_name'); ?>
			<div class="col-lg-10">
				<?php echo $form->textArea($model,'permission_name',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'permission_name'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'all_permission'); ?>
			<div class="col-lg-10">
				<?php echo $form->textArea($model,'all_permission',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'all_permission'); ?>
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
</div>