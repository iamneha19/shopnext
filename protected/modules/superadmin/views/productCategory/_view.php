<?php
/* @var $this ProductCategoryController */
/* @var $data ProductCategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_category_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->product_category_id), array('view', 'id'=>$data->product_category_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_category')); ?>:</b>
	<?php echo CHtml::encode($data->product_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
	<?php echo CHtml::encode($data->created_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_status')); ?>:</b>
	<?php echo CHtml::encode($data->active_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('added_on')); ?>:</b>
	<?php echo CHtml::encode($data->added_on); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_on')); ?>:</b>
	<?php echo CHtml::encode($data->updated_on); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>