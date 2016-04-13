<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs=array(
	'Shops'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Shop', 'url'=>array('create')),
	array('label'=>'Update Shop', 'url'=>array('update', 'id'=>$model->shop_id)),
	array('label'=>'Delete Shop', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->shop_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Shop', 'url'=>array('admin')),
);
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Shop</h4>
		</div>
		<div class="operations_ads row">
			<div id="sidebar"  class="operation_potlets">
				<?php
					$this->beginWidget('zii.widgets.CPortlet');
					$this->widget('zii.widgets.CMenu', array(
						'items'=>$this->menu,
						'htmlOptions'=>array('class'=>'operations'),
					));
					$this->endWidget(); ?>			</div><!-- sidebar -->
		</div>
		<div class="panel-body noPad clearfix">
			<?php $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$model,
				'type'=>'striped bordered condensed',
				'attributes'=>array(
					'shop_id',
		'category_id',
		'user_id',
		'name',
		'contact_no',
		'description',
		'address',
		'locality_id',
		'city_id',
		'state_id',
		'latitude',
		'longitude',
		'zip_code',
		'shop_image_id',
		'rating',
		'total_comments',
		'active_status',
		'added_on',
		'updated_on',
		'status',
				),
			)); ?>
		</div>
	</div>
</div>
