$(document).ready(function(e) 
{
	var auto_location = $('#search_input').val();
	var shop_list     = $('#shop_list');
	var search_input  = $('#search_input');
	var latitude  	  = $('#latitude');
	var longitude 	  = $('#longitude');
	var search_input2 = $('#search_input2');
	var search_list   = $('#search_list');
	var search_list_item = $('#search_list .search_item');
	var entity_type 	= $('#entity_type');
	var entity_id = $('#entity_id');

	$('#set_lo').on('click focus',function(){	
		search_list.css('display','block');
	});	
	$('#set_lo').on('blur focusout',function(){	
		search_list.css('display','none');
	});	
	search_input2.on('click focus',function()	{
		shop_list.css('display','block');
	});
	search_input2.on('blur focusout',function(){	
		search_list.css('display','none');
	});	
	search_input2.on('keyup',function(e)
	{ 
		var term = $(this);
		$('#shop_id').val('');
		if(e.which !== 40 && e.which !== 38 && e.which !== 13){
			if(term.val()!='' && term.val().length>2)
			{
				
				$.ajax({
						type:"GET",
						url:site_url+"/site/solrShopAutosuggest",
						data:{term:term.val(),lat:latitude.val(),lng:longitude.val(),location:search_input.val(),entity_type:entity_type.val(),entity_id:entity_id.val()},		
						success: function(result){
							shop_list.html(result);
							shop_list.css('display','block');
							//$('#shop_list .search_item:first-child').css({'background':'#e84c3d','color':'#ffffff'});
							$('#shop_list .search_item').on('click',function(){
								$div = $(this);
								term.val($div.text());
								$('#shop_id').val($div.attr('data-id'));
								$('#shop_list').css('display','none');							
							});
						},
					});
			}else{			
				shop_list.css('display','none');
			}
		}	
		
	});
	$('html').click(function(e) {
		
		var target = $(e.target);

		if(!target.is('#search_input') && !target.is('#set_lo') && !target.is('.detect_current')) {
			search_list.css('display','none');
		}
		if(!target.is('#search_input2')) {
			shop_list.css('display','none');
		}
	});

	// $('#search_list').click(function(e)
	// {
	// 	var user_search = $("#search_input").val();
	// 	$("#search_input").attr('data-auto-loc',user_search);

	// 	$.ajax({
	// 				type:"GET",
	// 				url:site_url+"/site/writeSearchSession",
	// 				data:{term:user_search},		
	// 				success: function(result){
						
	// 				},
	// 			});
	// });
	
	search_input.on('click focus',function()	{
		search_list.css('display','block');
	});
	search_input.on('keyup',function(e)
	{ 
		var term = $(this);
		if(e.which !== 40 && e.which !== 38 && e.which !== 13){
			if(term.val()!='' && term.val().length>2)
			{
				
				if(auto_location!=term.val())
				{
					// latitude.val('');
					// longitude.val('');
					// entity_type.val('');
					// entity_id.val('');
					$.ajax({
						type:"GET",
						url:site_url+"/site/solrLocationAutosuggest",
						data:{term:term.val(),lat:latitude.val(),lng:longitude.val()},			
						success: function(result){
							search_list.html(result);
							search_list.css('display','block');
							$('#search_list .search_item').on('click',function() 
							{
								$div = $(this);
								term.val($div.text());
								$("#search_input").attr('data-auto-loc',$div.text());
								if($div.attr('data-lat')!='' && $div.attr('data-lng')!='')
								{
									latitude.val($div.attr('data-lat'));
									longitude.val($div.attr('data-lng'));

									$.ajax({
												type:"GET",
												url:site_url+"/site/writeSearchSession",
												data:{term:term.val(),lat:latitude.val(),lng:longitude.val()},		
												success: function(result){
												},
											});

								}else{
									reverseGeocoding($div.text());
								}
								if($div.attr('data-entity-type')!='' && $div.attr('data-entity-id')!='')
								{
									entity_type.val($div.attr('data-entity-type'));
									entity_id.val($div.attr('data-entity-id'));
								}
								search_list.css('display','none');
								document.getElementById('search_input2').focus();
							});
							$('#search_list .detect_current').on('click',function(){								
								getGeoLocation();
								search_list.css('display','none');
							});
						},
					});
					
				
				}
				
			}		
		}	
		
	});
	search_input.on('blur focusout',function(){
		if($(this).val()=='')
		{			
			search_input.val(search_input.attr('data-auto-loc'));
			latitude.val(latitude.attr('data-auto-lat'));
			longitude.val(longitude.attr('data-auto-lng'));
			search_list.css('display','none');			
		}	
	});
	$('#search_list .detect_current').on('click',function(){	
		entity_type.val('');
		entity_id.val('');
		getGeoLocation();
		var term = $('#search_input').val();
		var lat = $('#latitude').val();
		var lng = $('#longitude').val();

		$("#search_input").attr('data-auto-loc',$('#search_input').val());
		
		$.ajax({
					type:"GET",
					url:site_url+"/site/writeSearchSession",
					data:{term:term,lat:lat,lng:lng},		
					success: function(result){
						
						search_list.css('display','none');
					},
				});
	});
	
	/*******
	@ Amit
	Up/Down keys on location auto complete 
	*****/
	var liSelected ;
	$('#search_div').on('keydown','#search_input',function(e){
		if(e.which === 40){
			if(liSelected){
				liSelected.removeClass('selected');
				next = liSelected.next();
				if(next.length > 0){
					liSelected = next.addClass('selected');
				}else{
					liSelected = $('#search_div').find('.search_item').first().addClass('selected');
				}
			}else{
				liSelected = $('#search_div').find('.search_item').first().addClass('selected');
				
			}
		}else if(e.which === 38){
			if(liSelected){
				liSelected.removeClass('selected');
				next = liSelected.prev();
				if(next.length > 0){
					liSelected = next.addClass('selected');
				}else{
					liSelected = $('#search_div').find('.search_item').last().addClass('selected');
				}
			}else{
				liSelected = $('#search_div').find('.search_item').last().addClass('selected');
			}
		}
		if( (liSelected && e.which === 40) || (liSelected && e.which === 38)){
			if(liSelected.text() != ''){
				$('#search_input').val(liSelected.text());
			}	
			
		}	
		
	});
	
	$('#search_input').on('keypress',function(e){
		if(e.which === 13){
			$div = $('#search_list').find('.search_item.selected');
			var term = $(this);
			term.val($div.text());
			$("#search_input").attr('data-auto-loc',$div.text());
			if($div.attr('data-lat')!='' && $div.attr('data-lng')!='')
			{
				latitude.val($div.attr('data-lat'));
				longitude.val($div.attr('data-lng'));

				$.ajax({
							type:"GET",
							url:site_url+"/site/writeSearchSession",
							data:{term:term.val(),lat:latitude.val(),lng:longitude.val()},		
							success: function(result){
							},
						});

			}else{
				reverseGeocoding($div.text());
			}
			if($div.attr('data-entity-type')!='' && $div.attr('data-entity-id')!='')
			{
				entity_type.val($div.attr('data-entity-type'));
				entity_id.val($div.attr('data-entity-id'));
			}
			$('#search_list').css('display','none');
			$('#search_input2').focus();
		}	
		
	});
	/*** end Up/Down on location auto complete ***/
	
	/******* Up/Down keys on shop auto complete *****/
	var shopSelected ;
	$('#search_product').on('keydown','#search_input2',function(e){
		if(e.which === 40){
			if(shopSelected){
				shopSelected.removeClass('selected');
				next = shopSelected.next();
				if(next.length > 0){
					shopSelected = next.addClass('selected');
				}else{
					shopSelected = $('#search_product').find('.search_item').first().addClass('selected');
				}
			}else{
				shopSelected = $('#search_product').find('.search_item').first().addClass('selected');
				
			}
		}else if(e.which === 38){
			if(shopSelected){
				shopSelected.removeClass('selected');
				next = shopSelected.prev();
				if(next.length > 0){
					shopSelected = next.addClass('selected');
				}else{
					shopSelected = $('#search_product').find('.search_item').last().addClass('selected');
				}
			}else{
				shopSelected = $('#search_product').find('.search_item').last().addClass('selected');
			}
		}
		if( (shopSelected && e.which === 40) || (shopSelected && e.which === 38)){
			if(shopSelected.text() != ''){
				$('#search_input2').val(shopSelected.text());
			}	
			
		}	
		
	});
	/*** end Up/Down on shop auto complete ***/
});

	var geocoder;
	function getGeoLocation() 
	{
		from_cookies = getCookie('current_location_details');
		if(from_cookies!='')
		{
			var obj = jQuery.parseJSON(from_cookies);
			processLocation(obj.address,obj.lat,obj.lng);
		} else 
		{			
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(processResult,handleError);
			} else { 
				alert("Geolocation is not supported by this browser.");
			}
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
			
			if (status == google.maps.GeocoderStatus.OK) 
			{
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
				logSearches(address,latitude,longitude);
				current_location_details = '{"address":"'+address+'","lat":"'+latitude+'","lng":"'+longitude+'"}';
				createCookie('current_location_details',current_location_details,10);
				processLocation(address,latitude,longitude);
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
	function reverseGeocoding(address)
	{
		var geocoder = new google.maps.Geocoder();

		geocoder.geocode( { 'address': address}, function(results, status) {		
		
		  if (status == google.maps.GeocoderStatus.OK) 
		  {	
				latitude = results[0].geometry.location.lat();
				longitude = results[0].geometry.location.lng();
				document.getElementById('latitude').value = latitude;
				document.getElementById('longitude').value = longitude;				
		  } 
		}); 
	}
	var processLocation = function (address,lat,lng)
	{
		document.getElementById('search_input').value = address;
		document.getElementById('latitude').value = lat;
		document.getElementById('longitude').value = lng;	
		document.getElementById('search_input2').focus();
	}
	var logSearches = function (address,lat,lng)
	{
		$.ajax({
			type:"POST",
			url:site_url+"/site/logLocation",
			data:{address:address,lat:lat,lng:lng},		
			success: function(result){
				return true;
			},
		});
	}
	var createCookie = function(name, value, days) 
	{
			var expires;
			if (days) {
				var date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				expires = "; expires=" + date.toGMTString();
			}
			else {
				expires = "";
			}
			document.cookie = name + "=" + value + expires + "; path=/";
	}

	function getCookie(c_name) 
	{
		if (document.cookie.length > 0) {
			c_start = document.cookie.indexOf(c_name + "=");
			if (c_start != -1) {
				c_start = c_start + c_name.length + 1;
				c_end = document.cookie.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = document.cookie.length;
				}
				return unescape(document.cookie.substring(c_start, c_end));
			}
		}
		return "";
	}
