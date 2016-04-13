<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'order-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
		
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'order_no',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->order_no))?$model->order_no:'';?>			
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'shop_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo (!empty($model->shop_id))? CHtml::link($model->shop->name,array('shop/view','id'=>$model->shop_id), array('target'=>'_blank')) :'';?>
				<?php echo $form->error($model,'shop_id'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'product_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
				<?php echo (!empty($model->product_id))? CHtml::link($model->product->name,array('product/view','id'=>$model->product_id), array('target'=>'_blank')) :'';?>
				<?php echo $form->error($model,'product_id'); ?>
			</div>
		</div>
		
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'user_id',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->user_id))?$model->user->name:'';?>			
			</div>
		</div>
		
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'quantity',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->quantity))?$model->quantity:'';?>			
			</div>
		</div>
		
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'unit_price',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->unit_price))?$model->unit_price:'';?>			
			</div>
		</div>
	
		<div class="form-group row" >
			<?php echo $form->labelEx($model,'sub_total',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">			
				<?php echo (!empty($model->sub_total))?$model->sub_total:'';?>			
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'order_status',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-md-4">
			<?php if($model->order_status == 'C'){ 
			      echo 'Complete';
			?>
			
			<?php }else{ ?>
				<?php echo $form->dropDownList($model,'order_status', array('P'=>'Pending','PR'=>'Processing', 'I'=>'InTransit', 'C'=>'Complete'), array('class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'order_status'); ?>
			<?php } ?>	
			</div>
		</div>
		
		
		<div class="form-group row">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/order/admin'">Cancel</button>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>