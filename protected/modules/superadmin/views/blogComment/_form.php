<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'blog-comment-form',
		'enableAjaxValidation'=>false,
		)); ?>

		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>

		<?php 
			$div_prnt_id = "display:none";
			$div_blog_id="display:none";
			if($model->parent_id!=''){$div_prnt_id="";}
			if($model->blog_id!=''){$div_blog_id="";}
		?>
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'user_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->user_id))?$model->user->name:'';?>			
			</div>
		</div>
		
		<div class="form-group row" id="div-blog-id" style="<?php echo $div_blog_id;?>">
			<?php echo $form->labelEx($model,'blog_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				
				<?php echo (!empty($model->blog_id))? $model->blog->title:'';?>
				
				<?php echo $form->error($model,'blog_id'); ?>
			</div>
		</div>
		
		<div class="form-group row" id="div-parent-id" style="<?php echo $div_prnt_id;?>">
			<?php echo $form->labelEx($model,'parent_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				
				<?php echo (!empty($model->parent_id))?$model->parent->comment:'';?>
				<?php echo $form->error($model,'parent_id'); ?>
			</div>
		</div>	
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'comment',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textArea($model,'comment',array('class'=>'form-control input-large','rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'comment'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model,'active_status', array('S'=>'Approve','H'=>'Disapprove'), array('class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/blogComment/admin'">Cancel</button>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>


