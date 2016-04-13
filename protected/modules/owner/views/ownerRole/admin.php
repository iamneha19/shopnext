<?php
/* @var $this OwnerRoleController */
/* @var $model ownerRole */
$owner_rights = $this->getAccessRule();

// if(in_array('Create',$owner_rights))
// {
	$this->menu=array(
		array('label'=>'Add Role', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
	);
// }
	
$template = "";

// if(in_array('View',$owner_rights))
// {
	$template .=" {view} ";
// }

// if(in_array('Update',$owner_rights))
// {
	$template .=" {update} ";
// }

// if(in_array('AssignPermission',$owner_rights))
// {
	$template .=" {manage_permission} ";
// }
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>
				Manage Owner Roles			</h4>
		</div>				
		<div class="panel-body noPad clearfix">
			<?php 
				$this->widget(
					'bootstrap.widgets.TbButtonGroup',
					array(
						'buttons' => $this->menu,
					)
				);
			?>
			<span class = "pull-lefft">
							<div class="btn-group">
					<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					Action <i class="fa fa-angle-down"></i>
					</button>
				</div>
			</span>
			<span class = "pull-right">
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'owner-role-grid',
				'type'=>'striped bordered condensed hover',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
							'header'=>'Sr. No.',
							'htmlOptions'=>array('class'=>'w45'),
							'class'=>'CounterColumn'
						),
						'name',
						array(
								'name'=>'added_on',
								'value'=>'($data->added_on == "") ? "Not Set" : Controller::dobConvert($data->added_on)',
								'filter'=>false,
						),
						/*
						'status',
						*/
						array(
								'class'=>'CButtonColumn',
								'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
								'template'=>$template,
								'buttons'=>array
								(
									'manage_permission' => array
									(
										'url'=>'Yii::app()->createUrl("owner/ownerRole/assignPermission", array("id"=>$data->owner_role_id))',
										 'options'=>array(
												'id'=>'$data->owner_role_id',
											),
										'imageUrl'=>Yii::app()->theme->baseUrl."/img/user-permission.png", 
										'label' =>'Manage Admin Permission'    
									),
								   
								),
								'htmlOptions'=>array('style'=>'width:100px;')
							  ),
				),
			)); ?>
		</div>
	</div>
</div>
