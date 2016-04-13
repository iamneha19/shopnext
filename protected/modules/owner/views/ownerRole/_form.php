<?php
/* @var $this OwnerRoleController */
/* @var $model ownerRole */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'owner-role-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'name',array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'name',array('class'=>'form-control input-medium','size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-lg-offset-2">
			<button class="btn btn-default marginR10" type="submit">Save changes</button>
			<button class="btn btn-danger" type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/ownerRole/admin'">Cancel</button>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->