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
	
	if($module=="superadmin")
	{
		$menu = ApplicationSessions::run()->read('menu_options');
	}
	else
	{
		$menu = ApplicationSessions::run()->read('owner_menu_options');
	}
	
	$controller = Yii::app()->controller->id;
	$action = Yii::app()->controller->action->id;
?>

	<div class="page-sidebar-wrapper">		
		<div class="page-sidebar navbar-collapse collapse">			
			<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">				
				<li class="sidebar-toggler-wrapper">					
					<div class="sidebar-toggler"></div>					
				</li>				
				<li class="sidebar-search-wrapper">&nbsp;</li>
				<?php	
					if(!empty($menu)) 
					
					{	
						foreach($menu['menu'] as $menu=>$mval) 
						{	
							if($mval['visibility'])
							{
								$menuclass =  "";
								if( 
									(array_key_exists("submenu",$mval) && array_key_exists($controller,$mval['submenu'])) || 
									(array_key_exists("controller",$mval) && $mval['controller'] == $controller)
								)
								{
									$menuclass =  "active open";
								}
				?>
					<li class="<?php echo $menuclass;?>">					
						<a href="javascript:;">
							<i class="<?php echo $mval['menu-icon']?>"></i>
							<span class="title">
								<?php echo $menu?>
							</span>
							<span class="arrow"></span>
						</a>						
						<ul class="sub-menu">						
							<?php	
								if(array_key_exists("submenu",$mval) && is_array($mval['submenu']) && !empty($mval['submenu'])) 
								{	
									foreach($mval['submenu'] as $submenu=>$sval) 
									{	
										if($sval['visibility'])
										{
								?>
										<li class="<?php if($submenu == $controller){echo "active";}?>">
											<a href="javascript:;">
												<i class="<?php echo $sval['icon']?>"></i> 
												<?php echo $sval['name']?> 
												<span class="arrow"></span>
											</a>
											<ul class="sub-menu">
											
												<?php	if(array_key_exists("options",$sval) &&  array_key_exists("list",$sval['options']) && $sval['options']['list']){?>											
												<li class="<?php if($submenu == $controller && $action=="admin"){echo "active";}?>">
													<a href="<?php echo Yii::app()->createUrl('/'.$module.'/'.$submenu.'/admin'); ?>">
														<i class="icon-list"></i>
														List <?php echo $sval['name'];?>
													</a>
												</li>											
												<?php }?>
												
												<?php	if(array_key_exists("options",$sval) &&  array_key_exists("add",$sval['options']) && $sval['options']['add']){?>
												<li class="<?php if($submenu == $controller && $action=="create"){echo "active";}?>">
													<a href="<?php echo Yii::app()->createUrl('/'.$module.'/'.$submenu.'/create'); ?>">
														<i class="fa fa-plus-square"></i>
														Add <?php echo $sval['name'];?>
													</a>
												</li>
												<?php }?>											
												
											</ul>
										</li>
							<?php
										}
									}
								}
							?>
							
							<?php	
								if(array_key_exists("controller",$mval) && !array_key_exists("submenu",$mval)) 
								{
									if(array_key_exists("options",$mval) &&  array_key_exists("list",$mval['options']) && $mval['options']['list'])
									{
							?>								
									<li class="<?php if($mval['controller'] == $controller && $action=="admin"){echo "active";}?>">
										<a href="<?php echo Yii::app()->createUrl('/'.$module.'/'.$mval['controller'].'/admin'); ?>">
											<i class="icon-list"></i>
											List <?php echo $menu?>
										</a>
									</li>								
							<?php 
									}
									if(array_key_exists("options",$mval) &&  array_key_exists("add",$mval['options']) && $mval['options']['add'])
									{
							?>
									<li class="<?php if($mval['controller'] == $controller && $action=="admin"){echo "active";}?>">
										<a href="<?php echo Yii::app()->createUrl('/'.$module.'/'.$mval['controller'].'/create'); ?>">
											<i class="fa fa-plus-square"></i>
											Add <?php echo $menu?>
										</a>
									</li>
							<?php 
									}
								} 
							?>
							
						</ul>
					</li> 
				<?php 
							}
						}
					}
				?>
			</ul>			
		</div>
	</div>
	