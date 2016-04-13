<footer id="footer1">
	<div id="footer1_main">
		<div id="f_comp_detail">
			<div id="f_comp_logo"><img src="<?php echo $baseUrl; ?>/images/comp_logo.jpg" title="ShopNext"/></div>
			<strong>ShopNext</strong> India’s #1 Coupon 
			Website, helps you save money through its comprehensive listing of coupons, offers, deals & discounts.<br><br>
			Whether it’s shopping from top online brands or eating at your favourite local restaurants
		</div>

		<div id="f_sign_up">
			<div id="sign_up_title">Sign Up for our Newsletter</div>
			<div id="sign_up_desc">Sign up to get exclusive offers from our favorite brands, to be well up in the news.</div>
			<div id="sign_up_box">
				<div id="sign_up_div">
					<div id="edit"></div>
					<input type="text" id="sign_up_input" placeholder="Enter your email address"/>
					<input type="button" id="sign_up_button" value="SIGNUP"/>
				</div>
			</div>
			<div id="social_icon">
				<a href="#"><img src="<?php echo $baseUrl; ?>/images/fb.jpg"/></a>
				<a href="#"><img src="<?php echo $baseUrl; ?>/images/twit.jpg"/></a>
				<a href="#"><img src="<?php echo $baseUrl; ?>/images/google.jpg"/></a>
			</div>
		</div>

		<div id="quick_link">
			<div id="quick_link_title">Quick Link</div>
			<a href="#">Privacy Policy & Opt Out</a><br>
			<a href="#">Terms of Service</a><br><br>
			<a href="#">Stores</a><br>
			<a href="#">Categories</a><br>
			<a href="#">Your Location</a><br>
			<a href="#">ShopNext Offers</a><br>
		</div>
	</div>
</footer>

<footer id="footer2">
	<div id="footer2_main"><a href="#">Home</a><a href="#">Sitemap</a><a href="#">Contact Us</a></div>
	<div id="footer_rights">© 2015 ShopNext. All rights reserved. Designed and developed by sts.in</div>
</footer>

<script>
		$(document).ready(function(){
			
			$("#sign_up_button").click(function () 
			{
				var EmailText = $("#sign_up_input").val();
					
				if ($.trim(EmailText).length == 0) 
				{
					alert("Please enter an email address!");
					return false;
				}
				if (validateEmail(EmailText)) 
				{
					$.ajax({
						type:"POST",
						url:'<?php echo Yii::app()->createUrl("site/SubscribeNewsletter"); ?>',  
						data:{email_id:EmailText},
						success:function($result)
						{    
							if($result=='200')
							{
								alert('The Email Address is valid and you are successfully Subscribed!!');
								$("#sign_up_input").val('');
							}
							else if($result=='256')
							{
								alert('Email Address has already been registered!');
								$("#sign_up_input").val('');
							}
							else{
								alert("Something went wrong, please try again");
							}
						}
					});
				}
				else 
				{
					alert('Invalid Email Address. Please enter a Valid Email Address');
				}
				
				
			});
		});
	function validateEmail(sEmail) 
	{
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if (filter.test(sEmail)) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
//$("#sign_up_button").keypress(function(e){
//$('#sign_up_input').on('keypress', function(e) {
 // var code = (e.keyCode ? e.keyCode : e.which);
  //  if (code == 13) {
//alert();
//} */
//});
</script>