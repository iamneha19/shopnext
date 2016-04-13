<?php
	$this->menu=array(
		array('label'=>'Create Permission', 'url'=>array('create')),
		array('label'=>'Update Permission', 'url'=>array('update', 'id'=>$model->permission_id)),
		array('label'=>'Delete Permission', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->permission_id),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Manage Permission', 'url'=>array('admin')),
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Permission</h4>
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
					'permission_id',
					'role_id',
					'permission_name',
					'all_permission',
					'active_status',
					'added_on',
					'updated_on',
					'status',
							),
				)); 
			?>
		</div>
	</div>
</div>
