<?php $baseUrl = Yii::app()->theme->baseUrl;
		$imageUrl = Yii::app()->baseUrl;
		$imagePath = Yii::app()->basePath; 	
 ?>
<div id="wrapper">
<div id="main_div">
<aside id="left_side">
 <?php 
	if(!empty($banners)){ 
		foreach($banners as $banner){ 
			if($banner->type == 'I')
			{
				$banner_img = $imagePath."/../upload/banner/".$banner->banner;
				if($banner->banner!='' && file_exists($banner_img)){
				?>
					<div class="local_ads"><img src="<?php echo $imageUrl."/upload/banner/".$banner->banner; ?>"/></div>
				<?php 
				}else
				{ ?>
					<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/></div>
				<?php 	
				} 
			}
			else 
			{ ?>
				<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=code+image"/></div>	
			<?php 
			} 
		} 
	}else{ ?>
		<div class="local_ads"></div>
<?php 	} ?>
	
	
</aside>
<aside id="center_div">
	<?php if(isset($search_input) ) {
		echo '<p> Search results for <b>'.$search_input.'</b> near <b>'.$location.'</b></p>';
	}else{
	//	echo 'Latest deals and offers near you !!!';
	}
	?>
	<?php if(!empty($shops)){ ?>
	<div id='shop_search_results'>
		<?php
			$this->renderPartial("_search",array('shops'=>$shops));
		?>
	</div>
	<?php if(count($shops) == 10 ){ ?>
		<div id="load_more_shops"><a href="javascript:void(0);">LOAD MORE SHOPS</a></div>
		<span class="load_more_msg"></span>
	<?php } ?>
	<?php }else{ ?>
	<span class="load_more_msg">Not Found</span>
	<?php } ?>
</aside>

<aside id="right_side">
	<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
	<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
	<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
</aside>
<div class="clr"></div>
</div>
</div>
<script>
	var check_session='<?php echo ApplicationSessions::run()->read('user_email'); ?>'
		$('#center_div').on('click','.favourite.shop',function(){
			if(check_session=='')
			{
				$('#overlay').fadeIn();
				$('#login_main').fadeIn();
				$('#login_main').animate({top:50},300);
			}else{
				var content= $(this);
				var shop_id = $(this).attr('data-value');
				var user_id = $(this).attr('data-user'); 
				$.ajax({
					type:"POST",
					url:site_url+"/site/LikeStatus",
					dataType:'html',
					data:{shop_id:shop_id,user_id:user_id,type:'shop'},
					success: function(result){
						var obj = result.split("::");
						if(obj[0]!='false')
						{
							if(obj[1]=='L')
							{
								content.siblings('.like_text').html("<span style='color:#b5291c;'>Unlike</span>");
							}
							else
							{
								content.siblings('.shop_like .like_text').html("Like");
							}
						}else{
							$('#overlay').fadeIn();
							$('#login_main').fadeIn();
							$('#login_main').animate({top:50},300);
						}
					},
				});
			}
		});
</script>
<script>
  $('.popup').click(function(event) {
    var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = this.href,
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;
    
    window.open(url, 'twitter', opts);
 
    return false;
  });
</script>

<div id="mail_box">
	<div id="title">Email</div>
    <div id="close"></div>
   
   <div class="row">
	<div class="lable">*To:</div>
	<div class="input"><input type="text" class="to_email"></div>
    </div>
    
    <div class="row">
		<div class="lable">From:</div>
		<?php $from_session = ApplicationSessions::run()->read('user_email'); 
			if(!empty($from_session)){
		?>
		<div class="input"><input type="text" class="from_email" value="<?php echo $from_session; ?>"></div>
		<?php }else { ?>
			<div class="input"><input type="text" class="from_email"></div>
		<?php } ?>
    </div>
	 
    
    <div class="row">
    <div class="lable">Subject:</div>
    <div class="input"><input type="text" class="subject shadow"></div> 
    </div>
    
   	<div class="text_box shadow">
	 <div class="lable">Note -</div>
		<textarea class="body"></textarea>
	
    </div>
   
    <div class="row_link"><input type="button" value="Send Mail" class="send_btn" id="sd_bt">
	<span id="processed_msg"></span>
	</div>
	<?php $from_name = ApplicationSessions::run()->read('fullname'); 
	if(!empty($from_name)){?>
		<input type="text" class="from_name" value="<?php echo $from_name; ?>" style="visibility:hidden";>
	 <?php } ?>
    
