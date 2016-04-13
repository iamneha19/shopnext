<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'city-form',
			'enableAjaxValidation'=>false,
		));
	?>
	<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'city',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-medium')); ?>
			<?php echo $form->error($model,'city'); ?>
		</div>
	</div>	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'country_id',array('class'=>'col-md-2 control-label')); ?>		
		<div class="col-md-6" style="width:426px!important">
			<?php echo $form->dropDownList($model,'country_id', $countries, array('class'=>'form-control input-medium','empty'=>'-- Select Country --','ajax' => array(
			'type'=>'POST', 
			'url'=>CController::createUrl('City/GetDynamicState'), 
			'data'=>array('country_id'=>'js:this.value'),
			'update'=>'#City_state_id', 
			)));?>
			<?php echo $form->error($model,'country_id'); ?>
			<?php if(isset($model->state) && $model->state->status=='0'){?><div class="errorMessage">State <b><?php echo $model->state->state."</b> is been deleted. Add new.";?></div><?php }?>
		</div>
	</div>	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'state_id',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'state_id', $state,array('empty'=>'-- Select State --','class'=>'form-control input-medium') );?>
			<?php echo $form->error($model,'state_id'); ?>
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-md-offset-2 col-md-9">
			<button class="btn btn-default marginR10" type="submit">Save changes</button>
			<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/city/admin'">Cancel</button>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>