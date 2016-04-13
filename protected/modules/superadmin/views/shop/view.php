<?php
	$user_rights = $this->getAccessRule();
	if($model->shop_image_id!='' && file_exists(Yii::app()->basePath."/../upload/shop/".$model->shopImage->image)){ 
		$image 	   = Yii::app()->baseUrl."/upload/shop/".$model->shopImage->image;	
	}else{
		$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";	
	}
	
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label' => 'Add Shop', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label' => 'Update Shop', 'url'=>array('update', 'id'=>$model->shop_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label' => 'Manage Images', 'url'=>array('manageImages', 'id'=>$model->shop_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'icon-picture'));
		array_push($this->menu, array('label' => 'List Shop', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label' => 'Delete Shop', 'url' => '#','htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->shop_id),'confirm'=>'Deleting this would delete all the records corresponding to shop, such as shop products, deals, & comments, Are you sure to continue?'),'icon' => 'trash',));
		
	}
	
	if($model->active_status=="H")
	{
		$array = array_push($this->menu, array('label'=>'Active Shop', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->shop_id,'active_status'=>"S"),'confirm'=>'Are you sure to activate this shop?')));
	}else{
		$array = array_push($this->menu, array('label'=>'Deactive Shop', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->shop_id,'active_status'=>"H"),'confirm'=>'Are you sure to deactivate this shop?')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Shop </h4>
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
					'shop_id',
					'name',
					array(
						'name'=>'category.category',
						'type'=>'raw',
						'value'=>CHtml::link($model->category->category,array('category/view','id'=>$model->category_id), array('target'=>'_blank')) ,
					),
					array(
						'name'=>'shop_image_id',
						'type' => 'raw',
						'value'=>CHtml::image($image, $model->shop_image_id,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
					),
					array(
						'name'=>'Name',
						'value'=>(!empty($model->admin->name)) ? $model->admin->name : $model->owner->name,
					),
					array(
					'name'=>'Created By',
					'value'=>(!empty($model->admin_id)) ? $model->admin->name." (Admin)" : $model->owner->name." (Owner)",
						
					),
					'contact_no',
					'description',
					'address',
					'locality.locality',
					'city.city',
					'state.state',
					'zip_code',
					'latitude',
					'longitude',
					'rating',					
					array(
						'name'=>'mark_invalid',
						'value'=>($model->mark_invalid == "1") ? "Yes" :"No",
					),
					array(
						'name'=>'invalid_remarks',
						'value'=>($model->mark_invalid == "1") ? $model->invalid_remarks : "Not Set",
					),
					array(
						'name'=>'home_delivery',
						'value'=>($model->home_delivery == "Y") ? "Yes" :"No",
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status == "S") ? "Active" :"Deactive",
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

