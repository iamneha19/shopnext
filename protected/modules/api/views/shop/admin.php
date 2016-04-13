<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs=array(
	'Shops'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Shop', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#shop-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>
				Manage Shops			</h4>
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
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'shop-grid',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'shop_id',
		'category_id',
		'user_id',
		'name',
		'contact_no',
		'description',
		/*
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
		*/
					array(
						'class'=>'CButtonColumn',
					),
				),
			)); ?>
		</div>
	</div>
</div>
