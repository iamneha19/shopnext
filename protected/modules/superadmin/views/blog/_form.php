<div class="form">

	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'blog-form',	
			'enableAjaxValidation'=>false,
		));
		?>

		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'title',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textField($model,'title',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'description',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textArea($model,'description',array('class'=>'form-control input-large','rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				 <?php echo $form->dropDownList($model,'active_status', array('S'=>'Active','H'=>'Deactive'), array('class'=>'form-control input-small') );?>
				 <?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>

		<div class="form-group row">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/blog/admin'">Cancel</button>
			</div>
		</div>
	<?php $this->endWidget(); ?>

</div>