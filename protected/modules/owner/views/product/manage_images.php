<?php
	$user_rights = $this->getAccessRule();
	$this->menu=array();
	if(in_array('Create',$user_rights)){
		array_push($this->menu, array('label' => 'Add Product', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'));
	}
	if(in_array('Update',$user_rights)){
		array_push($this->menu, array('label' => 'Update Product', 'url'=>array('update', 'id'=>$model->product_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'));
	}
	if(in_array('Delete',$user_rights)){
		array_push($this->menu, array('label' => 'View Product', 'url'=>array('view', 'id'=>$model->product_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'));
	}
	if(in_array('Admin',$user_rights)){
		array_push($this->menu, array('label' => 'List Product', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'));
	}

?>
<script type="text/javascript">		
	$(document).ready(function()
	{				
		var id = '<?php if(!empty($_REQUEST['id'])){ echo $_REQUEST['id']; } ?>';		
		if(id!='')
		{
			$.ajax({
				type: 'POST',
				url: '<?php echo Yii::app()->createUrl("/owner/product/uploadData"); ?>',
				data:'id='+id,
				success:function(data)
				{
					$('#XUploadForm-form .files').html(data);					
				},
			});
		}		
	});
</script>

<div class="col-lg-12">			
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>Manage Product Images #<?php echo $model->name; ?></h4>
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
			<div class="panel panel-success">				
				<div class="panel-body">
					<ul>
						<li>
							 The maximum file size for uploads is <strong>5 MB</strong>.
						</li>
						<li>
							 Only image files (<strong>JPG, GIF, PNG</strong>) are allowed.
						</li>
						<li>
							 Uploaded files (if not saved) will be deleted automatically after <strong>5 minutes</strong>.
						</li>
					</ul>
					<div class="col-lg-12">
						<?php
							$this->widget('xupload.XUpload', array(
								'url' => Yii::app()->createUrl("owner/product/upload?id=".$model->product_id."&model=Product"),
								'model' => $multiple_image_model,
								'showForm'=> true,
								'attribute' => 'file',
								'multiple' => true,
								'htmlOptions' => array('filesContainer'=>'template-upload'),
							));
						?>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

