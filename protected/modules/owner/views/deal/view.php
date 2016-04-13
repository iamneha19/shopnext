<?php
	$user_rights = $this->getAccessRule();
	if($model->deal_image!='' && file_exists(Yii::app()->basePath."/../upload/deal/".$model->deal_image)){
		$image = Yii::app()->baseUrl."/upload/deal/".$model->deal_image;
	}else{
		$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
	}
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Deal', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		// if($model->end_date > strtotime("+3 days")){
			array_push($this->menu, array('label'=>'Update Deal', 'url'=>array('update', 'id'=>$model->deal_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
		// }	
		
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Deal', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Deal', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->deal_id),'confirm'=>'Deleting this record would delete all its comments, Are you sure to continue?'),'icon' => 'trash','icon' => 'trash'));
	}
	
	if($model->active_status=="H")
	{
		$array = array_push($this->menu, array('label'=>'Approved Deal', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->deal_id,'active_status'=>"S"),'confirm'=>'Are you sure to approved this deal?')));
	}else{
		$array = array_push($this->menu, array('label'=>'Disapproved Deal', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->deal_id,'active_status'=>"H"),'confirm'=>'Are you sure to disapproved this deal?')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Deal</h4>
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
					'deal_id',
					array(
						'name'=>'shop.name',
						'type'=>'raw',
						'value'=>CHtml::link($model->shop->name,array('shop/view','id'=>$model->shop_id), array('target'=>'_blank')) ,
					),
					'title',
					'desc',
					// 'code',
					array(
						'name'=>'start_date',
						'value'=>($model->start_date == '') ? "Not Set" :Controller::dobConvert($model->start_date),
					),
					array(
						'name'=>'end_date',
						'value'=>($model->end_date == '') ? "Not Set" :Controller::dobConvert($model->end_date),
					),
					'amount',
					array(
						'name'=>'type',
						'value'=>($model->type=="P")?"Percentage":"Rupees",
					),
					array(
						'name'=>'deal_image',
						'type' => 'raw',
						'value'=>CHtml::image($image, $model->deal_image,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status=="S")?"Approved":"Disapproved",
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
