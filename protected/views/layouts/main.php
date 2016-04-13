<?php 
	$cs = Yii::app()->getClientScript();
	Yii::app()->bootstrap->register();
	// $user_id = ApplicationSessions::run()->read('user_id');
	$fullname = ApplicationSessions::run()->read('fullname');
	$username = ApplicationSessions::run()->read('username');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />
		
		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css" />
		
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<body>
		<div class="container" id="page">
			<div id="header">
				<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
			</div><!-- header -->
			<div id="mainmenu">				
				<ul id="yw0">				
					<li <?php if($this->id=='site' && $this->action->id=='index'){echo "class='active'";}?>><a href="/shopnext/site/index">Home</a></li>
					<li <?php if($this->id=='site' && $this->action->id=='about'){echo "class='active'";}?>><a href="/shopnext/site/page?view=about">About</a></li>
					<li <?php if($this->id=='site' && $this->action->id=='contact'){echo "class='active'";}?>><a href="/shopnext/site/contact">Contact</a></li>		
					<li <?php if($this->id=='deal' && $this->action->id=='latestdeals'){echo "class='active'";}?>><a href="/shopnext/deal/">Deals</a></li>	
					<li <?php if($this->id=='shop' && $this->action->id=='getshops'){echo "class='active'";}?>><a href="/shopnext/shop/getshops">Shops</a></li>	
					<?php if($this->user_id){ ?>				
						<li <?php if($this->id=='user' && $this->action->id=='myprofile'){echo "class='active'";}?>><a href="/shopnext/user/myprofile">My profile</a></li>	
						<li <?php if($this->id=='user' && $this->action->id=='changepassword'){echo "class='active'";}?>><a href="/shopnext/user/changepassword">Change password</a></li>
						<li><a href="<?php echo Yii::app()->createUrl("site/logout"); ?>">Logout(<?php echo ($username!='')?$username:$username ;?>)</a></li>	
					<?php }else{ ?>
						<li><a href="#" onclick="loadModal('login','Login');return false;"  class="login-btn">Login</a></li>
						<li><a href="#" onclick="loadModal('register','Register');return false;" class="register-btn">Register</a></li>
					<?php } ?>						
				</ul>					
			</div><!-- mainmenu -->			
			<?php echo $content; ?>			
			<div class="clear"></div>			
			<div id="footer">
				Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
				All Rights Reserved.<br/>
				<?php echo Yii::powered(); ?>
			</div><!-- footer -->			
			<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'general-modal')); ?>
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
					<h4></h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			<?php $this->endWidget(); ?>
		</div><!-- page -->
	</body>
</html>
<script>
	var $modal = $('#general-modal');	
    function loadModal(url,header)
	{
        var url = '<?php echo Yii::app()->createUrl("site"); ?>/'+url;
		$modal.find(".modal-header").find("h4").html(header);
        $modal.find(".modal-body").load(url);
        $modal.modal('show');
    }
		
	$(document).ready(function(){
		$modal.on('hidden.bs.modal',function(){			
			$modal.find(".modal-body").html('');
		});
	});	
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>