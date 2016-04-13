<?php
	$user_rights = $this->getAccessRule();
	
	$this->menu=array();
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label'=>'Update BlogComment', 'url'=>array('update', 'id'=>$model->blog_comment_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List BlogComment', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete BlogComment', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->blog_comment_id),'confirm'=>'Are you sure you want to delete this item?'),'icon' => 'trash'));	
	}
	if($model->active_status=="H")
	{
		$array = array_push($this->menu, array('label'=>'Approve Comment', 'url'=>'#','icon' => 'check',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->blog_comment_id,'active_status'=>"S"),'confirm'=>'Are you sure to approve this comment?')));
	}else{
		$array = array_push($this->menu, array('label'=>'Disapprove Comment', 'url'=>'#','icon' => 'ban',  'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('setstatus','id'=>$model->blog_comment_id,'active_status'=>"H"),'confirm'=>'Are you sure to disapprove this comment?')));
	}
	
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>View BlogComment</h4>
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
					'blog_comment_id',
					'blog.title',
					'user.name',					
					array(
			            'name'=>'parent_id',
						'type'=>'raw',
						'value'=>($model->parent_id == '') ? "Not Set" :CHtml::link($model->parent->comment,array('blogComment/view','id'=>$model->parent_id), array('target'=>'_blank')) ,
						
			        ),
					'comment',
					array(
			            'name'=>'active_status',
						'value'=>($model->active_status == 'S') ? "Approved" :"Disapproved",
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
