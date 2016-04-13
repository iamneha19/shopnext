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
		<?php echo $form->labelEx($model,'location',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textField($model,'location',array('maxlength'=>50,'size'=>60,'class'=>'form-control input-large')); ?>
			<?php echo $form->error($model,'location'); ?>
		</div>
	</div>
	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'type',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			<?php echo $form->dropDownList($model,'type', array('I'=>'Image','C'=>'Code'), array('class'=>'form-control input-small')); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
	</div>

	<div class="form-group row" id="code-input">
		<?php echo $form->labelEx($model,'code',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-lg-10">
			<?php echo $form->textArea($model,'code',array('maxlength'=>50,'size'=>60,'class'=>'form-control input-large')); ?>
			<?php echo $form->error($model,'code'); ?>
		</div>
	</div>
	
	<?php 
		if($model->banner_id!='' && file_exists(Yii::app()->basePath."/../upload/banner/".$model->banner)){ 
			$image 	   = Yii::app()->baseUrl."/upload/banner/".$model->banner;
			$cur_state = "fileinput-exists";
		}else{
			$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
			$cur_state = "fileinput-new";
		}
	?>
	<div class="form-group row" id="image-input">
		<?php echo $form->labelEx($model,'banner',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			<div class="fileinput <?php echo $cur_state?>" data-provides="fileinput">
				<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
					<?php echo CHtml::image("http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",$model->banner_id) ;?>
				</div>
				<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
					<?php echo CHtml::image($image,($model->banner_id!='') ?$model->banner:"") ;?>
				</div>
				<div>
					<span class="btn green btn-file">
					<span class="fileinput-new">Select image </span>
					<span class="fileinput-exists">	Change </span>
					<?php echo $form->fileField($model,'banner'); ?>
					</span>
					<a href="#" class="btn blue fileinput-exists" data-dismiss="fileinput">
					Remove </a>					
				</div>
				<?php echo $form->hiddenField($model, 'banner'); ?>
				<?php echo $form->error($model,'banner'); ?>
			</div>		
		</div>
	</div>
	
	<div class="form-group row">
		<?php echo $form->labelEx($model,'active_status',array('class'=>'col-md-2 control-label')); ?>
		<div class="col-md-6">
			
			<?php echo $form->dropDownList($model,'active_status', array('S'=>'Active','H'=>'Deactive'), array('class'=>'form-control input-small')); ?>
			<?php echo $form->error($model,'active_status'); ?>
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-md-offset-2 col-md-9">
			<button class="btn btn-default marginR10" type="submit">Save changes</button>
			<button class="btn btn-danger" type="button"  onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/banner/admin'">Cancel</button>
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
	$('.fileinput').fileinput().on('clear.bs.fileinput',function(){
		$('#Banner-_banner').val('');
	});
	
	if($('#Banner_type').val()=='I'){
		$('#image-input').show();
		$('#code-input').hide();
	}else{
		$('#image-input').hide();
		$('#code-input').show();
	}
	
   $('#Banner_type').change(function(){	
		if(this.value=='I'){
			$('#image-input').show();
			$('#code-input').hide();
		}else{
			$('#image-input').hide();
			$('#code-input').show();
		}
	});
});
</script>	