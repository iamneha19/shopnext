<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8"/>
	<?php if(!empty(Yii::app()->controller->module->id))
		{
			$module = 	Yii::app()->controller->module->id;
		}
		else
		{
			$url = explode("/",$_SERVER['REQUEST_URI']);
			$module = 	$url[2];
		}
		if($module=='superadmin'){
		?>
			<title>Shopnext - Superadmin</title>
	<?php } else { ?>
		<title>Shopnext - Owner </title>
	<?php } ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<?php
		$baseUrl = Yii::app()->theme->baseUrl; 
		$basePath = Yii::app()->theme->basePath; 
		$cs = Yii::app()->getClientScript();
	?>	
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/font-awesome/css/font-awesome.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap/css/bootstrap.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/uniform/css/uniform.default.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/plugins.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/components.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/login3.css'); ?>	
	<?php $cs->registerCssFile($baseUrl.'/css/layout.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/default_theme.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/custom.css'); ?>
	
	<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="login">
	<div class="logo">
		<a href="<?php echo Yii::app()->baseUrl; ?>">
		<img src="<?php echo $baseUrl; ?>/img/shopnex_logo.jpg" alt=""/>
		</a>
	</div>
	<div class="menu-toggler sidebar-toggler"></div>
	<div class="content">
		<?php echo $content; ?>
	</div>
	<div class="copyright">
		 © 2015 Synergy Technology Services. ALL Rights Reserved.
	</div>
</body>
</html>
<!--[if lt IE 9]>
<?php $cs->registerScriptFile($baseUrl.'/plugins/respond.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/excanvas.min.js'); ?>
<![endif]-->
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-1.11.0.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-migrate-1.2.1.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap/js/bootstrap.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery.blockui.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery.cokie.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/uniform/jquery.uniform.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-validation/js/jquery.validate.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/metronic.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/login.js'); ?>
<script>
	jQuery(document).ready(function() {     
		Metronic.init(); 
		Login.init();
	});
</script>


