<?php
$current_location = "";
if($geodata['city']!='' && $geodata['state']!='') {
	$current_location = $geodata['city'].", ".$geodata['state'];
}
?>

<?php //$form=$this->beginWidget('CActiveForm', array(
	//'id'=>'deals_form',
	//'enableAjaxValidation'=>false,
//)); ?>
<!-- <div>	
	Discover latest deals & offers near 
	<input type="text" name="current_location" id="current_location" value="<?php echo $current_location;?>" class="input-xlarge">
	<input type="button" value="Find" class="btn btn-primary">	
	<input type="hidden" name="navigator_location_details" id="navigator_location_details">
	
</div>
<div>
	<small>
		Please <a href="#" id="an_detect_curr_loc" class="detect_location"> click here to detect your exact location </a> 
		to find latest deals & offers near you.
	</small>
</div> -->

<?php //$this->endWidget(); ?>
<h3>	
	<?php if($current_location!='') {?>
		Latest deals & offers near <?php echo $current_location;?> :
	<?php } else { print "Oops..... your current location could not be traced !!";}?>	
</h3>

<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">

	<?php if(!empty($model)) {?>
	<?php foreach($model as $obj) {?>
		
		<div class="accordion-group" >		
			<div class="accordion-heading"  id="heading<?php echo $obj->deal_id;?>">
				<h4 class="panel-title">					
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo $obj->title.' @'.$obj->shop->name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a data-toggle="collapse" href="#collapse<?php echo $obj->deal_id;?>" data-parent="#accordion"  aria-expanded="true" aria-controls="collapse<?php echo $obj->deal_id;?>">
						<small>Details</small>
					</a>						
				</h4>
			</div>
			
			<div id="collapse<?php echo $obj->deal_id;?>" class="accordion-body collapse">			
				<div class="accordion-inner" >					
					<?php 
						if(!empty($obj->amount))
						{
							$type = ($obj->type=="P")? "%" : "Rupees";
							echo "Offer amount : ".$obj->amount." ".$type." off";
						}
						
					?><br>
					<?php echo "Offer Details : <i>".$obj->desc;?></i><br>
					<?php echo "Shop details : ".Controller::formatedAddress($obj->shop->name,$obj->shop->address,$obj->shop->zip_code,$obj->shop->city->city,$obj->shop->locality->locality);?><br>
					<?php 
						if(!empty($obj->start_date) && !empty($obj->end_date))
						{
							if($obj->start_date==$obj->end_date)
							{
								$offer_valid ="*Offer valid only on : ".Controller::dateFromTimestamp($obj->end_date,'dS M Y').".";
							}else{
								$offer_valid ="*Offer valid only between : ".Controller::dateFromTimestamp($obj->start_date,'dS M Y')." to ".Controller::dateFromTimestamp($obj->end_date,'dS M Y').".";
							}
					?>
						<b><?php echo $offer_valid;?></b>
					<?php }?>
				</div>
			</div>			
		</div>
		
	<?php }}else{print "No deals and offers found !!";}?>

</div>
<div id="test"></div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
	$(document).ready(function(){
	
		$('.detect_location').click(function(){
			getGeoLocation();
		});
		
	});
	
	var geocoder;
	
	function getGeoLocation() 
	{
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(processResult,handleError);
		} else { 
			alert("Geolocation is not supported by this browser.");
		}
	}

	function processResult(position) 
	{		
		var latitude  = position.coords.latitude;
		var longitude = position.coords.longitude;	
		
		var latlng = new google.maps.LatLng(latitude, longitude);
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({ 'latLng': latlng }, function (results, status) {
			
			if (status !== google.maps.GeocoderStatus.OK) {
				alert('Geocoder failed due to: ' + status);
			}
			
			if (status == google.maps.GeocoderStatus.OK) {
				console.log(results);
				var address = "";
				var address_arr = (results[0].address_components);

				fcnt = address_arr.length-4;
				$.each(address_arr, function (i, obj)
				{	
					if (i>1 && i<fcnt) 
					{
						sp = (i<fcnt-1) ? ", ": ".";
						address = address+obj.long_name+sp;
					}						
				});
				
				// var navigator_location_details = '{"country":"'+country+'","city":"'+city+'","state":"'+state+'","postal_code":"'+postal_code+'","latitude":"'+latitude+'","longitude":"'+longitude+'","locality":"'+locality+'"}';
				document.getElementById('current_location').value = address;
				// document.getElementById('navigator_location_details').value = navigator_location_details;
				
			}
		});		
		
	}	
	
	function handleError(error) 
	{
		switch(error.code) {
			case error.PERMISSION_DENIED:
				msg = "Location share is being denied.\n We recommend you to allow location share to refine your search results !!"
				break;
			case error.POSITION_UNAVAILABLE:
				msg = "Location information is unavailable !!"
				break;
			case error.TIMEOUT:
				msg = "The request to get user location timed out!!"
				break;
			case error.UNKNOWN_ERROR:
				msg = "An unknown error occurred !!"
				break;
		}
		alert(msg);
	}	
	
</script>

