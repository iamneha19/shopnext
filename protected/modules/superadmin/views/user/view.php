<?php
	$user_rights = $this->getAccessRule();
	if($model->profile_pic!='' && file_exists(Yii::app()->basePath."/../upload/user/".$model->profile_pic)){
		$image = Yii::app()->baseUrl."/upload/user/".$model->profile_pic;
	}else{
		$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
	}
	
	$this->menu=array();
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete User', 'url'=>'#', 'icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List User', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if($model->active_status=='S')	{
		$option_array = array_push($this->menu, array('label'=>'Disapprove User', 'url'=>'#','icon'=>'ban', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->user_id,'active_status'=>'H'),'confirm'=>'This action may unblock user\'s login access, Are you sure to perform this action?')));
	}else{
		$option_array = array_push($this->menu, array('label'=>'Approve User', 'url'=>'#','icon'=>'ban', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->user_id,'active_status'=>'S'),'confirm'=>'This action may block user\'s login access, Are you sure to perform this action?')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View User</h4>
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
					'user_id',
					'name',
					'email',
					array(
					'name'=>'dob',
					'value'=>(!empty($model->dob)) ? Controller::dobConvert($model->dob) :"Not Set",
					),
					'contact_no',
					array(
									'name'=>'profile_pic',
									'type' => 'raw',
									'value'=>CHtml::image($image, $model->profile_pic,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
								),
					'address',
					'locality.locality',
					'city.city',
					'state.state',
					array(
					'name'=>'country',
					'value'=>($model->country_id!='') ? $model->country->country :"Not Set",
					),
					array(
						'name'=>'gender',
						'value'=> ($model->gender!='') ? (($model->gender == 'M') ? "Male" : "Female") :"Not Set",
					),
					'username',
					array(
						'name'=>'send_newsletter',
						'value'=>($model->send_newsletter == 'Y') ? "Yes" : "No",
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status == 'S') ? "Active" : "Deactive",
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
