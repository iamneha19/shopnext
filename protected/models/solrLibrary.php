<?php

class solrLibrary
{
	public $client;
	
	public function init()
	{
		$this->client = new SolrClient(Yii::app()->params['SOLR_CONFIG']);
	}
	
	public function indexData($data)
	{
		
		if(!empty($data))
		{
			$client = new SolrClient(Yii::app()->params['SOLR_CONFIG']);
			
			foreach($data as $key=>$val)
			{
				$doc = new SolrInputDocument();
				$doc->addField('id', $val['id']);
				$doc->addField('name', $val['name']);
				$doc->addField('description', $val['description']);
				$doc->addField('address', $val['address']);
				$doc->addField('category', $val['category']);
				
				if(!empty($val['location']))
				{
					$doc->addField('location', $val['location']);
				}
				$doc->addField('locality', $val['locality']);
				$doc->addField('city', $val['city']);
				$doc->addField('active_status', $val['active_status']);
				$doc->addField('status', $val['status']);
				
				if(!empty($val['product']))
				{
					foreach($val['product'] as $product)
					{
						$doc->addField('products', $product['product_id']);
					}
				}
				
				$updateResponse = $client->addDocument($doc);
			}
			$client->commit();
		}
	}
	
	public function addMultivalue($doc,$data)
	{
		foreach($data as $key=>$val)
		{
			if(!is_array($val[$key]))
			{
				$doc->addField($key, $val[$key]);
			}
		}
	}
	
	/*Amit
	*@getResult
	*@param : 
		$core shop or location
		$q solr normal query
		$fq solr filter query 
		$start offset for results 
	*@return : solr response
	*/
	public function getResult($core,$q,$fq,$fields,$start=0)
	{
		if($core == 'shop'){
			$client = new SolrClient(Yii::app()->params['SOLR_CONFIG']);
		}else{
			$client = new SolrClient(Yii::app()->params['SOLR_LOCATION_CONFIG']);
		}
		
		
		$query = new SolrQuery();
		
		$query->setQuery($q);
		
		
		$query->setStart($start);

		$query->setRows(10);
		
		// $query->addField('name');
		if(!empty($fields))
		{
			foreach($fields as $field)
			{
				$query->addField($field);
			}
		}
		
		if(!empty($fq))
		{
			$query->addFilterQuery($fq);
		}
		// $query->addFilterQuery('type:city');
		// $query->addFilterQuery('longitude:92.733333');
		 // $query->setHighlight(true);
		 // $query->addHighlightField('name');
		// $query->setHighlightSimplePre('<b>','name');
		// $query->setHighlightSimplePost('</b>','name');
		$query_response = $client->query($query);

		$response = $query_response->getResponse();
		// echo '<pre>';
		// print_r($response);
		// echo '</pre>';
		// die('exit');
		// return $response->response->docs;
		
		return $response->response->docs;
	}
	
	public function indexLocationData(){
		
		$client = new SolrClient(Yii::app()->params['SOLR_LOCATION_CONFIG']);
		
		$location_search = Yii::app()->db->createCommand()
				->select('id,geo_location as name,latitude,longitude, id as type')
				->from('location_search ')
				->where('status="1" and active_status="S"')
				->order('name')
				->getText();
				
		$locality = Yii::app()->db->createCommand()
			->select("l.locality_id as id,concat(l.locality,', ',c.city,', ',s.state) as name,l.latitude,l.longitude,'locality' as type")
			->from('locality l')
			->where('l.status="1" and l.active_status="S"')
			->join('city c','l.city_id=c.city_id')
			->join('state s','s.state_id=c.state_id')
			->order('name')
			->getText();

		$locations = Yii::app()->db->createCommand()
			->select("c.city_id as id,concat(c.city,', ',s.state) as name,c.latitude,c.longitude,'city' as type")
			->from('city c')
			->where('c.status="1" and c.active_status="S"')
			->join('state s','s.state_id=c.state_id')
			->union($location_search)
			->union($locality)
			->order('name')
			->queryAll();
				
		// echo '<pre>';
		// print_r($locations);
		// echo '</pre>';	
		
		foreach($locations as $location)
		{
			$doc = new SolrInputDocument();
			$doc->addField('name', $location['name']);
			if(!empty($location['latitude']) && !empty($location['longitude']))
			{
				$cordinates = $location['latitude'].','.$location['longitude'];
			}else{
				$cordinates = '0.00,0.00';
			}	
			
			$doc->addField('location', $cordinates);
			$doc->addField('latitude', $location['latitude']);
			$doc->addField('longitude', $location['longitude']);
			if(is_numeric($location['type'])){
				$type = 'search';
			}else{
				$type =$location['type'];
			}		
			$doc->addField('type', $type);
			
			$doc->addField('id', $type.'_'.$location['id']);
			
			$client->addDocument($doc);
			
			
		}
		
		$client->commit();
		
		// $doc = new SolrInputDocument();
		// $doc->addField('name', 'Aberden, Andaman and Nicobar Islands');
		// $doc->addField('location', '11.666667,92.733333');
		// $doc->addField('latitude', 11.666667);
		// $doc->addField('longitude', 92.733333);
		// $doc->addField('type', 'city');
		
		// $updateResponse = $client->addDocument($doc);
		// echo '<pre>';
		// print_r($updateResponse);
		// echo '</pre>';
		// $client->commit();
	}
}
?>