</div>
<!--************* alert div ************-->
<div class="alert_box share_alert">
	<div class="alert_title">Email<div class="alert_close"></div></div>
	<div class="msg_div">
	  <p>Successfully sent email.</p>
	</div>
 </div>
<!--************* alert div ************--> 
<script>
/* $('.row').on('keydown',function(){
	var term = $('.to_email').val();
	 // alert(term);
	$.ajax({
			type:"GET",
			url:"site/AutosuggestUser",
			data:{term:term},
			success:function($result){
			},
	});
}); */
</script>
<script>
	$('#sd_bt').click(function(){
		var from_email = $('.from_email').val();
		var to_email = $('.to_email').val();
		var subject = $('.subject').val();
		var from_name = $('.from_name').val();
		var body = $('.body').val();
		if(to_email!='')
		{
			var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
			if(!filter.test(to_email))
			{
				alert('The email address "'+ to_email+'" is not recognized. Please fix it and try again.');
				return false;
			}
		}else{
			alert("Please specify atleast one recepient!");
			return false;
		}
			if(from_email!='')
			{
				var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
				if(!filter.test(from_email))
				{
					alert('The email address "'+ from_email+'" is not recognized. Please fix it and try again.');
					return false;
				}
			} else{
				alert("Please enter from email address!");
				return false;
			}
		if(body == '')
		{
			alert("Please enter some text in body!");
			return false;
		}
		if(to_email!='' && body!='' && from_email!='')
		{
			$('#sd_bt').hide();
			$('#processed_msg').html("REQUEST PROCESSING PLEASE WAIT....").show().css({'color':'red','font-weight':'bold'});
			$.ajax({
				type:"POST",
				url:site_url+"/site/SendMail",
				data:{from_email:from_email,to_email:to_email,from_name:from_name,subject:subject,body:body},
				success:function(result){
					if(result=='200')
					{
						$('#processed_msg').html("").hide();
						$('#sd_bt').show();
						$('input[type=text]').val('');
						$('.body').val('');
						$('#mail_box').hide();
						$('.share_alert').fadeIn();
						$('.share_alert').animate({top:160},300);
						//alert("Your message has been sent successfully!");
						setTimeout(function(){ location.reload(); }, 3000);
						
					}else{
						alert("An error occured! please try after sometime!");
					}
					$('#processed_msg').html("").hide();
					$('#sd_bt').show();
					$('input[type=text]').val('');
					$('.body').val('');
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					$('#mail_box').html('An error occured! please try after sometime. Status:'+textStatus+"Error: " + errorThrown).show();
					// window.location.reload();
				} 	
			});
		}
	});
</script>
<!-- pagination for shops -->
<script>
	start = 10;
	q ='<?php echo $search_input; ?>';
	latitude ='<?php echo $latitude; ?>';
	longitude ='<?php echo $longitude; ?>';
	$(document).ready(function(){
		$('#load_more_shops').on('click',function(){
			$.ajax({
					type:"POST",
					url:site_url+"/site/AjaxSolrShop",
					data:{start:start,q:q,latitude:latitude,longitude:longitude},
					success: function(result){
						if(result != ''){
							var container = document.createElement('div');
							container.id = 'result_wrap'; // temporarily  created 'div' to store ajax data(html)

							// Inject the div into the DOM
							document.body.appendChild(container);

							$('#result_wrap').html(result);
							var num = $('#result_wrap article').length; // find the no of records
							$('#result_wrap').remove(); // remove newly created 'div' from document
							
							$('#shop_search_results').append(result);
							if(num < 10){
								$('#load_more_shops').hide(); // to hide load more if data less than 10
							}	
							start += 10;
						}else{
							$('#load_more_shops').hide();
							$('.load_more_msg').html("NO MORE SHOPS TO DISPLAY....");
						}
						
						console.log(start);
						
					},
				});
			
		});
	});
</script>


