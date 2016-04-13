<?php 
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 
?>

<div id="wrapper">
	<div id="main_div">
	   <aside id="left_side">
			<?php foreach($banners as $banner){ 
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
				}else { ?>
					<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=code+image"/></div>	
				<?php 
				} 
			} ?>
		</aside>
    <aside id="center_div">
    <article class="products">
		<div class="prod_pic">
			 <div class="prof_title">Profile</div><div class="prof_edit"><a href="<?php echo Yii::app()->params['SERVER'] ?>user/myprofile">
			 <i class="fa fa-pencil-square-o fa-lg"></i></a></div>
			 <div class="profile_seperator"></div>
		 </div>
		 
		<div class="prof_img_cont">
			<div class="product_ic">
				<div class="prof_pic">
				<?php echo  $this->getUserImage($user,150,150); ?>
				
				<a href="javascript:void(0);" class='edit-image' style='margin: 0 auto;display: table;'>
					<i class="fa fa-pencil-square fa-lg" title='Change Image'></i>
				</a>	
				</div>											
			</div>
		</div>
                     
		<div class="profile_sidecont">
			<div class="profile_details">
			  <span class="profile_title">Full Name : </span>
			  <span class="profile_nm"><?php echo $user->name; ?></span>
			</div>
			<div class="profile_details">
			  <span class="profile_title">Email : </span>
			  <span class="profile_nm"><?php echo $user->email; ?></span>
			</div>	
			<div class="profile_details">
			   <span class="profile_title">Gender : </span>
			   <?php
					if(!empty($user->gender)){
			   ?>
					<span class="profile_nm"><?php echo ($user->gender == 'M')  ? 'Male' : 'Female'; ?></span>
				<?php }else{ ?>
					<span class="profile_nm">Not Set</span>
				<?php } ?>
			   
			</div>	
			<div class="profile_details">
			   <span class="profile_title">Date Of Birth : </span>
			   <?php if(!empty($user->dob)){ ?>
				 <span class="profile_nm"><?php echo date('d/m/Y',$user->dob); ?></span>
			   <?php }else {?>
					<span class="profile_nm">Not Set</span>
			   <?php } ?>
				
			</div>	
			<div class="profile_details">
				<span class="profile_title">Mobile No : </span>
				<?php if(!empty($user->contact_no)) { ?>
					<span class="profile_nm"><?php echo $user->contact_no; ?></span>
				<?php }else { ?>
					<span class="profile_nm">Not Set</span>
				<?php } ?>
			</div>	
		</div>
    
		<div class="profile_downcont">
		 <div class="profile_details">
			<span class="profile_title">Address : </span>
			<?php if(!empty($user->address)){ ?>
				<span class="profile_nm"><?php echo $user->address; ?></span>
			 <?php }else { ?>
				<span class="profile_nm">Not Set</span>
			<?php } ?>
		</div>	
		
		<div class="profile_details">
			<span class="profile_title">Locality : </span>
			 <span class="profile_nm"><?php echo $locality = (!empty($user->locality_id))?$user->locality->locality:'Not Set'; ?></span>
		</div>
		
		<div class="profile_details">
			<span class="profile_title">City : </span>
			 <span class="profile_nm"><?php echo $city = (!empty($user->city_id))?$user->city->city:'Not Set'; ?></span>
		</div>	
		
		 <div class="profile_details">
			<span class="profile_title">State : </span>
			 <span class="profile_nm"><?php echo $state = (!empty($user->state_id))?$user->state->state:'Not Set'; ?></span>
		</div>
		
		</div>
	</article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	</div>
</div>

<!--************* alert div ************-->
<div class="alert_box image_form">
	<div class="alert_title">Change Image<div class="alert_close"></div></div>
	<article style='padding:2.5%'>
		<div class="form upload-image-form">
		<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'edit-image-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>
		  <div class="row">
			<?php echo $form->labelEx($user,'profile_pic'); ?>
			<?php echo $form->fileField($user,'profile_pic'); ?>
			
		  </div>
		  <div class="row buttons save_btn">
		   <input type='button' class='upload-image' value='upload' />
		  </div>
		<?php $this->endWidget(); ?>
	</div>
	</article>
	
 </div>
<!--************* alert div ************--> 

<script>
$('document').ready(function(){
    $('.edit-image').on('click',function(){
		$( "#User_profile_pic" ).next().remove();
		$('#edit-image-form')[0].reset();
		$('#overlay').fadeIn(300);
		$('.image_form').fadeIn();
		$('.image_form').animate({top:160},300);
	});  

	$( "#edit-image-form" ).validate({
	  rules: {
		'User[profile_pic]': {
		  required: true
		}
	  }
	});	
     
	$('.upload-image').on('click',function(){
		var formData = new FormData($("#edit-image-form")[0]);
		
		if($("#edit-image-form").valid()){
			var file = $('#User_profile_pic').val();
			var exts = ['jpg','jpeg','png','gif'];
			var file_size = $('#User_profile_pic')[0].files[0].size;
			
			var get_ext = file.split('.');
			// reverse name to check extension
			get_ext = get_ext.reverse();
			// check file type is valid as given in 'exts' array
			if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
			 
				if(file_size>2097152 ) {
					$( "#User_profile_pic" ).after( '<label id="User_profile_pic-error" class="error" for="User_profile_pic" style="">File size is greater than 2MB.</label>' );
				}else{
					$.ajax({
						url: "changeimage", 
						dataType:"json",
						type: "POST",             
						data: formData, 
						contentType: false,       
						cache: false,            
						processData:false,        
						success: function(result)   
						{
							if(result.success){
								$('.prof_pic img').attr('src',result.img);
								$('#overlay').fadeOut();
								$('.alert_box').fadeOut();
								$('.alert_box').animate({top:-500},300);
								location.reload();
							}else{
								$( "#User_profile_pic" ).after( '<label id="User_profile_pic-error" class="error" for="User_profile_pic" style="">Something went wrong. Please try again.</label>' );
							}	
						}
					});
				}
			} else {
			  $( "#User_profile_pic" ).after( '<label id="User_profile_pic-error" class="error" for="User_profile_pic" style="">Invalid File.</label>' );
			}
			
			
		}	
		
	});  

});
</script>	


