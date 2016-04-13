
$(document).ready(function(e) {
    $('#menu').hover(function(){ // menu effect--------------------------------------
		$('#submenu').stop().fadeIn(300)
		},function(){
			$('#submenu').stop().fadeOut(300);
	});
	

	
	// To hide user dropdown when clikced outside.
	$(document).click(function(event) {
		if ($(event.target).closest('#logger_submenu').length === 0 && !$(event.target).hasClass('fa-angle-double-down')) {
			$('#logger_submenu').hide();
		}
	});
	
	$(".down-arrow").on('click',function(){
		$("#logger_submenu").toggle();
	});




// Email Box Scripts ---------------------------------------------------------------------------------------
        $('#center_div').on('click','.email',function(){
		$('#overlay').fadeIn(300);
		$('#mail_box').fadeIn(300)
	});
	
	$('#close').on('click',function(){
		$('#overlay').fadeOut(300);
		$('#mail_box').fadeOut(300);
		$('#mail_box').hide();
		$('#mail_box textarea').val('');
		$('.to_email').val('');
		window.location.reload();
		return false;
	});
		
		
// login Box Scripts ---------------------------------------------------------------------------------------	
		$('#l_close').on('click',function(){ // close login box
			$('#login_main').animate({top:-500},300);
			$('#login_main').fadeOut();
			$('#overlay').fadeOut();
			$('#login_main input[type=text]').val('');
			window.location.reload();
			});	
			
		$('#login_btn').on('click',function(){ // Open loginbox
			$('#overlay').fadeIn();
			$('#login_main').fadeIn();
			$('#login_main').animate({top:50},300);
			return false;
			});
			
		$('#login').on('click',function(){  // Normal Login
			$('#social_login').hide();
			$('#normal_login').fadeIn();
			});
			
		$('#back').on('click',function(){ // back to the social Login
			$('#social_login').fadeIn();
			$('#normal_login').hide();
			});
			
		$('#f_pass').on('click',function(){ // back to the social Login
			$('#f_pass_div').fadeIn();
			$('#normal_login').hide();
			});
			
		$('#f_pass_div #back').on('click',function(){ // back to the social Login
			$('#normal_login').fadeIn();
			$('#f_pass_div').hide();
			});
		
		// $('html').on('click',function(){ // hide add to cart popup
			// $('.cart_main').fadeIn();
			// $('.cart_main').hide();
			// $('#overlay').fadeIn();
			// $('#overlay').hide();
			// });
		
		$('#overlay').on('click',function(){ // hide login and register pop up
			window.location.reload();
			// $('.cart_main').fadeIn();
			$('.cart_main').hide();
			// $('#login_main').fadeIn();
			$('#login_main').hide();
			// $('#register').fadeIn();
			$('#register').hide();
			// $('#overlay').fadeIn();
			$('#overlay').hide();
			$('#mail_box').hide();
			$('.alert_box').hide();
			//window.location.reload();

			});
			
			
		
			
			
 // register box--------------------------------------------
		$('#register_btn').on('click',function(){// Open register box
			$('#overlay').fadeIn();
			$('#register').fadeIn();
			$('#register').animate({top:50},300)
		});

		$('#register #r_close').on('click',function(){ // close register box
			$('#overlay').fadeOut();
			$('#register').fadeOut();
			$('#register').animate({top:-500},300);
			$('#register input[type=text]').val('');
			window.location.reload();
			return false
		});
			
		

 // alert box--------------------------------------------
 
		$('.alert_box .alert_close').on('click',function(){ // close register box
			window.location.reload();
			$('#overlay').fadeOut();
			$('.alert_box').fadeOut();
			$('.alert_box').animate({top:-500},300);
			return false;
		});

 
			
});
(function($){
	$(window).load(function(){
		$("#submenu").mCustomScrollbar();
		
	});
})(jQuery);

// cart_main box--------------------------------------------
(function($){
        $(window).load(function(){
            $("#cart_data").mCustomScrollbar();
        });
    })(jQuery);
	
