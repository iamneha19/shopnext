<?php
	$user_rights = $this->getAccessRule();
	$this->menu = array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Owner', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('View',$user_rights)){
		array_push($this->menu, array('label'=>'View Owner', 'url'=>array('view', 'id'=>$model->owner_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Owner', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}

?>
<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Owner</h4>
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
			<?php $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>
	</div>
</div>

