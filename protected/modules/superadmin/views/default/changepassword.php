<div class="form">
	<div id ="Admin_form_msg" class="note Metronic-alerts alert alert-success fade in" style="display:none"; ></div>
	<div id="changePassword">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'advocate-form',
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data','validateOnSubmit'=>true,),
		)); ?>
		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'username',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $model->username; ?>
				
			</div>
		</div>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'old_password',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'old_password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>20,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'old_password'); ?>
				<span id="Admin_old_password_em_" class="errorMessage"></span>
			</div>
		</div>
	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'new_password',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'new_password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>20,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'new_password'); ?>
				<span id="Admin_new_password_em_" class="errorMessage"></span>
			</div>
		</div>
	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'confirm_password',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'confirm_password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>20,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'confirm_password'); ?>
				<span id="Admin_confirm_password_em_" class="errorMessage"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="button" id="save_btn">Save changes</button>
				<button class="btn btn-danger" type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/default/index'">Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
<script>
$(document).ready(function(){
	
	$('#Admin_old_password').on("blur",function()
	{
		if($(this).val()!='')
		{
			// Metronic.blockUI({message: 'Verifying old password, please wait ...'});
			// $(this).addClass('spinner');
			$.ajax({
				type:"POST",
				url:"checkpassword",
				data:"old_pass="+this.value+"&username=<?php echo ApplicationSessions::run()->read('admin_username')?>",				
				success: function(result){
					// Metronic.unblockUI();
					var obj = jQuery.parseJSON( result );
					if(obj.success==false)
					{
						if(obj.code=='404'){
							$('#save_btn').attr('disabled',true);
							$('#Admin_old_password_em_').html("Please enter the correct password!").show();
							$('#Admin_old_password').focus();
						}else{
							window.location.reload();
						}
						
					}else{					   
						$('#Admin_old_password_em_').html('').hide();
						$('#save_btn').attr('disabled',false);
					} 
					// $('#Admin_old_password').removeClass('spinner');
			    },
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					window.location.reload();
				} 
			
		    });
	    }
		
    });
	
	$('#save_btn').click(function(){
		counter=0;
		old_pwd = $('#Admin_old_password').val();
		new_pwd = $('#Admin_new_password').val();
		confirm_pwd = $('#Admin_confirm_password').val();
		if(old_pwd=='')
		{
			counter=counter+1;
			$('#Admin_old_password_em_').html("Please enter the current password!").show();
		}else{
			$('#Admin_old_password_em_').html("").hide();
		}
		if(new_pwd=='')
		{
			counter=counter+1;
			$('#Admin_new_password_em_').html("Please enter the new password!").show();
		}else{
			$('#Admin_new_password_em_').html("").hide();
		}
		
		if(confirm_pwd=='')
		{
			counter=counter+1;
			$('#Admin_confirm_password_em_').html("Please enter the confirm password!").show();
		}else{
			$('#Admin_confirm_password_em_').html("").hide();
		}
		if(old_pwd!='' && new_pwd!='' && confirm_pwd!='')
		{
			if(old_pwd==new_pwd)
			{
				counter=counter+1;
				$('#Admin_new_password_em_').html("Old password and new password can not be same!").show();
				$('#Admin_new_password').val('');
				return false;
			}else{
				$('#Admin_new_password_em_').html("").hide();
			}
			if(old_pwd!=new_pwd && new_pwd.length<8)
			{
				counter=counter+1;
				$('#Admin_new_password_em_').html("Password is too short (please enter minimum 8 characters)!").show();
				$('#Admin_new_password').val('');
			}else{
				$('#Admin_new_password_em_').html("").hide();
			}
			if(new_pwd!=confirm_pwd)
			{
				counter=counter+1;
				$('#Admin_confirm_password_em_').html("Confirm password is not correct!").show();
				$('#Admin_confirm_password').val('');
			}else{
				$('#Admin_confirm_password_em_').html("").hide();
			}
		}
		 if(counter>0)
		 {
			 $('.note-danger').html("Please fill all the required fields!").show();
		}else{
			 qry = $('#advocate-form').serialize();
			 $('#save_btn').attr('disabled',true);
			 Metronic.blockUI({message: 'Updating your new password, please wait...'});
			$.ajax({
				type: "POST",
				url: "updatepassword",
				data: qry,
				
				 success:function(result){
					var obj = jQuery.parseJSON(result);
					Metronic.unblockUI();
					if(obj.success==true)
					{
						// bootbox.alert("Your password has been changed successfully!");    
						$('#Admin_form_msg').html("Your password has been changed successfully!").show();
						
					}else{
						$('#Admin_form_msg').html('An error occured! please try after sometime.').show();
					}	
					 
					$('#save_btn').attr('disabled',false);	
					$('input[type=password]').val('');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$('#Admin_form_msg').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
						window.location.reload();
				} 				
			});
		 }
	});
});
</script>