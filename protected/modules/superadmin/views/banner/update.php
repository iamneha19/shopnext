<?php
	
	$this->menu=array(
	array('label' => 'Add Banner', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
	array('label' => 'View Banner', 'url'=>array('view', 'id'=>$model->banner_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'),	
	array('label' => 'List Banner', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'),
	array('label' => 'Manage Images', 'url'=>array('manageImages', 'id'=>$model->banner_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'icon-picture'),
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Banner</h4>
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
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>		
		</div>
	</div>
</div>