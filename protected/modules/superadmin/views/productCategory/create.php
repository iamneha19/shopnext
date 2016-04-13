<?php
/* @var $this ProductCategoryController */
/* @var $model ProductCategory */
	$user_rights = $this->getAccessRule();
	$this->breadcrumbs=array(
		'Product Categories'=>array('index'),
		'Create',
		);

	$this->menu=array();
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Product Category', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Add Product Category</h4>
		</div>
		<div class="panel-body">
			<div class="margin-bottom-10 ">
				<?php
					$this->widget(
						'bootstrap.widgets.TbButtonGroup',
						array(
							'buttons' => $this->menu,
						)
					); ?>			
			</div>
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>		</div>
	</div>
</div>
