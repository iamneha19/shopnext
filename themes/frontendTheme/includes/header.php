<?php 
	$fullname = ApplicationSessions::run()->read('fullname');
	$username = ApplicationSessions::run()->read('username');
 
	$latitude  = "";
	$longitude = "";
	$current_location = "";
	$user_location_det  = ApplicationSessions::run()->read('user_location_det');
	$user_search  = ApplicationSessions::run()->read('user_search');
	if(!isset($user_location_det) || empty($user_location_det))	{
		$user_location_det = $this->getUserGeolocation();		
	}

	if(!empty($user_search['term']))
	{
		$current_location = $user_search['term'];
	}
	elseif($user_location_det['city']!='' && $user_location_det['state']!='') {
		$current_location = $user_location_det['city'].", ".$user_location_det['state'];
	}

	if(!empty($user_search['latitude']) && !empty($user_search['longitude']))
	{
		$latitude = $user_search['latitude'];
		$longitude = $user_search['longitude'];
	}
	elseif($user_location_det['latitude']!='' && $user_location_det['longitude']!='') {
		$latitude = $user_location_det['latitude'];
		$longitude = $user_location_det['longitude'];
	}
	
?>
<header id="top">
	<div id="top_container">
		<div id="logo"><a href="<?php echo Yii::app()->getBaseUrl(true) . '/'; ?>"><img src="<?php echo $baseUrl; ?>/images/shopnex_logo.jpg"/></a></div>
		<div id="login_box">
			<?php if($this->user_id){ ?>	
				<div class="login_box_content">
                <a href="javascript:void(0);" onclick="viewCart();">
					<img src="<?php echo $baseUrl."/images/cart_notification.png" ?>" class="notification_cart" title = "Cart" />
					<span class="numberCircle">
						<?php 
							$cart_count = ApplicationSessions::run()->read('cart_count');
							
							if(!empty($cart_count))
							{
								echo $cart_count;
							}
							else
							{
								echo "0";
							}
						?>
					</span>
				</a>
                <a href="<?php echo Yii::app()->createUrl('site/index'); ?>">
					<img src="<?php echo $baseUrl; ?>/images/notification.jpg" title="Notification"/>
					<span class="notific_circle">
					<?php 
						$deal_notification = ApplicationSessions::run()->read('deal_notification');
						
						if(!empty($deal_notification))
						{
							echo $deal_notification;
						}
						else
						{
							echo "0";
						}
					?>
					</span>
				</a>
				</div>
				<div class="login_box_content1">
                   <ul id="logger_menu" >
                     <li><div class="login_nm" style="float:left;margin-left:-45px;"><?php echo ($username!='')?$username:$username ;?><a href="javascript:void(0);" class='down-arrow'><span style="padding-left: 4px;"><i class="fa fa-angle-double-down"></i></span></a></div>
                         <ul id="logger_submenu" style="display:none;">
						 <li><a href="<?php echo Yii::app()->createUrl("user/profile"); ?>"><i class="fa fa-user">&nbsp;&nbsp;</i>View Profile</a></li>
							<li><a href="<?php echo Yii::app()->createUrl("user/myprofile"); ?>"><i class="fa fa-pencil">&nbsp;&nbsp;</i>Update Profile</a></li>
							<li><a href="<?php echo Yii::app()->createUrl("user/orders"); ?>"><i class="fa fa-pencil">&nbsp;&nbsp;</i>View Order History</a></li>
							<li><a href="<?php echo Yii::app()->createUrl("user/changepassword"); ?>"><i class="fa fa-exchange">&nbsp;&nbsp;</i>
                            Change Password</a></li>
							<li><a href="<?php echo Yii::app()->createUrl("site/logout"); ?>">
                            <i class="fa fa-sign-out">&nbsp;&nbsp;</i>Logout</a></li>
						   
					   </ul>
                      </li>
                   </ul>
                </div>
			<?php }else{ ?>
				<div class="login_box_content"><a href="#" id="register_btn">Sign Up</a></div>
				<div class="login_box_content"><a href="#" id="login_btn">Login</a></div>
			<?php } ?>	
		</div>
		
		<div id="search1">			
				<div id="search_div">
					<div id="location_icon"></div>
					<input type="text" id="search_input" data-auto-loc="<?php echo $current_location;?>" name="searchgeoloc_input" placeholder="Select Location" autocomplete="off" value="<?php echo $current_location;?>">
					<input type="hidden" id="latitude" data-auto-lat="<?php echo $latitude;?>" name="latitude" value="<?php echo $latitude;?>">
					<input type="hidden" id="longitude" data-auto-lng="<?php echo $longitude;?>" name="longitude" value="<?php echo $longitude;?>">
					<input type="hidden" id="entity_id" name="entity_id">
					<input type="hidden" id="entity_type" name="entity_type">			
					<!-- <a href="#" id="set_lo">set location</a> -->
					<div id="search_list">	
						<span class="detect_current">Detect current location</span>
					</div>
				</div>
				
				<form action="<?php echo Yii::app()->createUrl("site/listshop"); ?>" method="GET">
					<div id="search_product">
						<div id="search_icon"></div>
						<input type="text" id="search_input2" name="q" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : ''; ?>" placeholder="Shops, Super Markets..." autocomplete="off">
						<div id="shop_list">
						</div>
					</div>				
					<input type="submit" id="search_btn" value="SEARCH">
				</form>		
				
		</div>
		
		<div class="clr"></div>
	</div>
</header>
<script>
var site_url = '<?php echo Yii::app()->getBaseUrl(true); ?>';
</script>
<?php if($latitude=='' || $longitude=='' || $current_location==''){?>
<script type="text/javascript">		
	var visitorGeolocation = new geolocate(false, true, 'visitorGeolocation');
	var callback = function()
	{	
		search_input = visitorGeolocation.getField('city')+(visitorGeolocation.getField('state')) ? ", "+visitorGeolocation.getField('state') : "";
		document.getElementById('search_input').value = search_input;
		document.getElementById('longitude').value = visitorGeolocation.getField('longitude');
		document.getElementById('latitude').value = visitorGeolocation.getField('latitude');
	};
	visitorGeolocation.checkcookie(callback);
</script>
<?php }?>