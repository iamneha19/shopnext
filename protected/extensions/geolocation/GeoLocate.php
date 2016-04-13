<?php 
/**Garima 
* @CLASS  : GeoLocate 
* PLEASE NOTE : refer class file details provided at the end of the file
**/

require_once 'vendor/autoload.php'; // to autoload GeoIp 
use GeoIp2\Database\Reader; // required for GeoIp db reading
use GeoIp2\WebService\Client; // required for GeoIp Webservices

class GeoLocate 
{
		
	var $ip = "";
	var $country_name = "";
	var $country_code = "";
	var $city_name    = "";
	var $region_name  = "";
	var $postal_code  = "";
	var $latitude     = "";
	var $longitude    = "";
	var $time_zone    = "";
	var $locality     = "";
	
	var $geoip_user_id    = "";
	var $geoip_lic_key    = "";
	
	var $ipinfodb_api_key = "";
	
	public function __construct()
	{
		$this->ip = $this->getUserIP();		
		$this->ipinfodb_api_key = "dabc0de1782697fb0f6a1561ad6ad39152d0a3fc15e9b5742a6936c59ff323af";		
		$this->geoip_user_id    = "97642";
		$this->geoip_lic_key    = "IFuXF8khSWsa";
		// below line --> for development mode 
		// if($this->ip=='::1' || $this->ip=='127.0.0.1')
		// {
			// $this->ip = "175.100.145.226";		
		// }
	}
	/*
	Garima
	* @geoLocate : to locate user using all methods according to their accuracy preferences
	*/
	public function geoLocate($method = null)
	{
		$data = array();
		if($method != null)
		{		
			$data = $this->$method();
		} else 
		{
			// call IpInfoDb to get location information		
			$data = $this->getFromIpInfoDb();	
			// call GeoipCity to get location information if above fails 
			if(!is_array($data) || empty($data))
			{
				$data = $this->getFromGeoipCity();		
			}		
			
			// call GeoipWebservices to get location information if above all fails 
			if(!is_array($data) || empty($data))
			{
				$data = $this->getFromGeoipWebservices();		
			}
			
			// returns on country details : call this only if above all fails 
			if(!is_array($data) || empty($data))
			{
				$data = $this->getFromGeoipCountry();		
			}	
		}
		if(is_array($data) & !empty($data))
		{
			foreach($data as $row=>$val)
			{
				$this->$row = $val;
			}
		}		
	}
	/*
	Garima
	* @getFromGeoipCity : to get location information from GeoLite2-City.mmdb database
	*/
	public function getFromGeoipCity()
	{	
		$user_info = array();
		try 
		{
			$reader = new Reader('mm-db/GeoLite2-City.mmdb'); 
			$record = $reader->city($this->ip);
			
			if($record->country->name!='' && $record->city->name!='' && $record->location->latitude!='' && $record->location->longitude!='')
			{
				$formated = $this->formatGeometry($record->location->latitude,$record->location->longitude);
				if($formated['latitude']!='' && $formated['longitude']!='')
				{
					$user_info = array(
							'ip' 		   => $this->ip ,
							'country_code' => $record->country->isoCode ,
							'country_name' => $record->country->name,
							'region_name'  => $record->mostSpecificSubdivision->name ,
							'city_name'    => $record->city->name,
							'postal_code'  => $record->postal->code ,
							'latitude' 	   => $formated['latitude'] ,
							'longitude'    => $formated['longitude'] ,
						);
				}
			}
			
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}		
		return $user_info;
	}
	
