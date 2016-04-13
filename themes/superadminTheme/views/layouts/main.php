<?php
	$document = Yii::app()->controller->getId();
	
	Yii::app()->clientScript->registerScript('gridFilter',"   
		$(function(){
			$(document).off('change.yiiGridView keydown.yiiGridView');
			
			$('body').on('click','.updateGridButtonSelector', function() {
			var values = $('#$document-grid .filters input ,#$document-grid .filters select').serializeArray();
			var status = 'Y';
			 jQuery.each( values, function( i, field ) {
						if(field.value.trim()==='=')
						{
							status = 'N';
						}
			});
			
				if(status=='Y')
				{
					$('#$document-grid').yiiGridView('update', {
							data: $('#$document-grid .filters input ,#$document-grid .filters select').serialize()    
						});

					   return false;
					
				}
				else
				{
					alert('= not allowed in search field');
				}
			});

			$('body').bind('keypress','.updateGridButtonSelector', function(e) {

				if(e.which==13)
				{
					var values = $('#$document-grid .filters input ,#$document-grid .filters select').serializeArray();
					var status = 'Y';
					 jQuery.each( values, function( i, field ) {
								if(field.value.trim()==='=')
								{
									status = 'N';
								}
					});
					
						if(status=='Y')
						{
							$('#$document-grid').yiiGridView('update', {
									data: $('#$document-grid .filters input ,#$document-grid .filters select').serialize()    
								});

							   return false;
							
						}
						else
						{
							alert('= not allowed in search field');
						}
				}
			
			});
		});
	", CClientScript::POS_READY);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">

<head>
	<meta charset="utf-8"/>
	<?php 
	$role_name = ApplicationSessions::run()->read('role_name');
	$owner_role_name = ApplicationSessions::run()->read('owner_role_name');
	if(!empty(Yii::app()->controller->module->id))
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
			<title>Shopnext - <?php echo ucfirst($role_name); ?></title>
	<?php } else { ?>
		<title>Shopnext - Owner - <?php echo ucfirst($owner_role_name); ?></title>
		<?php } ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<?php 
		$baseUrl = Yii::app()->theme->baseUrl; 
		$basePath = Yii::app()->theme->basePath; 
		$cs = Yii::app()->getClientScript();
		Yii::app()->bootstrap->registerCoreScripts('bootstrap.css',false);
	?>

	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	<?php $cs->registerCssFile($baseUrl.'/plugins/font-awesome/css/font-awesome.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap/css/bootstrap.min.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/uniform/css/uniform.default.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>		
	<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-datepicker/css/datepicker3.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/tasks.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/layout.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/components.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/plugins.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/default_theme.css'); ?>
	<?php $cs->registerCssFile($baseUrl.'/css/custom.css'); ?>
	<link rel="shortcut icon" href="favicon.ico"/>
	<script type="text/javascript">
		$('#form-reset-button').live('click',function()
		{
		   var id = '<?php echo $document; ?>-grid';
		   var inputSelector='#'+id+' .filters input, '+'#'+id+' .filters select';
		   $(inputSelector).each( function(i,o) {
				$(o).val('');
		   });
		   var data=$.param($(inputSelector));
		   $.fn.yiiGridView.update(id, {data: data});
		   return false;
		});
		
		$(document).live('click',function()
		{
			$('.Metronic-alerts').hide();
		});
	</script>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content">

	<?php require($basePath.'/includes/header.php'); ?>

	<div class="clearfix"></div>

	<div class="page-container">		
		<?php require($basePath.'/includes/left_menu.php'); ?>		
		<div class="page-content-wrapper">
			<div class="page-content">				
				<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
								<h4 class="modal-title">Modal title</h4>
							</div>
							<div class="modal-body">
								 Widget settings form goes here
							</div>
							<div class="modal-footer">
								<button type="button" class="btn blue">Save changes</button>
								<button type="button" class="btn default" data-dismiss="modal">Close</button>
							</div>
						</div>						
					</div>					
				</div>
				
				<?php require($basePath.'/includes/header_breadcum.php'); ?>
				
				<div class="row">
					<div class="col-md-12 content-block">
						<?php if(Yii::app()->user->hasFlash('success')):?>
							<div  id="statusMsg" class="Metronic-alerts alert alert-success fade in update-div" >
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								<i class="fa-lg fa fa-check"></i>  <?php echo Yii::app()->user->getFlash('success'); ?>
							</div>
						<?php endif; ?>
						<span  id="statusMsg"></span>
						<?php echo $content; ?>
					</div>
				</div>
			</div>
		</div>		
		<a href="javascript:;" class="page-quick-sidebar-toggler">
			<i class="icon-close"></i>
		</a>
	</div>

	<div class="page-footer">
		<div class="page-footer-inner">		 
		</div>
		<div class="page-footer-tools">
			<span class="go-top">
				<i class="fa fa-angle-up"></i>
			</span>
		</div>
	</div>
</body>

</html>

<!--[if lt IE 9]>
<script src="../../plugins/respond.min.js"></script>
<script src="../../plugins/excanvas.min.js"></script> 
<![endif]-->
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-migrate-1.2.1.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-ui/jquery-ui-1.9.2.custom.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap/js/bootstrap.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery.blockui.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/jquery.cokie.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootbox/bootbox.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/ui-blockui.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/metronic.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/layout.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/index.js'); ?>
<script>
	jQuery(document).ready(function() {    
	   Metronic.init(); 
	   Layout.init(); 	 
	   Index.init();   
	});
</script>

<style>
	.errorMessage{
		color:red;
		padding-left:5px;
		display: block;
	}
	.note-danger{
		color:red;
	}
</style>
