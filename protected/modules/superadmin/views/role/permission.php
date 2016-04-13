<?php
	$this->menu=array(
		array('label'=>'Add Role', 'url'=>array('create'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'plus'),
		array('label'=>'Update Role', 'url'=>array('update', 'id'=>$role_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'note'),
		array('label'=>'View Role', 'url'=>array('view', 'id'=>$role_id),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'magnifier'),	
		array('label'=>'List Role', 'url'=>array('admin'),'htmlOptions' => array('class'=>'btn-default btn-sm'),'icon' => 'list'),
	);
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>Role Permission</h4>
		</div>
		<div class="panel-body noPad clearfix">
			<?php 
				$this->widget(
					'bootstrap.widgets.TbButtonGroup',
					array(
						'buttons' => $this->menu,
					)
				);
			?>				
		</div>
		<div class="panel-body noPad clearfix">
			<div class="col-lg-12 ">
                <div class="page-header">
                    <h4>Modules and features</h4>
                </div>
                <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				        'id'=>'index-id',
						  
				)); ?>
                <table class="table  table-bordered">
                    <thead>
                      <tr>
                        <th>Sr No.</th>
                        <th>Module</th>
                        <th>Feature</th>
                        <th>Permission</th>
                      </tr>
                    </thead>
                    <tbody>
						<tr>
							<td colspan="4" align="center">
								<center>
									<?php $this->widget('bootstrap.widgets.TbButton', array(
										'buttonType'=>'submit',
										'type'=>'primary',
										'label'=>'Save',
									)); ?>
								</center>
								<input type="hidden" name="Permission[role_id]" value="<?php echo $role_id; ?>"/>
							</td>
						</tr>
                    	<?php
                    		$i = 0;
                    		
                    		foreach($controller_action as $controller=>$arr_action)
                    		{
                    	?>
								<tr>
									<td colspan="2"></td>
			                    	<td colspan="1" style="font-weight:bold;" align="right">Select All</td>
			                    	<td ><input class="nostyle" id="<?php echo $controller; ?>" name="Permission[all_permission][]" type="checkbox" onclick="setCheckboxValue('<?php echo $controller; ?>');" value="<?php echo $controller; ?>" <?php if(!empty($all_permission) && in_array($controller,$all_permission)){ echo "checked='checked'"; } ?>/></td>
			                    </tr>
                    	<?php
                    			foreach($arr_action as $key=>$action)
                    			{
                    				if($key==0)
                    				{
                    					$i++;
                    	?>
                						<tr>
					                        <td><?php echo $i; ?></td>
					                        <td><?php echo ($controller=='Category') ? 'Shop Category' : $controller; ?></td>
					                        <td><?php echo $action."  ".preg_replace('/(?<!\ )[A-Z]/', '  $0', $controller); ?></td>
					                        <td><input class="nostyle" type="checkbox" name="Permission<?php echo "[".$controller."]"."[]"; ?>" parentid="<?php echo $controller; ?>" value="<?php echo $controller.".".$action; ?>" <?php if(!empty($action_permission) && in_array($controller.".".$action,$action_permission)){ echo "checked='checked'"; } ?>/></td>
					                    </tr>
					    <?php
                    				}
									else 
									{
						?>	
										<tr>
					                        <td>&nbsp;</td>
					                        <td>&nbsp;</td>
					                        <td><?php echo $action."  ".preg_replace('/(?<!\ )[A-Z]/', '  $0', $controller); ?></td>
					                        <td><input class="nostyle" type="checkbox" name="Permission<?php echo "[".$controller."]"."[]"; ?>" parentid="<?php echo $controller; ?>" value="<?php echo $controller.".".$action; ?>" <?php if(!empty($action_permission) && in_array($controller.".".$action,$action_permission)){ echo "checked='checked'"; } ?>/></td>
					                    </tr>
					    <?php
									}
                    			}
                    		}
                    	?>
                    	<tr>
							<td colspan="4" align="center">
								<center>
									<?php $this->widget('bootstrap.widgets.TbButton', array(
										'buttonType'=>'submit',
										'type'=>'primary',
										'label'=>'Save',
									)); ?>
								</center>
							</td>
						</tr>
                    </tbody>
                </table>
                <?php $this->endWidget(); ?>
				<br/>
            </div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function setCheckboxValue(id)
	{
		$('input[parentid="'+id+'"]').each(function(i,el){
			if($('#'+id).is(':checked'))
			{
				  $(el).attr('checked', true);
			} else {	 
				 $(el).attr('checked', false);
			}			   
		});
	}
	function setCheckboxIndividual(obj)
	{		if($(obj).is(':checked'))
		{
			 
		} else	 {
			   
		} 			
	}

</script>
