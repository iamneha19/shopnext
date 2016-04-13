<!--************* alert div ************-->
<div class="alert_box review_alert">
	<div class="alert_title">Review<div class="alert_close"></div></div>
	<div class="msg_div">
	  <p>Your review has submitted successfully. Review will be approved by Admin!</p>
	</div>
 </div>
<!--************* alert div ************--> 

<script type="text/javascript">
	$(document).ready(function()
	{
		var check_session='<?php echo ApplicationSessions::run()->read('user_email'); ?>';
		$('#review-box').click(function(){
			if(check_session=='')
			{
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			}else{		
				$(this).focus();
			}
		});
		
		$('#review-submit-btn').on('click',function(){
			if(check_session=='')
			{
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			}
			else
			{	
		        var review =  $('#review-box').val();

		        if($('.shopId').val())
		        {
					var shop_id = $('.shopId').val();
		        }
		        else
		        {
		        	var shop_id = '';
		        }

		        if($('.dealId').val())
		        {
					var deal_id = $('.dealId').val();
		        }
		        else
		        {
		        	var deal_id = '';
		        }

		        if($('.productId').val())
		        {
					var product_id = $('.productId').val();
		        }
		        else
		        {
		        	var product_id = '';
		        }

		        if(review != '') {
		            $.ajax({
							type: "POST",
							url: '<?php echo Yii::app()->createUrl('shop/review') ?>',
							dataType: "json",
							data: {review:review,shop_id:shop_id,deal_id:deal_id,product_id:product_id},
							success: function(data)
							{   
								if(data.success){
									
									$('#review-box').val('');
									$('#overlay').fadeIn();
									$('.review_alert').fadeIn();
									$('.review_alert').animate({top:160},300);
									$('#review-box').attr('readonly', 'readonly');
									// alert('Review will be approved by Admin!.');    
								}
								else
								{
									if(data.login_status)
									{
										alert('Something went wrong!! Please enter valid data.');
									}
									else
									{
										$('#overlay').fadeIn();
										$('#login_main').fadeIn();
										$('#login_main').animate({top:50},300);
									} 
								}
							}
		            });
		        }
		        else
		        {
					$('#review-box').attr('placeholder','Add your review to post');
					$('#review-box').click();
				}
			} 
	    });

		var offset = '<?php echo $offset+$limit; ?>';
		var limit = '<?php echo $limit; ?>';
		if($('.shopId').val())
        {
			var shop_id = $('.shopId').val();
        }
        else
        {
        	var shop_id = '';
        }

        if($('.dealId').val())
        {
			var deal_id = $('.dealId').val();
        }
        else
        {
        	var deal_id = '';
        }

        if($('.productId').val())
        {
			var product_id = $('.productId').val();
        }
        else
        {
        	var product_id = '';
        }

		$('#center_div').on('click','#load_more',function(){
		$('#load_more').html("<a href = '#'> LOADING PLEASE WAIT....</a>").css('background','#50618E');
		$.ajax({
				type:"POST",
				url:site_url+"/shop/reviewList",
				data:{pagination:offset,shopId:shop_id,dealId:deal_id,productId:product_id},
				success:function(result){
					var obj = jQuery.parseJSON( result );
					if(obj.data!='')
					{
						$('#append_more').append(obj.data);
						$('#append_more').find('abbr.timeago').timeago();
						$('#load_more').html('<a href="javascript:void(0);">LOAD MORE REVIEWS</a>').css('background','#1e3060');
						offset = parseInt(offset)+parseInt(limit);
					}else{
						$('#load_more').hide();
						$('.load_more_msg').html("NO MORE REVIEWS TO DISPLAY....");				
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$('#center_div').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
						window.location.reload();

					}
			});
		});

		$('#center_div1').on('click','#load_more',function(){
		$('#load_more').html("<a href = '#'> LOADING PLEASE WAIT....</a>").css('background','#50618E');
		$.ajax({
				type:"POST",
				url:site_url+"/shop/reviewList",
				data:{pagination:offset,shopId:shop_id,dealId:deal_id,productId:product_id},
				success:function(result){
					var obj = jQuery.parseJSON( result );
					if(obj.data!='')
					{
						$('#append_more').append(obj.data);
						$('#append_more').find('abbr.timeago').timeago();
						$('#load_more').html('<a href="javascript:void(0);">LOAD MORE REVIEWS</a>').css('background','#1e3060');
						offset = parseInt(offset)+parseInt(limit);
					}else{
						$('#load_more').hide();
						$('.load_more_msg').html("NO MORE REVIEWS TO DISPLAY....");				
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$('#center_div').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
						window.location.reload();

					}
			});
		});
	});
</script>