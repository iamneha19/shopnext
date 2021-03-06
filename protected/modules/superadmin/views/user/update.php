<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Create User', 'url'=>array('create')));
	}
	if(in_array('View',$user_rights)){
		array_push($this->menu, array('label'=>'View User', 'url'=>array('view', 'id'=>$model->user_id)));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'Manage User', 'url'=>array('admin')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update User</h4>
		</div>
		<div class="operations_ads row">
			<div id="sidebar"  class="operation_potlets">
				<?php
					$this->beginWidget('zii.widgets.CPortlet');
					$this->widget('zii.widgets.CMenu', array(
						'items'=>$this->menu,
						'htmlOptions'=>array('class'=>'operations'),
					));
					$this->endWidget(); ?>			
			</div>
		</div>
		<div class="panel-body">
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>		
		</div>
	</div>
</div>