	/*
	Garima
	* @getFromGeoipCountry : to get location information from GeoLite2-Country.mmdb database
	*/
	public function getFromGeoipCountry()
	{
		$user_info = array();
		try 
		{
			$reader = new Reader('mm-db/GeoLite2-Country.mmdb');		
			//$record = $reader->country($this->ip);
			// if($record->country->name!='' $record->location->latitude!='' && $record->location->longitude!='')
			// {
				// $formated = $this->formatGeometry($record->location->latitude,$record->location->longitude);
				
				// $user_info = array(
							// 'ip' 		   => $this->ip ,
							// 'country_code' => $record->country->isoCode ,
							// 'country_name' => $record->country->name,
							// 'region_name'  => $record->mostSpecificSubdivision->name,
							// 'postal_code'  => $record->postal->code ,
							// 'latitude' 	   => $formated['latitude'] ,
							// 'longitude'    => $formated['longitude'] ,
						// );
			
			// }
			
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		return $user_info;
	}
	
	/*
	Garima
	* @getFromGeoipWebservices getting values from maxmind's geoIp webservices
	*/
	public function getFromGeoipWebservices()
	{
		$user_info = array();
		try 
		{
			$client = new Client($this->geoip_user_id, $this->geoip_lic_key);
			$record = $client->city($this->ip);

			if($record->country->name!='' && $record->city->name!='' && $record->location->latitude!='' && $record->location->longitude!='')
			{
				$formated = $this->formatGeometry($record->location->latitude,$record->location->longitude);
				if($formated['latitude']!='' && $formated['longitude']!='')
				{
					$user_info = array(
							'ip' 		   => $this->ip ,
							'country_code' => $record->country->isoCode ,
							'country_name' => $record->country->name,
							'region_name'  => $record->mostSpecificSubdivision->name ,
							'city_name'    => $record->city->name,
							'postal_code'  => $record->postal->code ,
							'latitude' 	   => $formated['latitude'] ,
							'longitude'    => $formated['longitude'] ,
						);
				}		
			}
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}		
		return $user_info;
	}
	/*
	Garima
	* @getFromIpInfoDb getting values from IpInfoDb's webservices
	*/
	public function getFromIpInfoDb()
	{		
		$user_info = array();
		try 
		{
			$API_KEY = $this->ipinfodb_api_key;
			// $url = "http://api.ipinfodb.com/v3/ip-city/?key=".$API_KEY."&ip=".$this->ip."&format=json";
			$url = "http://api.ipinfodb.com/v3/ip-city/?key=".$API_KEY."&format=json&ip".$this->ip;
			
			$header_resp = $this->get_http_response_code($url);
			
			if($header_resp != "404")
			{
				$resp_data = file_get_contents($url);
							
				if(!empty($resp_data))
				{
					$data = json_decode($resp_data , true);
					
					if($data['statusCode']=='OK' && strlen($data['countryName']) && strlen($data['cityName']) && strlen($data['latitude']) && strlen($data['longitude']))
					{
						$latitude  = $data['latitude'];
						$longitude = $data['longitude'];
						$formated = $this->formatGeometry($latitude,$longitude);
						if($formated['latitude']!='' && $formated['longitude']!='')
						{
							$user_info = array(
							'ip' 		   => $data['ipAddress'] ,
							'country_code' => $data['countryCode'] ,
							'country_name' => $data['countryName'] ,
							'region_name'  => $data['regionName'] ,
							'city_name'    => $data['cityName'] ,
							'postal_code'  => $data['zipCode'] ,
							'latitude' 	   => $formated['latitude'] ,
							'longitude'    => $formated['longitude'] ,
							'time_zone'    => $data['timeZone'] ,
							);					
						}					
					}
				}
			}
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		return $user_info;		
	}
	
	/*
	Garima
	* @getFromGoogleApi : to get location information from google apis
	*/
	public function getFromGoogleApi()
	{		
		
	}
	
	/*
	Garima
	* @formatGeometry : to format latitude and longitude to get search accuracy (if needed)
	* @PARAM : latitude, latitude
	* @RETURN : formated latitude, latitude
	*/
	private function formatGeometry($latitude,$longitude)
	{		
		if($latitude!='' && ($latitude>0 || $latitude<0))
		{
			$latitude = number_format($latitude,15);
		}else{
			$latitude = '';
		}
		if($longitude!='' && ($longitude>0 || $longitude<0))
		{
			$longitude = number_format($longitude,7);
		}else{
			$longitude = '';
		}
		return array('latitude'=>$latitude,'longitude'=>$longitude);
	}
	
	/*
	Garima
	* @get_http_response_code : to get http reponse code for eg: 200,400..etc
	* @PARAM : url 
	* @RETURN : return response code if valid url is passed and false in invalid 
	*/
	private function get_http_response_code($url) 
	{
		if($this->validate_url($url))
		{
			$headers = get_headers($url);
			$return =  substr($headers[0], 9, 3);
		}else
		{
			$return =  false;
		}
		return $return;
	}	
	
	/*
	Garima
	* @validate_url : to validate urls
	* @PARAM : Boolean values true -> valid, false -> invalid
	*/
	private function validate_url($url) 
	{
		if(!filter_var($url, FILTER_VALIDATE_URL))
		{
			return false;
		}
		else
		{
			return true;
		}
	}	
	
	/*
	Garima
	* @getUserIP : to get user's ip address
	*/
	public function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		
		if($ip=='::1' || $ip=='127.0.0.1' || $this->is_ip_private($ip)){
			$ip = $this->get_public_ip_address();
		}
		return $ip;
	}

	function get_public_ip_address()
	{
		$url="simplesniff.com/ip";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function is_ip_private($ip) 
	{
		$pri_addrs = array (
						  '10.0.0.0|10.255.255.255', // single class A network
						  '172.16.0.0|172.31.255.255', // 16 contiguous class B network
						  '192.168.0.0|192.168.255.255', // 256 contiguous class C network
						  '169.254.0.0|169.254.255.255', // Link-local address also refered to as Automatic Private IP Addressing
						  '127.0.0.0|127.255.255.255' // localhost
						 );

		$long_ip = ip2long ($ip);
		if ($long_ip != -1) {

			foreach ($pri_addrs AS $pri_addr) {
				list ($start, $end) = explode('|', $pri_addr);

				 // IF IS PRIVATE
				 if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) {
					 return true;
				 }
			}
		}

		return false;
	}

}
/**
*@CLASS  : GeoLocate 
* To get geo location information of site user/visitor from their ip address.
*
* @METHODS :
*		@geoLocate 				 : to locate user using all methods according to their accuracy preferences
*		@getFromGeoipCity 		 : to get location information from GeoLite2-City.mmdb database
*		@getFromGeoipCountry 	 : to get location information from GeoLite2-Country.mmdb database
*		@getFromGeoipWebservices : to get location information from maxmind's geoIp webservices
*		@getFromIpInfoDb 		 : to get location information from IpInfoDb's webservices
*		@getFromGoogleApi 		 : to get location information from google apis
*
* @ACCURACY - PREFERENCES :
*
*		@getFromIpInfoDb :
*							Requires registration to get API KEY
*							PLEASE NOTE : THIS IS FREE DB AVALIABLE, HENCE TRY TO GET LOCATION INFO FROM THIS METHOD FIRST
*							Currently Registered through OR To log / upgrade  use:
*								Username	: garima.singh@sts.in
*								Password	: sts*630
*							Required keys:
*								API_KEY 	: dabc0de1782697fb0f6a1561ad6ad39152d0a3fc15e9b5742a6936c59ff323af
*
*		@getFromGeoipCity :
*							Requires 	: GeoLite2-City.mmdb database
*							PLEASE NOTE : GeoLite2-City.mmdb is for trail/development  mode freely available. 
*										  Needs to be update manually every month(preferably after 13th of each).
*							Stored at 	: .....\protected\extensions\geolocation\mm-db\GeoLite2-City.mmdb
*
*		@getFromGeoipWebservices  :
*							 	Requires registration -> Currently using free trial based registration. 
* 							 	PLEASE NOTE 	: QUERY LIMITATION IS 1000/ TRIAL REGISTRATION, HENCE CALL THIS 
*												  FUNCTION ONLY WHEN DATA NOT FOUND IN OTHER METHODS.
* 							 	Currently Registered through OR To log / upgrade  use:
*										Username	: garima.singh@sts.in
*										Password	: wrj89rd
* 								Required keys:
*										User ID 	: 97642
*										License key : IFuXF8khSWsa
*
*		@getFromGeoipCountry :
*							Requires 	: GeoLite2-Country.mmdb database
*							PLEASE NOTE : GeoLite2-Country.mmdb is for trail/development  mode freely available. 
*										  Needs to be update manually every month(preferably after 13th of each).
*							Stored at 	: .....\protected\extensions\geolocation\mm-db\GeoLite2-Country.mmdb
*
*		@getFromGoogleApi : Currently not in use
*/
?>