<?php
	$user_rights = $this->getAccessRule();
	
	if(in_array('Create',$user_rights)){
		$this->menu=array(
			array('label'=>'Add City', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
		);
	}
	$template = "";
	if(in_array('View',$user_rights)){
		$template .=" {view} ";
	}
	if(in_array('Update',$user_rights)){
		$template .=" {update} ";
	}
	if(in_array('Delete',$user_rights)){
		$template .=" {delete} ";
	}
	
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>List Cities</h4>
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
			<span class = "pull-right">
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'city-grid',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					 array('header'=>'Sr. No.',
						  'htmlOptions'=>array('class'=>'w45'),
						   'class'=>'CounterColumn'
					), 
					'city',
					array(
						'name'=>'state_id',
						'value'=>'$data->state->state',
						'filter' =>CHtml::activeDropDownList($model,'state_id',CHtml::listData(State::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'state')), 'state_id', 'state'),array('prompt'=>'-- State --')),
					),
					array(
						'name'=>'added_on',
						'value'=>'($data->added_on == "") ? "Not Set" :Controller::dateConvert($data->added_on)',
						'filter'=>false,
					),
					array(
						'class'=>'CButtonColumn',
						'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
						'template'=>$template,
					),
				),
			)); ?>
		</div>
	</div>
</div>

