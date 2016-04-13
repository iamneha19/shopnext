<?php
	$user_rights = $this->getAccessRule();
	
	$this->menu=array();
		//array('label'=>'Add Comment', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update Comment', 'url'=>array('update', 'id'=>$model->comment_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Comment', 'url'=>'#','icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->comment_id),'confirm'=>'Are you sure you want to delete this item?')));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Comment', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	
	if($model->active_status=="H")
	{
		$array = array_push($this->menu, array('label'=>'Approve Comment', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->comment_id,'active_status'=>"S"),'confirm'=>'Are you sure to approve this comment?')));
	}else{
		$array = array_push($this->menu, array('label'=>'Disapprove Comment', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->comment_id,'active_status'=>"H"),'confirm'=>'Are you sure to disapprove this comment?')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View Comment</h4>
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
		<div class="panel-body noPad clearfix">
			<?php $this->widget('bootstrap.widgets.TbDetailView', array(
				'data'=>$model,
				'type'=>'striped bordered condensed',
				'attributes'=>array(
					'comment_id',
					'comment',
					array(
						'name'=>'deal_id',
						'type'=>'raw',
						'value'=>CHtml::link($model->deal->title,array('deal/view','id'=>$model->deal_id), array('target'=>'_blank')) ,
					),
					'user.name',
					array(
						'name'=>'type',
						'value'=>($model->active_status == 'P') ? "Is parent comment" :"Reply on comment",
					),
					array(
						'name'=>'parent_id',
						'type'=>'raw',
						'value'=>($model->parent_id != '') ? CHtml::link($model->parent->comment,array('shopComment/view','id'=>$model->parent_id), array('target'=>'_blank')):"",
					),
					array(
						'name'=>'shop.name',
						'type'=>'raw',
						'value'=>CHtml::link($model->deal->shop->name,array('shop/view','id'=>$model->deal->shop->shop_id), array('target'=>'_blank')),
					),
					array(
						'name'=>'type',
						'value'=>($model->active_status == 'P') ? "Parent" :"Response",
					),
					array(
						'name'=>'active_status',
						'value'=>($model->active_status == 'S') ? "Active" :"Inactive",
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
