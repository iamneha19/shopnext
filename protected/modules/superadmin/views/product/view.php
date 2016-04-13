<?php
	$user_rights = $this->getAccessRule();
	if($model->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$model->productImage->image)){ 
		$image 	   = Yii::app()->baseUrl."/upload/product/".$model->productImage->image;	
	}else{
		$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";	
	}
	
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label' => 'Add Product', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label' => 'Update Product', 'url'=>array('update', 'id'=>$model->product_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label' => 'Manage images', 'url'=>array('manageImages', 'id'=>$model->product_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'icon-picture'));
		array_push($this->menu, array('label' => 'List Product', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label' => 'Delete Product', 'url' => '#','htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->product_id),'confirm'=>'Deleting this would delete all the records corresponding to this product, such as product images & comments, Are you sure to continue?'),'icon' => 'trash',));
	}
	
	if($model->active_status=="H")
	{
		$array = array_push($this->menu, array('label'=>'Active Product', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->product_id,'active_status'=>"S"),'confirm'=>'Are you sure to activate this product?')));
	}else{
		$array = array_push($this->menu, array('label'=>'Deactive Product', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->product_id,'active_status'=>"H"),'confirm'=>'Are you sure to deactivate this product?')));
	}

?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Product</h4>
		</div>

		<div class="panel-body noPad clearfix">
			<div class="margin-bottom-10 ">
				<?php 
					$this->widget(
						'bootstrap.widgets.TbButtonGroup',
						array(
							'buttons' => $this->menu,
						)
					);
				?>				
			</div>	
			<?php $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$model,
				'type'=>'striped bordered condensed',
				'attributes'=>array(
					'product_id',					
					'name',
					array(
						'name'=>'productCategory.product_category',
						'type'=>'raw',
						'value'=>CHtml::link($model->productCategory->product_category,array('productCategory/view','id'=>$model->product_category_id), array('target'=>'_blank')) ,
					),
					array(
						'name'=>'shop.name',
						'type'=>'raw',
						'value'=>CHtml::link($model->shop->name,array('shop/view','id'=>$model->shop_id), array('target'=>'_blank')) ,
					),	
					array(
						'name'=>'price',
						'value'=>number_format($model->price,2)." INR",
					),
					'discount',
					array(
						'name'=>'discount_type',
						'value'=>($model->discount_type == "P") ? "Percentage" :"Rupees",
					),
					'description',	
					array(
						'name'=>'product_image_id',
						'type' => 'raw',
						'value'=>CHtml::image($image, $model->product_image_id,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
					),
					array(
						'name'=>'online',
						'value'=>($model->online == "Y") ? "Yes" :"No",
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status == "S") ? "Active" :"Deactive",
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
