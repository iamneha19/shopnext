<?php
	if($model->profile_pic!='' && file_exists(Yii::app()->basePath."/../upload/admin/".$model->profile_pic)){
		$image = Yii::app()->baseUrl."/upload/admin/".$model->profile_pic;
	}else{
		$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
	}
	$this->menu=array(
		array('label'=>'Update Profile', 'url'=>array('update'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'),
	);
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
					'name',
                                        'email',
                                        'username',
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
