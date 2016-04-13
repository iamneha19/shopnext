<div class="form">

	<?php 
		$form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>false,
		));
	?>
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'name'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'email'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'dob'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'dob'); ?>
				<?php echo $form->error($model,'dob'); ?>
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
			<?php echo $form->labelEx($model,'profile_pic'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'profile_pic',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'profile_pic'); ?>
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
			<?php echo $form->labelEx($model,'country_id'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'country_id'); ?>
				<?php echo $form->error($model,'country_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'type'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'type',array('size'=>1,'maxlength'=>1)); ?>
				<?php echo $form->error($model,'type'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'username'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'password'); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'send_newsletter'); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'send_newsletter',array('size'=>1,'maxlength'=>1)); ?>
				<?php echo $form->error($model,'send_newsletter'); ?>
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