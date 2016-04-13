<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'View Comment', 'url'=>array('view', 'id'=>$model->comment_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'));	
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label'=>'List Comment', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Comment', 'url'=>'#','icon'=>'trash', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->comment_id),'confirm'=>'Are you sure you want to delete this item?')));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Comment</h4>
		</div>
		<div class="panel-body">
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
			<div class="panel-body">
				<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
			</div>
		</div>
	</div>
</div>