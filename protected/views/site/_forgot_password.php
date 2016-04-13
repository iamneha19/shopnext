
<div class="form">
	<?php echo CHtml::link('<< Back to sign in',"#",array('onclick'=>'loadModal("login","Login");return false;')); ?>
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'forgotpassword-form',
            'enableClientValidation'=>true,
            'enableAjaxValidation'=>true,
            'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>false,//this needs to stay on false always.
                   'beforeValidate'=>"js:function(form){
                        return true;
                   }",
                   'afterValidate'=>"js:function(form, data, hasError){
                      console.log('executed');
                        if(hasError){
                         console.log('hasError');
                            //do smth if there is an error.   
                        }else{
                            // submit the data to your controller.
                            console.log('ajax');
							$('#btn-process').val('Processing request please wait...').attr('disabled','true');
                            $.ajax({
                                    url: $(form).attr('action'),
                                    type:'POST',
                                    data:$(form).serialize(),
                                    dataType:'json',
                                    success:function(obj){
                                    console.log(obj);
										if( obj.result === 'success' ){
                                              $('#div-main').html('<br><br><b style=\"color:green\">Forgot password mail has been successfully sent to your email id!! </b><br><br>');
											  setTimeout(function(){ location.reload(); }, 5000);
                                            }
                                    }
                            });
                        }
                        return false;
                   }"
            ),
    )); ?>

	<div id="div-main">
		<div class="row" >
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username',array('placeholder'=>'Enter username')); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Send me forgot password mail.',array('class'=>'btn btn-primary','id'=>'btn-process')); ?>
		</div>
		
	</div>
	<div class="row buttons">
		
		Don't have account ?
		<?php echo CHtml::link('Sign Up here',"#",array('onclick'=>'loadModal("register","Register");return false;')); ?>
		
		<span class="pull-right">
			Or Sign in with
			<a href="#" onclick="socialMediaLogin('fblogin');return false;" >
				<img src="<?php echo Yii::app()->baseUrl."/themes/classic/img/facebook.png";?>">
			</a>
			<a href="#" onclick="socialMediaLogin('googlelogin');return false;" >
				<img src="<?php echo Yii::app()->baseUrl."/themes/classic/img/google.png";?>">
			</a>
			<!--<a href="#" onclick="socialMediaLogin('twitterlogin');return false;" >
				<img src="<?//php echo Yii::app()->baseUrl."/themes/classic/img/twitter.png";?>">
			</a>-->
		</span>
		
	</div>
	
    <?php $this->endWidget(); ?>
</div><!-- form -->



