<?php
	$user_rights = $this->getAccessRule();
	if(in_array('Create',$user_rights)){
		$this->menu=array(
			array('label'=>'Add State', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
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
			<h4>List States</h4>
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
				'id'=>'state-grid',
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
					'state',
					array(
						'name'=>'country_id',
						'value'=>'$data->country->country',
						'filter' =>CHtml::activeDropDownList($model,'country_id',CHtml::listData(Country::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'country')), 'country_id', 'country'),array('prompt'=>'-- Country --')),
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

