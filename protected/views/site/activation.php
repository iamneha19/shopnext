<?php
$this->pageTitle = 'Account Activation';
?>
<div id="wrapper">
	<div id="main_div">
		<h1>Account Activation</h1>

		<?php if(Yii::app()->user->hasFlash('msg')): ?>

		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('msg'); ?>
		</div>
		<?php endif; ?>
	</div>
</div>
