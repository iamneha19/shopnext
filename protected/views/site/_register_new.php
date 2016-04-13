
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-form',
            'enableClientValidation'=>true,
            'enableAjaxValidation'=>true,
            'clientOptions'=>array(
                    'validateOnSubmit'=>true,
            ),
    )); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
        
    <div class="row">
		<?php echo $form->labelEx($model,'repeat_password'); ?>
		<?php echo $form->passwordField($model,'repeat_password'); ?>
		<?php echo $form->error($model,'repeat_password'); ?>
	</div>
        
        <div class="row rememberMe">
		<?php echo $form->checkBox($model,'iagree'); ?>
		<?php echo $form->label($model,'iagree'); ?>
		<?php echo $form->error($model,'iagree'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton('Sign Up',array('class'=>'btn btn-primary')); ?>
		Have an account ? 
		<?php echo CHtml::link('Sign in here',"#",array('onclick'=>'loadModal("login","Login");return false;')); ?>
		
		<span class="pull-right">
			Or Sign up through
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
