<?php
	if(!empty(Yii::app()->controller->module->id))
	{
		$module = 	Yii::app()->controller->module->id;
	}
	else
	{
		$url = explode("/",$_SERVER['REQUEST_URI']);
		$module = 	$url[2];
	}
	
	if($module=='superadmin')
	{
		$profile_pic = ApplicationSessions::run()->read('admin_pic');
		if($profile_pic!='' && file_exists(Yii::app()->basePath.'/../upload/admin/'.$profile_pic))
		{
			$profile_pic = Yii::app()->baseUrl.'/upload/admin/'.$profile_pic;
		}
		else 
		{
			$profile_pic = $baseUrl."/img/avatar.png";
		}
	}else{
		$profile_pic = ApplicationSessions::run()->read('owner_pic');
		if($profile_pic!='' && file_exists(Yii::app()->basePath.'/../upload/owner/'.$profile_pic))
		{
			$profile_pic = Yii::app()->baseUrl.'/upload/owner/'.$profile_pic;
		}
		else 
		{
			$profile_pic = $baseUrl."/img/avatar.png";
		}
	}
?>
<div class="page-header navbar navbar-fixed-top">	
	<div class="page-header-inner">		
		<div class="page-logo">
			<a href="<?php echo Yii::app()->createUrl($module.'/default/index'); ?>">
			<img src="<?php echo $baseUrl; ?>/img/logo.png" alt="logo" class="logo-default"/>
			</a>
			<div class="menu-toggler sidebar-toggler hide">
				
			</div>
		</div>	
		
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
		
		<div class="top-menu">		
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown dropdown-user">				
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" class="img-circle" src="<?php echo $profile_pic; ?>" height="40" width="40"/>
						<span class="username">
							<?php  
									if(ApplicationSessions::run()->read('type')=='admin')
									{
										echo ApplicationSessions::run()->read('admin_name');
									}else{
										echo ApplicationSessions::run()->read('owner_fullname');
									}
							?>
						</span>
						<i class="fa fa-angle-down"></i>
					</a>					
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo Yii::app()->createUrl($module.'/default/view'); ?>">
								<i class="icon-user"></i> 
								My Profile 
							</a>
						</li>
                        <li>
							<a href="<?php echo Yii::app()->createUrl($module.'/default/changepassword'); ?>">
								<i class="icon-key"></i> 
								Change Password 
							</a>
						</li>
						<li>
							<a href="<?php echo Yii::app()->createUrl($module.'/default/logout'); ?>">
								<i class="fa fa-power-off"></i> 
								Log Out 
							</a>
						</li>
					</ul>					
				</li>				
			</ul>			
		</div>		
	</div>	
</div>
