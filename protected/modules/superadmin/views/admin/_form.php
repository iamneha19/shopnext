<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'admin-form',
			'enableAjaxValidation'=>false,
				'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>
		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'role_id',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'role_id', $role, array('empty'=>'-- Select --','class'=>'form-control input-large') );?>
				<?php echo $form->error($model,'role_id'); ?>
			</div>
		</div>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'name',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'name',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
		</div>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'email',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'email',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
		<?php if(Yii::app()->controller->action->id == 'create'){ ?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'password',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
		</div>
		<?php } ?>
		<?php 
			if($model->profile_pic!='' && file_exists(Yii::app()->basePath."/../upload/admin/".$model->profile_pic)){ 
				$image 	   = Yii::app()->baseUrl."/upload/admin/".$model->profile_pic;
				$profile_pic = "fileinput-exists";
			}else{
				$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
				$profile_pic = "fileinput-new";
			}
		?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'profile_pic',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<div class="fileinput <?php echo $profile_pic?>" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
						<?php echo CHtml::image("http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",$model->profile_pic) ;?>
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
						<?php echo CHtml::image($image,$model->profile_pic) ;?>
					</div>
					<div>
						<span class="btn green btn-file">
						<span class="fileinput-new">Select image </span>
						<span class="fileinput-exists">	Change </span>
							<?php echo $form->fileField($model,'profile_pic'); ?>
						</span>
						<a href="#" class="btn blue fileinput-exists" data-dismiss="fileinput">
							Remove 
						</a>					
					</div>
					<?php echo $form->hiddenField($model, 'profile_pic'); ?>
				</div>		
			</div>
		</div>		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'active_status', array('S'=>'Active','H'=>'Deactive'), array('empty'=>'-- Select --','class'=>'form-control input-large') );?>
				<?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>
		<div class="form-group row buttons">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/admin/admin'">Cancel</button>
			</div>
		</div>
	<?php $this->endWidget(); ?>

</div>

<?php $cs = Yii::app()->getClientScript();$baseUrl = Yii::app()->theme->baseUrl; ?>
<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>

<script>
	$('.fileinput').fileinput().on('clear.bs.fileinput',function(){
		$('#Admin_profile_pic').val('');
	});
</script>
