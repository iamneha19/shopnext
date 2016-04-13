<?php
	$owner_id = ApplicationSessions::run()->read('owner_id');
	$created_by = ApplicationSessions::run()->read('created_by');
	
	if(empty($created_by)){
		$condition = 'owner_id="'.$owner_id.'" and status = "1"';
	}else{
		$condition = 'user_id="'.$owner_id.'" and status = "1"';
	}
	
	$user_rights = $this->getAccessRule();
	
	if(in_array('Create',$user_rights)){
		$this->menu=array(
			array('label'=>'Add Deal', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
		);
	}
	$template = "";
	if(in_array('View',$user_rights)){
		$template .=" {view} ";
	}
	
	if(in_array('Delete',$user_rights)){
		$template .=" {delete} ";
	}
	$template .=" {activate} {deactivate}";
	
	if(in_array('Update',$user_rights)){
		$template .=" {update} ";
	}
?>


<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>List Deals</h4>
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
							<a href="#" onclick="changeStatus('changestatus','S');return false;">
							Approve </a>
						</li>
						<li>
							<a href="#"  onclick="changeStatus('changestatus','H');return false;">
							Disapprove</a>
						</li>
						<?php if(in_array('Delete',$user_rights)){ ?>
							<li>
								<a href="#"  onclick="changeStatus('changestatus','D');return false;">
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
				'id'=>'deal-grid',
				'type'=>'striped bordered condensed hover',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				//'ajaxUpdate'=>false,
				'beforeAjaxUpdate' => 'saveFilter', // to reinitialize DatePicker
				'afterAjaxUpdate' => 'reinstallDatePicker', // to reinitialize DatePicker
				'columns'=>array(
								array(
									'class'=>'CCheckBoxColumn',
									'selectableRows'=>2,
									'checkBoxHtmlOptions'=>array('name'=>'deal_id_arr[]'),
								),
								array(
									'header'=>'Sr. No.',
									'htmlOptions'=>array('class'=>'w45'),
									'class'=>'CounterColumn'
								),
								array(
										'name'=>'shop_id',
										'value'=>'($data->shop_id != "") ? $data->shop->name  :"Not set"',
										'filter' =>CHtml::activeDropDownList($model,'shop_id',CHtml::listData(Shop::model()->findAll(array('condition'=>$condition,'order'=>'name')), 'shop_id', 'name'),array('prompt'=>'-- Shop --')),
								),
								'title',
								 array(
										'name' => 'start_date',                             
										'value'=>'($data->start_date == "") ? "Not Set" : Controller::dobConvert($data->start_date)',
										'filter'=>CHtml::textField('Deal[start_date]'),

								),


								array(
										'name'=>'end_date',
										'value'=>'($data->end_date == "") ? "Not Set" : Controller::dobConvert($data->end_date)',
										'filter'=>CHtml::textField('Deal[end_date]'),
								),
								array(
										'name'=>'active_status',
										'value'=>'($data->active_status == "S") ? "Approved" :"Disapproved"',
										'filter' => CHtml::dropDownList(
														'Deal[active_status]',
														$model->active_status,
														array("S" => "Approved","H"=>"Disapproved"),array('empty' => '-- All --')),
									),
								
								array(
										'class'=>'CButtonColumn',
										'deleteConfirmation'=>'Deleting this record would delete all its comments, Are you sure to continue?',
										'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
										'template'=>$template,
										'buttons'=>array(
													'update' => array
													(
														'url'=>'Yii::app()->createUrl("owner/deal/update",array("id"=>$data->deal_id))',
														'label' =>'Update',
														// 'visible'=>'$data->end_date > strtotime("+3 days") ? 1:0'
													),
													
													'activate' => array
													(
														'url'=>'Yii::app()->createUrl("owner/deal/setstatus",array("id"=>$data->deal_id,"active_status"=>"S"))',
															 'options'=>array(
																	'id'=>'$data->active_status',
																					'onClick'=>'return confirm("Are you sure to approved deal?")'
																),
															'imageUrl'=>Yii::app()->theme->baseUrl.'/img/active-icon.png', 
															'label' =>'Approved',
																	'visible'=>'$data->active_status=="S"? 0:1'
													),
															'deactivate' => array
													(
														'url'=>'Yii::app()->createUrl("owner/deal/setstatus",array("id"=>$data->deal_id,"active_status"=>"H"))',
															 'options'=>array(
																	'id'=>'$data->active_status',
																	'onClick'=>'return confirm("Are you sure to disapproved deal?")'
																),
															'imageUrl'=>Yii::app()->theme->baseUrl.'/img/inactive-icon.png', 
															'label' =>'Disapproved',
																	'visible'=>'$data->active_status!="S"? 0:1'
													),

											),
										'htmlOptions'=>array('style'=>'width:150px;')
								),
				),
			)); ?>
		</div>
	</div>
</div>

<script>
	selected_start_date = '';
	selected_end_date = '';
    $('document').ready(function(){
       $('#Deal_start_date').datepicker({
		   format:'dd-mm-yyyy', 
			autoclose: true
		}).on('changeDate',function(){
				if($('#Deal_start_date').val() == ''){
					$('#Deal_start_date').val(selected_start_date);
				}else{
					selected_start_date = $('#Deal_start_date').val();
				}
				
				$('#Deal_start_date').datepicker('update', selected_start_date);
			});
       $('#Deal_end_date').datepicker({
		   format:'dd-mm-yyyy', 
		   autoclose: true
	   }).on('changeDate',function(){
				if($('#Deal_end_date').val() == ''){
					$('#Deal_end_date').val(selected_end_date);
				}else{
					selected_end_date = $('#Deal_end_date').val();
				}
				
				$('#Deal_end_date').datepicker('update', selected_end_date);
			});
    });
</script>
<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Deal_start_date').val(start_date);
    $('#Deal_start_date').datepicker({
	   format:'dd-mm-yyyy', 
		autoclose: true
	}).on('changeDate',function(){
			if($('#Deal_start_date').val() == ''){
				$('#Deal_start_date').val(selected_start_date);
			}else{
				selected_start_date = $('#Deal_start_date').val();
			}
			
			$('#Deal_start_date').datepicker('update', selected_start_date);
	}); 
    
    $('#Deal_end_date').val(end_date);
    $('#Deal_end_date').datepicker({
	   format:'dd-mm-yyyy', 
	   autoclose: true
   }).on('changeDate',function(){
			if($('#Deal_end_date').val() == ''){
				$('#Deal_end_date').val(selected_end_date);
			}else{
				selected_end_date = $('#Deal_end_date').val();
			}
			
			$('#Deal_end_date').datepicker('update', selected_end_date);
		});
   
  
}
");
?>
<?php
Yii::app()->clientScript->registerScript('save-filter', "
    function saveFilter(id, data) {
     start_date = $('#Deal_start_date').val();
     end_date = $('#Deal_end_date').val();
    }
");
?>

<script>
	function changeStatus(action,status)
	{
		var check = $("input[name='deal_id_arr[]']:checked");
		
		if(check.length>0)
		{
			bootbox.confirm("Are you sure to perform this action?", function(result) {
				if(result)
				{
					Metronic.blockUI({message: 'Processing please wait ...'});
					qry = check.serialize();
					$.ajax({
						type:'POST',
						url:action,
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
			bootbox.alert("No deals selected !!!");    
		}
	}
</script>
