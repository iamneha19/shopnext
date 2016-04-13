<?php
	$this->menu=array(
		array('label'=>'Create Permission', 'url'=>array('create')),
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>
				Manage Permissions
			</h4>
		</div>
		<div class="operations_ads row">
			<div id="sidebar"  class="operation_potlets">
				<?php
					$this->beginWidget('zii.widgets.CPortlet');
					$this->widget('zii.widgets.CMenu', array(
						'items'=>$this->menu,
						'htmlOptions'=>array('class'=>'operations'),
					));
					$this->endWidget(); ?>
			</div><!-- sidebar -->
		</div>
		<div class="panel-body noPad clearfix">
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'permission-grid',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					'permission_id',
					'role_id',
					'permission_name',
					'all_permission',
					'active_status',
					'added_on',
					 array(
						'class'=>'CButtonColumn',
						'template'=>'{view}  {update}  {manage_permission}  {activate}  {delete}',
						'buttons'=>array
						(
							'manage_permission' => array
							(
								'url'=>'Yii::app()->createUrl("superadmin/permission/assignPermission", array("id"=>$data->permission_id))',
								 'options'=>array(
										'id'=>'$data->permission_id',
									),
								'imageUrl'=>Yii::app()->theme->baseUrl."/img/user-permission.png", 
								'label' =>'Manage Advocate Permission'    
							),
						   
						),
						'htmlOptions'=>array('style'=>'width:100px;')
					),
				),
			)); ?>
		</div>
	</div>
</div>
