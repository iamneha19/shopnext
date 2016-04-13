<?php 
	$latitude  = "";
	$longitude = "";
	$current_location = "";
	$user_location_det  = ApplicationSessions::run()->read('user_location_det');
	if(!isset($user_location_det) || empty($user_location_det))	{
		$user_location_det = $this->getUserGeolocation();		
	}
	if($user_location_det['city']!='' && $user_location_det['state']!='') {
		$current_location = $user_location_det['city'].", ".$user_location_det['state'];
	}
	if($user_location_det['latitude']!='' && $user_location_det['longitude']!='') {
		$latitude = $user_location_det['latitude'];
		$longitude = $user_location_det['longitude'];
	}
	
?>
<?php 
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'fileupload',
	'action' => Yii::app()->createUrl('site/search'),  
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data','method'=>'GET'),
	));
?>
<div id="search_main">
	<div id="search">
		<div id="search_div">
			<div id="location_icon"></div>
			<input type="text" id="search_input" data-auto-loc="<?php echo $current_location;?>" name="searchgeoloc_input" placeholder="Select Location" autocomplete="off" value="<?php echo $current_location;?>">
			<input type="hidden" id="latitude" data-auto-lat="<?php echo $latitude;?>" name="latitude" value="<?php echo $latitude;?>">
			<input type="hidden" id="longitude" data-auto-lng="<?php echo $longitude;?>" name="longitude" value="<?php echo $longitude;?>">
			<input type="hidden" id="entity_id" name="entity_id">
			<input type="hidden" id="entity_type" name="entity_type">			
			<a href="#" id="set_lo">set location</a>
			<div id="search_list">	
				<span class="detect_current">Detect current location</span>
			</div>
		</div>
		<div id="search_product">
			<div id="search_icon"></div>
			<!--<input type="text" id="search_input2" name="search_input" placeholder="Shops, Super Markets..." autocomplete="off">-->
			<input type="text" id="search_input2" name="q" placeholder="Shops, Super Markets..." autocomplete="off">
			<input type="hidden" id="shop_id" name="shop_id" value="">
			<div id="shop_list">
			</div>
		</div>
		
		<input type="submit" id="search_btn" value="SEARCH">
		<div class="clr"></div>
	</div>
</div>
<?php $this->endWidget(); ?>
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
