<!doctype html>
<html itemscope itemtype="http://schema.org/<?php echo $this->page_type; ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0" />
<title><?php echo $this->page_title; ?></title>

<!-- Meta Data -->
<meta itemprop="name" content="<?php echo $this->page_title; ?>">
<meta itemprop="description" content="<?php echo $this->page_description; ?>">
<meta itemprop="image" content="<?php echo ($this->page_image) ? $this->page_image : Yii::app()->theme->baseUrl.'/images/shopnex_logo.jpg'; ?>">

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@shopnext" />
<meta name="twitter:image" content="<?php echo ($this->page_image) ? $this->page_image : Yii::app()->theme->baseUrl.'/images/shopnex_logo.jpg'; ?>" />
<meta name="twitter:title" content="<?php echo $this->page_title; ?>" />
<meta name="twitter:description" content="<?php echo $this->page_description; ?>" />
<meta name="twitter:url" content="<?php echo $this->tweet_url; ?>" />

<?php 
	$baseUrl = Yii::app()->theme->baseUrl; 
	$basePath = Yii::app()->theme->basePath; 
	$cs = Yii::app()->getClientScript();
?>

<?php $cs->registerCssFile($baseUrl.'/css/jquery.mCustomScrollbar.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/bootstrap.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/owl.carousel.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/owl.theme.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/prettify.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/slideshow.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/example.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/style.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/media.css'); ?>
<?php $cs->registerCssFile($baseUrl.'/css/custom.css'); ?>
<?php 
	$action = Yii::app()->controller->action->id;

	if($action=='shoppingCart')
	{
		$cs->registerCssFile($baseUrl.'/css/style-shop.css'); 
	}	
?>
<?php $cs->registerCssFile('http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'); ?>

