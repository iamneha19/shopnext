<?php
/* @var $this ProductCategoryController */
/* @var $model ProductCategory */


	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Product Category', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Product Category', 'url'=>array('update', 'id'=>$model->product_category_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Product Category', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Product Category', 'url'=>'#','htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->product_category_id),'confirm'=>'Are you sure you want to delete this item?')));	
	}

?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Product Category</h4>
		</div>
		<div class="panel-body noPad clearfix">
			<div class="margin-bottom-10 ">
				<?php
					$this->widget(
						'bootstrap.widgets.TbButtonGroup',
						array(
							'buttons' => $this->menu,
						)
					); ?>
			</div>		
			<?php $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$model,
				'type'=>'striped bordered condensed',
				'attributes'=>array(
					'product_category_id',
					'product_category',
					array(
							'name'=>'created_by',
							'value'=>($model->created_by == "") ? "Not set" : $model->createdBy->name,
						),
						array(
							'name'=>'active_status',
							'value'=>($model->active_status == "S") ? "Active" : "Deactive",
						),
						array(
							'name'=>'added_on',
							'value'=>($model->added_on == '') ? "Not Set" : Controller::dateConvert($model->added_on),
						),
						array(
							'name'=>'updated_on',
							'value'=>($model->updated_on == '') ? "Not Set" : Controller::dateConvert($model->updated_on),
						),
				),
			)); ?>
		</div>
	</div>
</div>
