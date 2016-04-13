<?php
/* @var $this ProductController */
/* @var $data Product */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->product_id), array('view', 'id'=>$data->product_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_category_id')); ?>:</b>
	<?php echo CHtml::encode($data->product_category_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shop_id')); ?>:</b>
	<?php echo CHtml::encode($data->shop_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('product_image_id')); ?>:</b>
	<?php echo CHtml::encode($data->product_image_id); ?>
	<br />

	<?php /*
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

	*/ ?>

</div>