<div class="form">
	<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'state-form',
			'enableAjaxValidation'=>false,
		)); 
	?>

		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'state',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textField($model,'state',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'state'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'country_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model,'country_id', $country,array('empty'=>'-- Select --','class'=>'form-control input-medium') );?>
				<?php echo $form->error($model,'country_id'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/state/admin'">Cancel</button>
			</div>
		</div>

	<?php $this->endWidget(); ?>
</div>