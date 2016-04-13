<?php

	$owner_id = ApplicationSessions::run()->read('owner_id');
	$created_by = ApplicationSessions::run()->read('created_by');
	
	if(empty($created_by)){
		$condition = 'owner_id="'.$owner_id.'" and status = "1"';
	}else{
		$condition = 'user_id="'.$owner_id.'" and status = "1"';
	}
	
	$user_rights = $this->getAccessRule();
	
	$template = "";
	if(in_array('View',$user_rights)){
		$template .=" {view} ";
	}
	// if(in_array('Update',$user_rights)){
		// $template .=" {update} ";
	// }
	// if(in_array('Delete',$user_rights)){
		// $template .=" {delete} ";
	// }
	// $template .=" {activate} {deactivate}";
	
	$owner_id = ApplicationSessions::run()->read('owner_id');
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>List Shop Comments</h4>
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
				<!--<div class="btn-group">
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
						<?php // if(in_array('Delete',$user_rights)){ ?>
							<li>
								<a href="#"  onclick="changeStatus('D');return false;">
								Delete</a>
							</li>
						<?php // } ?>
					</ul>
				</div>-->
			</span>		
			<span class = "pull-right">				
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'shopComment-grid',
				'type'=>'striped bordered condensed hover',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->shopSearch(),
				'filter'=>$model,
				'columns'=>array(
						array(
							'class'=>'CCheckBoxColumn',
							'selectableRows'=>2,
							'checkBoxHtmlOptions'=>array('name'=>'comment_id_arr[]'),
						),
						array(
							'header'=>'Sr. No.',
							'htmlOptions'=>array('class'=>'w45'),
							'class'=>'CounterColumn'
						), 
						'comment',
						array(
							'name'=>'shop_id',
							'value'=>'($data->shop_id!="") ? $data->shop->name:""',
							'filter' =>CHtml::activeDropDownList($model,'shop_id',CHtml::listData(Shop::model()->findAll(array('condition'=>$condition,'order'=>'name')), 'shop_id', 'name'),array('prompt'=>'-- Shops --')),
						),
						 array(
							'name'=>'user_id',
							'value'=>'($data->user_id != "") ? $data->user->name : ""',
							'filter' =>CHtml::activeDropDownList($model,'user_id',CHtml::listData(User::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'name')), 'user_id', 'name'),array('prompt'=>'-- User name --')),
						), 	
						array(
							'name'=>'active_status',
							'value'=>'($data->active_status == "S") ? "Approved" :"Disapproved"',
							'filter' =>  CHtml::dropDownList(
														'Comment[active_status]',
														$model->active_status,
														array("S" => "Approved","H"=>"Disapproved"),array('empty' => '-- All --')),
						),
						// array(
							// 'name'=>'added_on',
							// 'value'=>'($data->added_on == "") ? "Not Set" :Controller::dateConvert($data->added_on)',
							// 'filter'=>false,
						// ),
						array(
							'class'=>'CButtonColumn',
							'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
							'template'=>$template,
							'buttons'=>array
							(
								'activate' => array
								(
									'url'=>'Yii::app()->createUrl("owner/shopComment/setstatus",array("id"=>$data->comment_id,"active_status"=>"S"))',
									 'options'=>array(
											'id'=>'$data->comment_id',
											'onClick'=>'return confirm("Are you sure to approve this comment?")'
										),
									'imageUrl'=>Yii::app()->theme->baseUrl.'/img/active-icon.png', 
									'label' =>'Approve',
									'visible'=>'$data->active_status=="S"? 0:1'
								),
								'deactivate' => array
								(
									'url'=>'Yii::app()->createUrl("owner/shopComment/setstatus",array("id"=>$data->comment_id,"active_status"=>"H"))',
									 'options'=>array(
											'id'=>'$data->comment_id',
											'onClick'=>'return confirm("Are you sure to disapprove this comment?")'
										),
									'imageUrl'=>Yii::app()->theme->baseUrl.'/img/inactive-icon.png', 
									'label' =>'Disapprove',
									'visible'=>'$data->active_status!="S"? 0:1'
								),
							   
							),
				    		'htmlOptions'=>array('style'=>'width:30px;')
						),
					),
			)); ?>
		</div>
	</div>
</div>

<script>
	/* function changeStatus(status)
	{
		var check = $("input[name='comment_id_arr[]']:checked");
		
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
							}else{
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
	} */
</script>