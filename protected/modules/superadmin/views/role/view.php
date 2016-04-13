<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Role', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Role', 'url'=>array('update', 'id'=>$model->role_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
		//array('label'=>'Delete Role', 'url'=>'#','icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->role_id),'confirm'=>'Are you sure you want to delete this item?'));
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Role', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('AssignPermission',$user_rights)){
		array_push($this->menu, array('label'=>'Manage permissions', 'url'=>array('assignPermission','id'=>$model->role_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'icon-user-following'));
	}	
	
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Role</h4>
		</div>
		<div class="panel-body noPad clearfix">
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
					'role_id',
					'name',
						array(
							'name'=>'added_on',
							'value'=>($model->added_on == '') ? "Not Set" :Controller::dateConvert($model->added_on),
						),
						array(
							'name'=>'updated_on',
							'value'=>($model->updated_on == '') ? "Not Set" :Controller::dateConvert($model->updated_on),
						),
							),
						)); 
			?>
		</div>
	</div>
</div>
