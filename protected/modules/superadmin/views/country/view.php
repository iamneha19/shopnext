<?php
	$user_rights = $this->getAccessRule();
	
	$this->breadcrumbs=array(
		'Countries'=>array('index'),
		$model->country_id,
	);

	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Country', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Country', 'url'=>array('update', 'id'=>$model->country_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Country', 'url'=>'#','icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->country_id),'confirm'=>'Are you sure you want to delete this item?')));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Country', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Country</h4>
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
					'country_id',
					'country',
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