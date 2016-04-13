<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'country-form',
			'enableAjaxValidation'=>false,
		)); 
	?>
		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'country',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'country'); ?>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/country/admin'">Cancel</button>
			</div>
		</div>
	<?php $this->endWidget(); ?>
</div>