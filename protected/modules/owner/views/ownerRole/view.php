<?php
/* @var $this OwnerRoleController */
/* @var $model ownerRole */

$user_rights = $this->getAccessRule();
$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add ownerRole', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update ownerRole', 'url'=>array('update', 'id'=>$model->owner_role_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List ownerRole', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete ownerRole', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->owner_role_id),'confirm'=>'Deleting this record would delete all its comments, Are you sure to continue?'),'icon' => 'trash','icon' => 'trash'));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View ownerRole</h4>
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
					'owner_role_id',
		'name',
		array(
			'name'=>'created_by',
			'value'=>$model->createdBy->name,
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