function addToCart(id)
{
	var shop_id = $(id).attr('data-shop');
	var product_id = $(id).attr('data-product');
	var user_id = $(id).attr('data-user');
	if(user_id=='')
	{
		$('#overlay').fadeIn();
		$('#login_main').fadeIn();
		$('#login_main').animate({top:50},300);
	}
	else
	{
		$('#preloader').show();
		$.ajax({
			type:"POST",
			url:site_url+"/shop/addToCart",
			data:{shop_id:shop_id,user_id:user_id,product_id:product_id,qty:1},
			success: function(result){
				console.log(result);
				if(result!='400')
				{
					if(result!='401')
					{
						result = result.split("::");
						$('#preloader').hide();
						$('#overlay').fadeIn();
						$('#shop-style').html('<link rel="stylesheet" type="text/css" href="'+site_url+'/themes/frontendTheme/css/style-shop.css" />');
						$('.cart_main').html(result[0]);
						$('.numberCircle').html(result[1]);
						$('.cart_main').fadeIn();
						$('.cart_main').animate({top:50},300);
					if(result=='402')
					{
						result = result.split("::");
						alert("You cannot enter more that 10 products!!");
						return false;
					}
					}else{
						$('#preloader').hide();
						$('#overlay').fadeIn();
						$('#login_main').fadeIn();
						$('#login_main').animate({top:50},300);
					}	
					
				}
			},
		});
	}
}
	
function closeCart()
{ 
	$('#overlay').fadeOut();
	$('#shop-style').html();
	$('.cart_main').fadeOut();
	$('.cart_main').animate({top:-500},300);
}

function delItem(product_id)
{ 
	$.ajax({
				type:"POST",
				url:site_url+"/shop/deleteCart",
				data:{product_id:product_id},
				success: function(result){
					if(result!='400')
					{
						result = result.split("::");
						$('#overlay').fadeIn();
						$('#shop-style').html('<link rel="stylesheet" type="text/css" href="'+site_url+'/themes/frontendTheme/css/style-shop.css" />');
						$('.cart_main').html(result[0]);
						$('.numberCircle').html(result[1]);
						$('.cart_main').fadeIn();
						$('.cart_main').animate({top:50},300);
					}
				},
			});
}

function updateCart(product_id)
{
	var qty = $('#'+product_id).val();
	
	var num = /^[0-9\s\+]+$/;
	if(qty=='' || isNaN(qty) || qty==0 || qty < 1)

	{
		var pre_val = $('#'+product_id).attr("value");
		var qty = $('#'+product_id).val(pre_val);
	}
	if(!qty.match(num))
	{
		var pre_val = $('#'+product_id).attr("value");
		var qty = $('#'+product_id).val(pre_val);
	}
	if(qty >100)
	{
		var pre_val = $('#'+product_id).attr("value");
		var qty = $('#'+product_id).val(pre_val);
	}
	else
	{
		$.ajax({
				type:"POST",
				url:site_url+"/shop/updateCart",
				data:{product_id:product_id,qty:qty},
				success: function(result){
					if(result!='400')
					{
						$('#overlay').fadeIn();
						$('#shop-style').html('<link rel="stylesheet" type="text/css" href="'+site_url+'/themes/frontendTheme/css/style-shop.css" />');
						$('.cart_main').html(result);
						$('.cart_main').fadeIn();
						$('.cart_main').animate({top:50},300);
					}
				},
			});
	}
}

function viewCart()
{
	$.ajax({
				type:"POST",
				url:site_url+"/shop/viewCart",
				data:'',
				success: function(result){
					if(result!='400')
					{
						if(result!='401')
						{
							$('#overlay').fadeIn();
							$('#shop-style').html('<link rel="stylesheet" type="text/css" href="'+site_url+'/themes/frontendTheme/css/style-shop.css" />');
							$('.cart_main').html(result);
							$('.cart_main').fadeIn();
							$('.cart_main').animate({top:50},300);
						}else{
							$('#preloader').hide();
							$('#overlay').fadeIn();
							$('#login_main').fadeIn();
							$('#login_main').animate({top:50},300);
						}	
						
						
					}
				},
			});
}
// $('.close_bt').on('click',function(){
	// window.location.reload();
// });

$(document).keyup(function(e) {    
    if (e.keyCode == 27) { //escape key

        window.location.reload();
    }
});
		

