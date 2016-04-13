<?php
	
	if($model->banner!='' && file_exists(Yii::app()->basePath."/../upload/banner/".$model->banner)){ 
		$image 	   = Yii::app()->baseUrl."/upload/banner/".$model->banner;	
	}else{
		$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";	
	}
	if($model->active_status=="H")
	{
		$array = array('label'=>'Active Banner', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->banner_id,'active_status'=>"S"),'confirm'=>'Are you sure to activate this banner?'));
	}else{
		$array = array('label'=>'Deactive Banner', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->banner_id,'active_status'=>"H"),'confirm'=>'Are you sure to deactivate this banner?'));
	}
	
	$this->menu=array(
	array('label' => 'Add Banner', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
	
	array('label' => 'Update Banner', 'url'=>array('update', 'id'=>$model->banner_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'),
	
	array('label' => 'List Banner', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'),
	
	array('label' => 'Delete Banner', 'url' => '#','htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->banner_id),'confirm'=>'Deleting this would delete all the records corresponding to this banner, such as banner images, Are you sure to continue?'),'icon' => 'trash'),
	
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Banner</h4>
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
					'banner_id',					
					'location',
					array(
							'name'=>'type',
							'value'=>($model->type == "I") ? "Image" :"Code",
						),					
					array(
						'name'=>'banner',
						'type' => 'raw',
						'value'=>CHtml::image($image, $model->banner,array("width"=>"200px" ,"height"=>"150px", "class"=>"thumbnail")),
						'visible'=>($model->type == "I") ? "1" :"0"
					),
					array(
						'name'=>'code',
						'value'=>$model->code,
						'visible'=>($model->type == "C") ? "1" :"0"
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status == "S") ? "Active" :"Deactive",
					),
					array(
						'name'=>'added_on',
						'value'=>($model->added_on == '') ? "Not Set" : Controller::dateConvert($model->added_on),
					),
					array(
						'name'=>'updated_on',
						'value'=>($model->updated_on == '') ? "Not Set" : Controller::dateConvert($model->updated_on),
					),
				),
			)); ?>
		</div>
	</div>
</div>
