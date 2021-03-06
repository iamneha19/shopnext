<?php
	$user_rights = $this->getAccessRule();
	if($model->profile_pic!='' && file_exists(Yii::app()->basePath."/../upload/owner/".$model->profile_pic)){
		$image = Yii::app()->baseUrl."/upload/owner/".$model->profile_pic;
	}else{
		$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
	}
	
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Owner', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Owner', 'url'=>array('update', 'id'=>$model->owner_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Owner', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Owner', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->owner_id),'confirm'=>'Are you sure you want to delete this item?'),'icon' => 'trash','icon' => 'trash'));
	}
	if($model->active_status=="H"){	
		array_push($this->menu,array('label'=>'Active', 'url' => '#','htmlOptions' => array('class'=>'btn-default btn-sm','onclick'=>'if(confirm("This action may block user\'s login access, Are you sure to perform this action?")) window.location="'.Yii::app()->createUrl('/owner/owner/activate', array('id' => $model->owner_id,'is_approve'=>'S')).'";'),'icon' => 'check',));
	}else{	
		array_push($this->menu,array('label'=>'Deactive', 'url' => '#','htmlOptions' => array('class'=>'btn-default btn-sm','onclick'=>'if(confirm("This action may unblock user\'s login access, Are you sure to perform this action?")) window.location="'.Yii::app()->createUrl('/owner/owner/deactivate', array('id' => $model->owner_id,'is_approve'=>'H')).'";'),'icon' => 'ban',));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Profile</h4>
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
								'owner_id',
								array(
									'name'=>'owner_role_id',
									'value'=>$model->ownerRole->name,
								),
								'name',
								'email',
								'username',
								array(
									'name'=>'created_by',
									'value'=>($model->createdBy!='') ?$model->createdBy->name:'',
								),
								array(
									'name'=>'active_status',
									'value'=>($model->active_status=="S")?"Active":"Deactive",
								),
								array(
									'name'=>'profile_pic',
									'type' => 'raw',
									'value'=>CHtml::image($image, $model->profile_pic,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
								),
							),
			)); ?>
		</div>
	</div>
</div>

