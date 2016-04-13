<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label'=>'Add Blog', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}	
	if(in_array('View',$user_rights)){	
		array_push($this->menu, array('label'=>'View Blog', 'url'=>array('view', 'id'=>$model->blog_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'));
	}
	if(in_array('Admin',$user_rights)){	
		array_push($this->menu, array('label'=>'List Blog', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label'=>'Delete Blog', 'url'=>'#', 'htmlOptions'=>array('class'=>'btn-default btn-sm','submit'=>array('delete','id'=>$model->blog_id),'confirm'=>'Are you sure you want to delete this item?'),'icon' => 'trash','icon' => 'trash'));
	}
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Update Blog</h4>
			</div>
		<div class="panel-body">
			<div class="margin-bottom-10 ">
				<?php
					$this->widget(
						'bootstrap.widgets.TbButtonGroup',
						array(
							'buttons' => $this->menu,
						)
					); ?>			
			</div>
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>
	</div>
</div>