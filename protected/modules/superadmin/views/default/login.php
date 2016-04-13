<?php 
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'members-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
		'htmlOptions'=>array('class'=>'login-form'),
	));
?>
	<h3 class="form-title">Login to your account</h3>
	<div class="alert alert-danger display-hide">
		<button class="close" data-close="alert"></button>
		<span>
		Enter any username and password. </span>
	</div>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<?php echo $form->labelEx($model,'username',array('class'=>'control-label visible-ie8 visible-ie9')); ?>
		<div class="input-icon">
			<i class="fa fa-user"></i>
			<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50,'class'=>'form-control placeholder-no-fix','placeholder'=>'Enter your username ...')); ?>
		</div>
		<?php echo $form->error($model,'username',array('class'=>'error required')); ?>
	</div>
	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<div class="input-icon">
			<i class="fa fa-lock"></i>
			<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>50,'class'=>'form-control placeholder-no-fix','placeholder'=>'Enter your password ...')); ?>
		</div>
		<?php echo $form->error($model,'password',array('class'=>'error required')); ?>
	</div>
	<div class="form-actions">
		<a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
		<label class="checkbox">
		<!-- <input type="checkbox" name="remember" value="1"/> Remember me  --></label>
		<button type="submit" class="btn green pull-right">
		Login <i class="m-icon-swapright m-icon-white"></i>
		</button>
	</div>
	
<?php $this->endWidget(); ?>

<form class="forget-form"  action="javascript:void(0);" onsubmit="return false;"  method="post">

	<h3>Forget Password ?</h3>
	<p>
		Enter your username below to reset your password.
	</p>
	<div class="form-group">
		<div class="input-icon">
			<i class="fa fa-user"></i>
			<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" id="username"/>
		</div>
		<span class="errorMessage " aria-required="true" id="err-username"></span>

	</div>
	<div class="form-actions">
		<button type="button" id="back-btn" class="btn btn-default"><i class="m-icon-swapleft "></i> Back</button>
		<button type="button" id="reset-submit" onclick="validate_reset();" class="btn green pull-right">
		Reset <i class="m-icon-swapright m-icon-white"></i>
		</button>
	</div>

</form>


<script>
	 $('document').ready(function() {
			
		$('#forget-password').click(function() {
			$('.login-form').hide();
		   
			$('.forget-form').show();
		});

		$('#back-btn').click(function() {
			$('.login-form').show();
			$('#username').val('');
			$('.errorMessage').html('');
			$('.forget-form').hide();
		});
	  
		$('.forget-form input').keypress(function(e) {
			if (e.which == 13) {
				validate_reset();
				return false;
			}
		});
		 
	});
	
	function validate_reset()
	{
		username = $.trim($('#username').val());
		$('#err-username').attr('style','color:red');
		if(username!='')
		{
			$($('#username')).addClass('spinner');
			$('#reset-submit').attr('disabled',true);
			$.ajax({
					type: "POST",
					url: "requestnewpassword",
					data: 'username='+username,
					success:function(result){
						var obj = jQuery.parseJSON(result);
						
						if(!obj.success)
						{
							$('#err-username').html(obj.msg).show();
							$('#username').focus();			
						}else{
							$('#username').val('');
							$('#err-username').attr('style','color:green').html(obj.msg);
							 setTimeout(function(){ location.reload(); }, 3000);
						}	
						$('#reset-submit').attr('disabled',false);	
						$($('#username')).removeClass('spinner');
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						alert('An error occured please try after sometime !!!');
						window.location.reload();
					} 				
			});
		}else{
			$('#err-username').html('Username is required !').show();
		}
		
		//return false;
	}
</script>