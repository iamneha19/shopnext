<?php
	$user_rights = $this->getAccessRule();
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
	$template .=" {activate} {deactivate}";
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>
				List Blog Comments			</h4>
		</div>
		<div class="panel-body noPad clearfix">	
			<span class = "pull-leftt">
				<?php 
					$this->widget(
						'bootstrap.widgets.TbButtonGroup',
						array(
							'buttons' => $this->menu,
						)
					);
				?>
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
					Action <i class="fa fa-angle-down"></i>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="#" onclick="changeStatus('S');return false;">
							Approved </a>
						</li>
						<li>
							<a href="#"  onclick="changeStatus('H');return false;">
							Disapproved</a>
						</li>
						<?php if(in_array('Delete',$user_rights)){ ?>
							<li>
								<a href="#"  onclick="changeStatus('D');return false;">
								Delete</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</span>
			<span class = "pull-right">
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'blogComment-grid',
				'pagerCssClass' => 'col-md-12',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'class'=>'CCheckBoxColumn',
						'selectableRows'=>2,
						'checkBoxHtmlOptions'=>array('name'=>'blog_comment_id_arr[]'),
					),
					array('header'=>'Sr. No.',
						  'htmlOptions'=>array('class'=>'w45'),
						   'class'=>'CounterColumn'
						),						
						'comment',
						array(
							'name'=>'blog_id',
							'value'=>'($data->blog_id != "") ? $data->blog->title : ""',
							'filter' => CHtml::dropDownList(
								'BlogComment[blog_id]',
								$model->blog_id,
								CHtml::listData(Blog::model()->findAll(array('condition'=>'status="1"','order'=>'title')),'blog_id','title'),array('empty' => '-- All --')
							),
					
						),
						array(
							'name'=>'user_id',
							'value'=>'($data->user_id != "") ? $data->user->name : ""',
							'filter' => CHtml::dropDownList(
								'BlogComment[user_id]',
								$model->user_id,
								CHtml::listData(User::model()->findAll(array('condition'=>'status="1"','order'=>'name')),'user_id','name'),array('empty' => '-- All --')
							),
					
						),
						array(
						'name'=>'active_status',
						'value'=>'($data->active_status == "S") ? "Approved" :"Disapproved"',
						'filter' => CHtml::dropDownList(
										'BlogComment[active_status]',
										$model->active_status,
										array("S" => "Approved","H"=>"Disapproved"),array('empty' => '-- All --')),
						),
						
					array
					(
						'class'=>'CButtonColumn',
						'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
						'template'=>$template,
						'buttons'=>array
						(								   
							'activate' => array
							(
								'url'=>'Yii::app()->createUrl("superadmin/blogComment/setstatus",array("id"=>$data->blog_comment_id,"active_status"=>"S"))',
									 'options'=>array(
											'id'=>'$data->active_status',
											'onClick'=>'return confirm("Are you sure to approved Blog comment?")'
										),
								'imageUrl'=>Yii::app()->theme->baseUrl.'/img/active-icon.png', 
								'label' =>'Approved',
								'visible'=>'$data->active_status=="S"? 0:1'
							),
							'deactivate' => array
							(
								'url'=>'Yii::app()->createUrl("superadmin/blogComment/setstatus",array("id"=>$data->blog_comment_id,"active_status"=>"H"))',
									 'options'=>array(
											'id'=>'$data->active_status',
											'onClick'=>'return confirm("Are you sure to disapproved Blog comment?")'
										),
								'imageUrl'=>Yii::app()->theme->baseUrl.'/img/inactive-icon.png', 
								'label' =>'Disapproved',
								'visible'=>'$data->active_status!="S"? 0:1'
							),
							
						),
						'htmlOptions'=>array('style'=>'width:100px;')
					),
				),
			)); ?>
		</div>
	</div>
</div>

<script>
	function changeStatus(status)
	{
		var check = $("input[name='blog_comment_id_arr[]']:checked");
		
		if(check.length>0)
		{
			bootbox.confirm("Are you sure to perform this action?", function(result) {
				if(result)
				{
					Metronic.blockUI({message: 'Processing please wait ...'});
					qry = check.serialize();
					$.ajax({
						type:'POST',
						url:'changestatus',
						data:qry+'&status='+status,
						success:function(result) 
						{
							var obj = jQuery.parseJSON( result );	
							Metronic.unblockUI();
							if(obj.success==false){
								bootbox.alert("An error occured !! \n Please try after some time.");    
							} else {
								window.location.reload();
							}	
							
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) { 
							Metronic.unblockUI();	
							bootbox.alert("An error occured !! \n Please try again after reloading the page.");    
						} 
					});
					
				}					
			});
			
		}else{
			bootbox.alert("No comments selected !!!");    
		}
	}
</script>