<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
<?php $cs->registerScriptFile($baseUrl.'/js/jquery.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/scripts.js'); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<?php $cs->registerScriptFile($baseUrl.'/js/search_bar.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/jquery.mCustomScrollbar.concat.min.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/owl.carousel.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/bootstrap-collapse.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/bootstrap-transition.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/bootstrap-tab.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/prettify.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/application.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/gallery.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/jquery-ui.js'); ?>
<?php $cs->registerScriptFile($baseUrl.'/js/metronic.js'); ?>

 <!--fb share button js start -->
	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId: '772500282830657',
				status: true,
				cookie: true,
				xfbml: true
			});
		};

		(function(d, debug){var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];if   (d.getElementById(id)) {return;}js = d.createElement('script'); js.id = id; js.async = true;js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";ref.parentNode.insertBefore(js, ref);}(document, /*debug*/ false));

		function postToFeed(title, desc, url, image) {
			var obj = {method: 'feed',link: url, picture: image,name: title,description: desc};
			function callback(response) {}
			FB.ui(obj, callback);
		}
		
		$(document).ready(function(e) {
			$('#login_sign').on('click',function(){
				$('#login_main').animate({top:-500},300);
				$('#register').fadeIn();
				$('#register').animate({top:50},300)
			});
		});
	</script>
	
 <!--fb share button js end -->
</head>

<body>
	
	<div class="main-wrap">
		<div id="overlay"></div>
		<div id="preloader" style="display:none;"></div>
		<?php 
			require($basePath.'/includes/header.php');
		    require($basePath.'/includes/menu.php');
			//require($basePath.'/includes/search_bar.php'); 
		?>
		<?php echo $content; ?>
		<?php require($basePath.'/includes/footer.php'); ?>
	</div>
	
	<div id="login_main">
		 <div id="login_title">Login <div id="l_close" class="c_close"></div></div>
		 <div id="social_login">
		 <a href="#" onclick="facebookLogin();return false;" >
			<div class="l_cover l_tm30"><img src="<?php echo $baseUrl; ?>/images/sign-in-facebook.jpg"/></div>
		 </a>
		 <a href="#" onclick="googleLogin();return false;" >
			<div class="l_cover l_tm30"><img src="<?php echo $baseUrl; ?>/images/loginwithgoogle.jpg"/></div>
		 </a>
		 <div class="l_cover">Or use your email address</div>
		 <div id="l_btn_div">
			<input type="button" id="login" value="Log in">
			<input type="button" id="login_sign" value="Sign up">
			<div class="l_cover l_tm20">By logging in, you agree to Shopnext's <a href="#">Terms of Service</a>, <a href="#">Privacy Policy</a> and <a href="#">Content Policies</a>.</div>
		 </div>
		 </div>
		 
		 <div id="normal_login">
			<form id="login_form">
				<div class="l_cover">
				<span>EMAIL</span> <input class="input" type="text" name="SiteUserLogin[email]">
				</div>
				<div class="l_cover">
				<span>PASSWORD</span> <input class="input" type="password" name="SiteUserLogin[password]">
				</div>
				<div class="l_cover">
				<a href="javascript:void(0);" id="f_pass">Forgot password?</a>
				</div>
				<div id="l_btn_div">
				<input type="button" id="back" value="Back">
				<input type="submit" id="login" value="Log In">
				<div class="l_cover l_tm20" style="text-align:center">By logging in, you agree to Shopnext's <a href="#">Terms of Service</a>, <a href="#">Privacy 			Policy</a> and <a href="#">Content Policies</a>.</div>
				</div>
			</form>
		 </div>
		 
		 <div id="f_pass_div">
			<div class="l_cover" id="confirm_msg">Please enter the email address you signed up with and we'll send you a password reset link.</div>
			<div class="l_cover">
				<span>EMAIL</span> <input type="text" placeholder="Username" id="txt_mail">
			</div>
			<div class ="mail_msg"></div>
			
			<div id="l_btn_div">
			<input type="button" id="back" value="Back">
			<input type="button" id="rst_pwd" value="Reset Password" type="submit">
			</div>
		 </div>     
	</div>

	
	<div id="register">
		<div id="login_title">Register <div id="r_close"></div></div>
		<form id='register_form' action=''>
			<div class="l_cover">
				<span>FULL NAME</span> 
				<input class="input" type="text" name="User[name]">
			</div>
			<div class="l_cover">
				<span>EMAIL ADDRESS</span> 
				<input class="input" id="email" type="text" name="User[email]">
			</div>
			<div class="l_cover">
				<span>PASSWORD</span> 
				<input class="input" type="password" name="User[password]" id="password" >
			</div>
			<div class="l_cover">
				<span>CONFIRM PASSWORD</span> 
				<input class="input" type="password" name="User[repeat_password]"  id="confirm_password">
			</div>
			<div class="l_cover">
				<input type="checkbox" name="User[send_newsletter]">Send me occasional email updates
			</div>
			<div id="l_btn_div">
				<input type="submit" id="regi" value="Register">
			</div>
		</form>
     </div>
	 
	 <div class="alert_box sign_up_alert">
		<div class="alert_title">Thank You for Sign-up!<div class="alert_close"></div></div>
		<div class="msg_div">
		  <p>Your account has been created, please verify it by clicking the activation link that has been send to your email.</p>
		  <p>Please check spam also, if not received in inbox.</p>
		   <!--<div class="alert_btn"><input type="button" value="Resend" /></div>-->
		</div>
     </div>
	 
	 <div id="shop-style"></div>
	 <div class="cart_main"></div>
	 
	 
	 <!--************* popup div ************-->
	 <?php $action = Yii::app()->controller->action->id;
		if($action=="shopDetails"){
	 ?>
<?php if(count($this->dataImages->shopImages)>0) { ?>
	<div class="img_popup_cont">
		<div class="main_div">
			<div id="demo">
				<div class="container">
					<div class="span12">
						<div class="title_cont_popup">
							<div class="prod_title">Shop Photos</div>
							<?php $count = 0;
								foreach($this->dataImages->shopImages as $img)
								{
									if($img->status=="1" && $img->active_status=="S")
									{
										$count++;
									}
								}
							?>
							
							<div class="prod_subtitle">Over <?php echo $count;?> + Photos</div>
						</div>
						<div class="customNavigation">
							<a class="btn prev"><img src="<?php echo $baseUrl; ?>/images/leftarrow.png"/></a>
							<a class="btn next"><img src="<?php echo $baseUrl; ?>/images/rightarrow.png"/></a>
						</div>

						<div id="owl-shop-images" class="owl-carousel">
							<?php 
								$i=1; 

								foreach($this->dataImages->shopImages as $img) 
								{ 
									if($img->status=="1" && $img->active_status=="S")
									{
										if ($i==1 || $i%7==0)
										{
							?>
											<div class="item">
												<div class="l_cont_popup">
							<?php
										}
							?>      
									<div class="prod_cont">
										<div class="product_ic">
											<div class="add_img"><img src="<?php echo Yii::app()->baseUrl."/upload/shop/".$img->image; ?>"/> </div>
										</div>
									</div>
							<?php
										if($i%6==0)
										{
							?>
												</div>
											</div>
							<?php
										} 

										$i++;
									}
								}
							?>    
						</div>       
						<div class="close_btn_popup">Close</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php } ?>
<?php } ?>

<!--************* end popup div ************-->
	 
	
	 <script>	
		$(document).ready(function(e) {
			$("#register_form").validate({
				onkeyup:false,
				rules: {
					'User[name]':  {
						required: true,
						minlength: 5
					},
					'User[email]': {
						required: true,
						email: true,
						remote: { // check email exists.
						url: "<?php echo Yii::app()->createUrl("site/emailcheck"); ?>",
						type: "post",
						data: {
						  email: function() {
							return $( "#email" ).val();
						  }
						}
					  }
					},
					'User[password]':{
						required: true,
						minlength: 8
					},
					'User[repeat_password]': {
						required: true,
						minlength: 8,
						equalTo : "#password"
					},
				},
				messages: {
					'User[email]': {
						remote:"This email id has already been taken."
					}
				},
				submitHandler: function(form) {
					var data = new FormData($('#register_form')[0]);
					$('#register_form')[0].reset();
					$('#preloader').show();
					// $('#register_form').html('<div class="l_cover"><span>processing..</span></div>');
					$.ajax({
						type:"POST",
						dataType:"json",
						url:"<?php echo Yii::app()->createUrl("site/register"); ?>",
						data: data,
						cache: false,
						contentType : false,
						processData : false,					
						success: function(result){
							$('#preloader').hide();
							if(result.success){
								$('#register').fadeOut();
								$('#register').animate({top:-500},300);
								$('.sign_up_alert').fadeIn();
								$('.sign_up_alert').animate({top:160},300);
								// window.location="<?php echo Yii::app()->params['SERVER']; ?>site/page?view=message";
							}
							else
							{
								// Show server side validations if any.
								jQuery.each(result.error, function(i, val) {
								//	Show error with respective to field. 
								   $('#register_form').find('input[name="User['+i+']"]').after( '<label id="User['+i+']-error" for="User['+i+']" class="error">'+val+'</label>' );
								});
							}
						},
					});
				}
			});
			
			$("#login_form").validate({
				onkeyup:false,
				rules: {
					'SiteUserLogin[email]': {
						required: true,
						email: true,
					},
					'SiteUserLogin[password]':{
						required: true,
					},
				},
				submitHandler: function(form) {
					var data = new FormData($('#login_form')[0]);
					$.ajax({
						type:"POST",
						dataType:"json",
						url:"<?php echo Yii::app()->createUrl("site/login"); ?>",
						data: data,
						cache: false,
						contentType : false,
						processData : false,					
						success: function(result){
							if(result.success){
								location.reload();
							}
							else
							{
								$('#login_form').find('input[name="SiteUserLogin[password]"]').next().remove();
								if(result.errorCode==5)
								{
									$('#login_form').find('input[name="SiteUserLogin[password]"]').after( '<label id="SiteUserLogin[password]-error" for="SiteUserLogin[password]" class="error">Your account is deleted.</label>' );
								}
								else if(result.errorCode==6)
								{
									$('#login_form').find('input[name="SiteUserLogin[password]"]').after( '<label id="SiteUserLogin[password]-error" for="SiteUserLogin[password]" class="error">Your account not activated yet.</label>' );
								}
								else
								{
									$('#login_form').find('input[name="SiteUserLogin[password]"]').after( '<label id="SiteUserLogin[password]-error" for="SiteUserLogin[password]" class="error">Please check email and password.</label>' );
								}
							}
						},
					});
				}
			});
		});
	</script>
	<script>
		function  googleLogin()
		{
			var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
				 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
				 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
				 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
				 width    = 500,
				 height   = 270,
				 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
				 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
				 features = (
					'width=' + width +
					',height=' + height +
					',left=' + left +
					',top=' + top
				  );

			newwindow=window.open('<?php echo Yii::app()->createUrl("site"); ?>/googlelogin','Login_by_facebook',features);

			 if (window.focus) {newwindow.focus()}
			return false;
		}
	</script>
	<script>
	
	function  facebookLogin()
	{	
		FB.login(function (response) 
		{
			if (response.authResponse) 
			{
				access_token = response.authResponse.accessToken;
				
				FB.api('/me', function (response) 
				{
					name = response.name;
					user_id = response.id;
					user_email = response.email;
					gender = response.gender;
					FB.api('/me/picture?type=normal', function (response) 
					{
						profile_image = response.data.url;
						//var data = "access_token="+access_token+"&name="+name+"&user_id="+user_id+"&user_email="+user_email+"&gender="+gender+"&profile_image="+profile_image;
						var data = {
									access_token : access_token,
									name : name,
									user_id : user_id,
									user_email : user_email,
									gender : gender,
									profile_image : profile_image
								}
						$.ajax({
								url:"<?php echo Yii::app()->createUrl("site/fblogin"); ?>" ,
								type:'POST',
								data:data,
								success:function(obj){
									if(obj=="200")
									{
										window.location.reload();
									}
								},
								error: function(XMLHttpRequest, textStatus, errorThrown) {
										alert(textStatus);
										// $('#confirm_msg').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
										// window.location.reload();
									} 	
							});
					});
				});
				
			}
			else 
			{
				alert("Login attempt failed!");
			}
		  }, { scope: 'email' });
	}
	$('#rst_pwd').click(function(){
	
		var txt_mail = $('#txt_mail').val();
		$('.mail_msg').css({'padding-left':'0.5cm','color':'red'});
		if(txt_mail!='')
		{	
			$($('#txt_mail')).addClass('spinner');
			$('.rst_pwd').attr('disabled','disabled');
			$.ajax({
				url:"<?php echo Yii::app()->createUrl("site/forgotpassword"); ?>" ,
				type:'POST',
				data:{txt_mail:txt_mail},
				success:function(result){
					var obj = jQuery.parseJSON(result);
					if( obj.result == '200' ){
							$('#txt_mail').val('');
							$('.mail_msg').css({'padding-left':'0.5cm','color':'green'}).html(obj.msg);
							setTimeout(function(){ location.reload(); }, 4000);
						}else{
							$('#txt_mail').focus();
							$('.mail_msg').html(obj.msg);
							$('#rst_pwd').attr('disabled','disabled');
						}
					$('#rst_pwd').attr('disabled',false);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					$('.mail_msg').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
					// window.location.reload();
				}	
			});
		}else{
			$('.mail_msg').html('Username is required !').show();
		}
	});
	</script> 
	<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow79.js"></script>
</body>
</html>
