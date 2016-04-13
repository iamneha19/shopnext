<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Locality', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Locality', 'url'=>array('update', 'id'=>$model->locality_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Locality', 'url'=>'#','icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->locality_id),'confirm'=>'Are you sure you want to delete this item?')));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Locality', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4> View Locality </h4>
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
					'locality_id',
					'locality',
					'city.city',
					'city.state.state',
					'city.state.country.country',
					'latitude',
					'longitude',
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
