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
?>
<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>List Orders</h4>
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
								<a href="#"  onclick="changeStatus('D');return false;">
								Delete</a>
							</li>
						
					</ul>
				</div>
			</span>		
			<span class = "pull-right">				
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'order-grid',
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
							'checkBoxHtmlOptions'=>array('name'=>'order_id_arr[]'),
						),
						array(
							'header'=>'Sr. No.',
							'htmlOptions'=>array('class'=>'w45'),
							'class'=>'CounterColumn'
						), 
						'order_no',
						 array(
							'name'=>'user_id',
							'value'=>'($data->user_id != "") ? $data->user->name : ""',
							'filter' =>CHtml::activeDropDownList($model,'user_id',CHtml::listData(User::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'name')), 'user_id', 'name'),array('prompt'=>'-- User name --')),
						),
						array(
						'name'=>'sub_total',
						'value'=>'number_format($data->sub_total,2)." INR"',							
						),
						array(
							'name'=>'order_status',
							'value' =>'(!empty($data->order_status))?$data->getOptions($data->order_status):""',
									  
							'filter' =>  CHtml::dropDownList(
														'Order[order_status]',
														$model->order_status,
														array('P' => 'Pending','PR' => 'Processing','I' => 'InTransit','C' => 'Complete'),array('empty' => '-- All --')),
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
							'update' => array
											(
												'visible'=>'$data->order_status=="C"? 0:1'
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
		var check = $("input[name='order_id_arr[]']:checked");
		
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
			bootbox.alert("No orders selected !!!");    
		}
	}
</script>