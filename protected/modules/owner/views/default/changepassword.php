<div class="form">
	<div id ="Owner_form_msg" class="note Metronic-alerts alert alert-success fade in" style="display:none"; ></div>
	<div id="changePassword">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'owner-form',
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
				<span id="Owner_old_password_em_" class="errorMessage"></span>
			</div>
		</div>
	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'new_password',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'new_password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>20,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'new_password'); ?>
				<span id="Owner_new_password_em_" class="errorMessage"></span>
			</div>
		</div>
	
		<div class="form-group row">
			<?php echo $form->labelEx($model,'confirm_password',array('class'=>'col-md-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->passwordField($model,'confirm_password',array('class'=>'form-control input-large','size'=>60,'maxlength'=>20,'class'=>'form-control input-medium')); ?>
				<?php echo $form->error($model,'confirm_password'); ?>
				<span id="Owner_confirm_password_em_" class="errorMessage"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="button" id="save_btn">Save changes</button>
				<button class="btn btn-danger" type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/owner/default/index'">Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
<script>
$(document).ready(function(){
	
	$('#Owner_old_password').on("blur",function()
	{
		if($(this).val()!='')
		{
			// Metronic.blockUI({message: 'Verifying old password, please wait ...'});
			// $(this).addClass('spinner');
			$.ajax({
				type:"POST",
				url:"checkpassword",
				data:"old_pass="+this.value+"&username=<?php echo ApplicationSessions::run()->read('owner_username')?>",				
				success: function(result){
					// Metronic.unblockUI();
					var obj = jQuery.parseJSON( result );
					if(obj.success==false)
					{
						if(obj.code=='404'){
							$('#save_btn').attr('disabled',true);
							$('#Owner_old_password_em_').html("Please enter the correct password!").show();
							$('#Owner_old_password').focus();
						}else{
							window.location.reload();
						}
						
					}else{					   
						$('#Owner_old_password_em_').html('').hide();
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
		old_pwd = $('#Owner_old_password').val();
		new_pwd = $('#Owner_new_password').val();
		confirm_pwd = $('#Owner_confirm_password').val();
		if(old_pwd=='')
		{
			counter=counter+1;
			$('#Owner_old_password_em_').html("Please enter the current password!").show();
		}else{
			$('#Owner_old_password_em_').html("").hide();
		}
		if(new_pwd=='')
		{
			counter=counter+1;
			$('#Owner_new_password_em_').html("Please enter the new password!").show();
		}else{
			$('#Owner_new_password_em_').html("").hide();
		}
		
		if(confirm_pwd=='')
		{
			counter=counter+1;
			$('#Owner_confirm_password_em_').html("Please enter the confirm password!").show();
		}else{
			$('#Owner_confirm_password_em_').html("").hide();
		}
		if(old_pwd!='' && new_pwd!='' && confirm_pwd!='')
		{
			if(old_pwd==new_pwd)
			{
				counter=counter+1;
				$('#Owner_new_password_em_').html("Old password and new password can not be same!").show();
				$('#Owner_new_password').val('');
				return false;
			}else{
				$('#Owner_new_password_em_').html("").hide();
			}
			if(old_pwd!=new_pwd && new_pwd.length<8)
			{
				counter=counter+1;
				$('#Owner_new_password_em_').html("Password is too short (please enter minimum 8 characters)!").show();
				$('#Owner_new_password').val('');
			}else{
				$('#Owner_new_password_em_').html("").hide();
			}
			if(new_pwd!=confirm_pwd)
			{
				counter=counter+1;
				$('#Owner_confirm_password_em_').html("Confirm password is not correct!").show();
				$('#Owner_confirm_password').val('');
			}else{
				$('#Owner_confirm_password_em_').html("").hide();
			}
		}
		 if(counter>0)
		 {
			 $('.note-danger').html("Please fill all the required fields!").show();
		}else{
			 qry = $('#owner-form').serialize();
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
						$('#Owner_form_msg').html("Your password has been changed successfully!").show();
						
					}else{
						$('#Owner_form_msg').html('An error occured! please try after sometime.').show();
					}	
					 
					$('#save_btn').attr('disabled',false);	
					$('input[type=password]').val('');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$('#Owner_form_msg').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
						window.location.reload();
				} 				
			});
		 }
	});
});
</script>