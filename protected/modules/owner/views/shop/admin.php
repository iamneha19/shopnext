<?php
	$user_rights = $this->getAccessRule();
	// if(in_array('Create',$user_rights)){
		// $this->menu=array(
			// array('label' => 'Add Shop', 'url' => 'create','htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
		// );
	// }
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
	$template .=" {activate} {deactivate} {manage_images}";
?>

<div class="col-lg-12">
			
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>List Shops</h4>
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
							Active </a>
						</li>
						<li>
							<a href="#"  onclick="changeStatus('H');return false;">
							Deactive</a>
						</li>
						<?php if(in_array('Delete',$user_rights)){?>
							<li>
								<a href="#"  onclick="changeStatus('D');return false;">
								Delete</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</span>
			<span class = "pull-right">
				<span class="badge list-group-item-danger" >
					&nbsp;<i class="fa fa-warning"></i>&nbsp;<strong>Marked for containing invalid details </strong>&nbsp;
				</span>
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'shop-grid',
				'type'=>'striped bordered condensed hover',
				'pagerCssClass' => 'col-md-12',
				'pager' => array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'=>'</br>',
				'rowCssClassExpression' => '
					( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ) .
					( ($data->mark_invalid=="1") ? " danger" : null )
				',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
						array(
							'class'=>'CCheckBoxColumn',
							'selectableRows'=>2,
							'checkBoxHtmlOptions'=>array('name'=>'shop_id_arr[]'),
						),
						array(
							'header'=>'Sr. No.',
							'htmlOptions'=>array('class'=>'w45'),
							'class'=>'CounterColumn'
						),
						'name',
						array(
							'name'=>'category_id',
							'value'=>'$data->category->category',
							'filter' =>CHtml::activeDropDownList($model,'category_id',CHtml::listData(Category::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'category')), 'category_id', 'category'),array('prompt'=>'-- Category --')),
						),
						'contact_no',
						array(
							'name'=>'active_status',
							'value'=>'($data->active_status == "S") ? "Active" :"Deactive"',
							'filter' => CHtml::dropDownList(
												'Shop[active_status]',
												$model->active_status,
												array("S" => "Active","H"=>"Deactive"),array('empty' => '-- All --')),
						),
						array(
							'class'=>'CButtonColumn',
							'deleteConfirmation'=>'Deleting this would delete all the records corresponding to shop, such as shop products, deals, & comments, Are you sure to continue?',
							'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
							'template'=>$template,
							'buttons'=>array
							(
								'manage_images' => array
								(
									'url'=>'Yii::app()->createUrl("owner/shop/manageImages", array("id"=>$data->shop_id))',
										'options'=>array(
											'id'=>'$data->shop_id',
										),
									'imageUrl'=>Yii::app()->theme->baseUrl."/img/pictures_2.png", 
									'label' =>'Manage Shop Images'    
								),
								 'activate' => array
								(
									'url'=>'Yii::app()->createUrl("owner/shop/setstatus",array("id"=>$data->shop_id,"active_status"=>"S"))',
										 'options'=>array(
												'id'=>'$data->active_status',
																'onClick'=>'return confirm("Are you sure to activate this shop?")'
											),
										'imageUrl'=>Yii::app()->theme->baseUrl.'/img/active-icon.png', 
										'label' =>'Active',
												'visible'=>'$data->active_status=="S"? 0:1'
								),
								'deactivate' => array
								(
									'url'=>'Yii::app()->createUrl("owner/shop/setstatus",array("id"=>$data->shop_id,"active_status"=>"H"))',
										 'options'=>array(
												'id'=>'$data->active_status',
												'onClick'=>'return confirm("Are you sure to deactivate this shop?")'
											),
										'imageUrl'=>Yii::app()->theme->baseUrl.'/img/inactive-icon.png', 
										'label' =>'Deactive',
												'visible'=>'$data->active_status!="S"? 0:1'
								),
							   
							),
							'htmlOptions'=>array('style'=>'width:110px;')
						),					
				),
			)); ?>
		</div>
	</div>
</div>
<script>
	function changeStatus(status)
	{
		var check = $("input[name='shop_id_arr[]']:checked");
		
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
			bootbox.alert("No shops selected !!!");    
		}
	}
</script>

