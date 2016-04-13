<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'comment-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
		
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'user_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->user_id))?$model->user->name:'';?>			
			</div>
		</div>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'deal_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo (!empty($model->deal_id))? CHtml::link($model->deal->title,array('deal/view','id'=>$model->deal_id), array('target'=>'_blank')) :'';?>
				<?php echo $form->error($model,'shop_id'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'shop_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo (!empty($model->deal->shop->name))? CHtml::link($model->deal->shop->name,array('shop/view','id'=>$model->deal->shop->shop_id), array('target'=>'_blank')) :'';?>
				<?php echo $form->error($model,'shop_id'); ?>
			</div>
		</div>
		
		<?php if($model->parent_id!='') { ?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'parent_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo  CHtml::link($model->parent->comment,array('shopComment/view','id'=>$model->parent_id), array('target'=>'_blank')) ;?>
				<?php echo $form->error($model,'parent_id'); ?>
			</div>
		</div>
		<?php } ?>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'comment',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'comment'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model,'active_status', array('S'=>'Approved','H'=>'Disapproved'), array('class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>
		
		
		<div class="form-group row">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/comment/admin'">Cancel</button>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>