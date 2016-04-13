<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if($model->order_status!="C")
	{
		if(in_array('Update',$user_rights)){
			array_push($this->menu, array('label' => 'Update Order', 'url'=>array('update', 'id'=>$model->order_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
		}
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label' => 'Delete Order', 'url' => '#','htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->order_id),'confirm'=>'Are you sure you want to delete this item?'),'icon' => 'trash',));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label' => 'List Order', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Order</h4>
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
		<div class="panel-body noPad clearfix">
			<?php $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$model,
				'type'=>'striped bordered condensed',
				'attributes'=>array(
					'order_id',
					'order_no',
					array(
						'name'=>'shop.name',
						'type'=>'raw',
						'value'=>CHtml::link($model->shop->name,array('shop/view','id'=>$model->shop_id), array('target'=>'_blank')) ,
					),
					array(
						'name'=>'product.name',
						'type'=>'raw',
						'value'=>CHtml::link($model->product->name,array('product/view','id'=>$model->product_id), array('target'=>'_blank')) ,
					),
					'user.name',					
					'quantity',
					array(
						'name'=>'unit_price',
						'value'=>number_format($model->unit_price,2)." INR",
					),
					array(
						'name'=>'sub_total',
						'value'=>number_format($model->sub_total,2)." INR",
					),
					array(
						'name'=>'order_status',
						'value'=>($model->order_status!='') ? $model->getOptions($model->order_status):"",
					),
					array(
						'name'=>'added_on',
						'value'=>($model->added_on == '') ? "Not Set" :Controller::dateConvert($model->added_on),
					),
					array(
						'name'=>'updated_on',
						'value'=>($model->updated_on == '') ? "Not Set" :Controller::dateConvert($model->updated_on),
					),
				),
			)); ?>
		</div>
	</div>
</div>
