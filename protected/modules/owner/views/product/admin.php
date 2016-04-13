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
			array('label' => 'Add Product', 'url' => 'create','htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
		);
	}
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
	$owner_id = ApplicationSessions::run()->read('owner_id');
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>Manage Products</h4>
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
				<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button','class'=>'btn red btn-sm')); ?>
				<?php echo CHtml::resetButton('Search', array('class'=>'updateGridButtonSelector btn green btn-sm')); ?>
			</span>
			
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'product-grid',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'pager' 		=> array('header' => '','htmlOptions'=>array('class'=>'pagination')),
				'summaryText'	=> '</br>',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
					array(
						'class'=>'CCheckBoxColumn',
						'selectableRows'=>2,
						'checkBoxHtmlOptions'=>array('name'=>'product_id_arr[]'),
					),
					array(
						'header'=>'Sr. No.',
						'htmlOptions'=>array('class'=>'w45'),
						'class'=>'CounterColumn'
					),
					'name',
					array(
						'name'=>'price',
						'value'=>'number_format($data->price,2)." INR"',							
					),
					array(
							'name'=>'product_category_id',
							'value'=>'$data->productCategory->product_category',
							'filter' =>CHtml::activeDropDownList($model,'product_category_id',CHtml::listData(ProductCategory::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'product_category')), 'product_category_id', 'product_category'),array('prompt'=>'-- Product Category --')),
					),
					array(
						'name'=>'shop_id',
						'value'=>'($data->shop_id != "") ? $data->shop->name  :"Not set"',
						'filter' =>CHtml::activeDropDownList($model,'shop_id',CHtml::listData(Shop::model()->findAll(array('condition'=>$condition,'order'=>'name')), 'shop_id', 'name'),array('prompt'=>'-- Shop --')),
					),
					array(
						'name'=>'active_status',
						'value'=>'($data->active_status == "S") ? "Active" :"Deactive"',
						'filter' => CHtml::dropDownList(
										'Product[active_status]',
										$model->active_status,
										array("S" => "Active","H"=>"Deactive"),array('empty' => '-- All --')),
					),
					array(
							'class'=>'CButtonColumn',
							'deleteConfirmation'=>'Deleting this would delete all the records corresponding to this products, such as product images & comments, Are you sure to continue?',
							'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").replaceWith(data); }',
							'template'=>$template,
							'buttons'=>array
						    (
								'manage_images' => array
								(
									'url'=>'Yii::app()->createUrl("owner/product/manageImages", array("id"=>$data->product_id))',
										'options'=>array(
											'id'=>'$data->shop_id',
										),
									'imageUrl'=>Yii::app()->theme->baseUrl."/img/pictures_2.png", 
									'label' =>'Manage Shop Images'    
								),
						        'activate' => array
						        (
						            'url'=>'Yii::app()->createUrl("owner/product/setstatus",array("id"=>$data->product_id,"active_status"=>"S"))',
						        	 'options'=>array(
							                'id'=>'$data->product_id',
											'onClick'=>'return confirm("Are you sure to activate this product?")'
							            ),
							        'imageUrl'=>Yii::app()->theme->baseUrl.'/img/active-icon.png', 
							        'label' =>'Active',
									'visible'=>'$data->active_status=="S"? 0:1'
						        ),
								'deactivate' => array
						        (
						            'url'=>'Yii::app()->createUrl("owner/product/setstatus",array("id"=>$data->product_id,"active_status"=>"H"))',
						        	 'options'=>array(
							                'id'=>'$data->product_id',
							                'onClick'=>'return confirm("Are you sure to deactivate this product?")'
							            ),
							        'imageUrl'=>Yii::app()->theme->baseUrl.'/img/inactive-icon.png', 
							        'label' =>'Deactive',
									'visible'=>'$data->active_status!="S"? 0:1'
						        ),
						       
				    		),
				    		'htmlOptions'=>array('style'=>'width:120px;')
						),
				),
			)); ?>
		</div>
	</div>
</div>

<script>
	function changeStatus(status)
	{
		var check = $("input[name='product_id_arr[]']:checked");
		
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
			bootbox.alert("No products selected !!!");    
		}
	}
</script>
