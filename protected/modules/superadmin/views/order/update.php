<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('View',$user_rights)){
		array_push($this->menu, array('label' => 'View Order', 'url'=>array('view', 'id'=>$model->order_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label' => 'List Order', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Order', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->order_id),'confirm'=>'Are you sure you want to delete this item?'),'icon' => 'trash','icon' => 'trash'));
	}
?>
<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Order</h4>
		</div>
		<div class="panel-body">
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
			<div class="panel-body">
				<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
			</div>
		</div>
	</div>
</div>