<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'login-form',
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
                            $.ajax({
                                    url: $(form).attr('action'),
                                    type:'POST',
                                    data:$(form).serialize(),
                                    dataType:'json',
                                    success:function(obj){
                                    console.log(obj);
                                    if( obj.result === 'success' ){
                                              location.reload();  
                                            }
                                    }
                            });
                        }
                        return false;
                   }"
            ),
    )); ?>
		<div class="row">
			<?php echo $form->labelEx($model,'email_id'); ?>
			<?php echo $form->textField($model,'email_id'); ?>
			<?php echo $form->error($model,'email_id'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
		<div class="row rememberMe">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo CHtml::link('Forgot password?',"#",array('onclick'=>'loadModal("forgotPassword","Forgot Password ?");return false;')); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>	
		<div class="row buttons">		
			<?php echo CHtml::submitButton('Sign In',array('class'=>'btn btn-primary')); ?>		
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


