<?php
	$this->menu=array(
		array('label'=>'Create Permission', 'url'=>array('create')),
		array('label'=>'View Permission', 'url'=>array('view', 'id'=>$model->permission_id)),
		array('label'=>'Manage Permission', 'url'=>array('admin')),
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Permission</h4>
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