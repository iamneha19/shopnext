<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'order_id'); ?>
		<?php echo $form->textField($model,'order_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'order_no'); ?>
		<?php echo $form->textField($model,'order_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shop_id'); ?>
		<?php echo $form->textField($model,'shop_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'quantity'); ?>
		<?php echo $form->textArea($model,'quantity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unit_price'); ?>
		<?php echo $form->textField($model,'unit_price'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'sub_total'); ?>
		<?php echo $form->textField($model,'sub_total'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'order_status'); ?>
		<?php echo $form->textField($model,'order_status',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active_status'); ?>
		<?php echo $form->textField($model,'active_status',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'added_on'); ?>
		<?php echo $form->textField($model,'added_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_on'); ?>
		<?php echo $form->textField($model,'updated_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->