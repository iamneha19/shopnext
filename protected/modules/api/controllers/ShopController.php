<?php

class ShopController extends ApiController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the "acces"s contro)l rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array();
	}
	
	/**
	 * List all shops.
	 * @param 
	 */
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			if(!empty($_REQUEST['category_id'])){
			   $criteria->compare('category_id',$_REQUEST['category_id']); 
			}
			if(!empty($_REQUEST['locality_id'])){
			   $criteria->compare('locality_id',$_REQUEST['locality_id']); 
			}
			if(!empty($_REQUEST['state_id'])){
			   $criteria->compare('state_id',$_REQUEST['state_id']); 
			}
			if(!empty($_REQUEST['city_id'])){
			   $criteria->compare('city_id',$_REQUEST['city_id']); 
			}
			if(!empty($_REQUEST['name'])){
			   $criteria->compare('name',$_REQUEST['name'],true); 
			}
			if(!empty($_REQUEST['sort'])){
			   $criteria->order = $_REQUEST['sort']; 
			}
		   
			$criteria->AddCondition('active_status="S" and status="1"');
		    $shops = Shop::model()->findAll($criteria);
			if(!empty($shops)){
				$data = array();
				
				foreach($shops as $key=>$shop){ 

					$data[$key] = $shop->attributes;
					$data[$key]['category'] = $shop->category->category;
					$data[$key]['locality'] = $shop->locality->locality;
					$data[$key]['city'] = $shop->city->city;
					$data[$key]['state'] = $shop->state->state;
					if(!empty($shop->shop_image_id)){
						$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/shop/'.$shop->shopImage->image;
					}else{
						$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/shop/default.png';
					}
					$data[$key]['added_on']	 =  $this->dateConvert($shop->added_on);
					$data[$key]['updated_on']=  $this->dateConvert($shop->updated_on);
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
				
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			} 
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
        
        /**
	 * View shop.
	 * @param 
	 */
	public function actionView()
	{
		$resp_code = $this->validateRequest();

		if($resp_code=='200')
		{
			$shop_id = $_REQUEST['shop_id'];
			$shop = Shop::model()->find(array('condition'=>'active_status="S" and status="1" and shop_id ='.$shop_id)); 
			if(!empty($shop)){
				$data= $shop->attributes;
				$data['category'] = $shop->category->category;
				$data['locality'] = $shop->locality->locality;
				$data['city'] = $shop->city->city;
				$data['state'] = $shop->state->state;
				if(!empty($shop->shop_image_id)){
					$data['image'] = Yii::app()->params['SERVER'].'upload/shop/'.$shop->shopImage->image;
				}else{
					$data['image'] = Yii::app()->params['SERVER'].'upload/shop/default.png';
				}	
				$data['added_on']	 =  $this->dateConvert($shop->added_on);
				$data['updated_on']=  $this->dateConvert($shop->updated_on);
				
				$resp = array('code'=>$resp_code,'data'=>$data);
			}
			else if(Shop::model()->findAll(array('condition'=>'status="0" and shop_id ='.$shop_id)))
			{
				//if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
			}else{
				 $resp_code = $this->status_code['NOT_FOUND'];
				 $resp = array('code'=>$resp_code);
			}
        }
		else
		{
			 $resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
        
        /**
	 * Create New Shop.
	 * @param 
	 */
	public function actionCreate()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$user_id = $_REQUEST['user_id'];			
			$shop_count = Shop::model()->count(array('condition'=>'status = "1" and category_id="'.$_REQUEST['category_id'].'" and locality_id="'.$_REQUEST['locality_id'].'" and city_id="'.$_REQUEST['city_id'].'" and  name="'.$_REQUEST['name'].'"'));
			
			if($shop_count==0)
			{
				$model=new Shop;
				//Mandatory fields
				$model->name=$_REQUEST['name'];
				$model->user_id=$_REQUEST['user_id'];
				$model->category_id=$_REQUEST['category_id']; 
				$model->description=$_REQUEST['description'];
				$model->address=$_REQUEST['address'];
				$model->city_id=$_REQUEST['city_id'];
				$model->state_id=$_REQUEST['state_id'];
				$model->locality_id=$_REQUEST['locality_id'];
				$model->zip_code=$_REQUEST['zip_code'];
				
				//Optional fields
				if(!empty($_REQUEST['contact_no']))
				{
					$model->contact_no=$_REQUEST['contact_no'];
				}
				
				if(!empty($_REQUEST['latitude']) && !empty($_REQUEST['longitude']))
				{
					 $model->latitude=$_REQUEST['latitude'];
					 $model->longitude=$_REQUEST['longitude'];
				}
				
				$model->added_on = time();
				$model->updated_on = time();
				if ($model->validate()) 
				{
					if($model->save()){
						
						if(!empty($_REQUEST['pic']))
						{
							$path = 'shop';
							$pic = $this->uploadPic($model->shop_id,$_REQUEST['pic'],$path);
						}
						
						if(!empty($pic))
						{
							$model_shop_img=new ShopImage;
							$model_shop_img->image = $pic;
							$model_shop_img->shop_id = $model->shop_id;
							$model_shop_img->added_on = time();
							$model_shop_img->updated_on = time();
							
							if($model_shop_img->save()){
								$model->updateByPk($model->shop_id,array('shop_image_id'=>$model_shop_img->shop_image_id));
							}	
						}
						
						// If saved successfully.
						$resp = array('code'=>$resp_code);
				   }else{
						// If saving process fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code); 
					}
				}else{
					// If validation fails. 
					$resp_code = $this->status_code['BAD_REQUEST'];
					$resp = array('code'=>$resp_code); 
				}
			}
			else
			{
				// If shop exist in same city,locality and category. 
				$resp_code = $this->status_code['UPDATE_REQUIRED'];
				$resp = array('code'=>$resp_code); 
			}	
			
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
        
        /**
	 * Update Shop.
	 * @param 
	 */
	public function actionUpdate()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$model = Shop::model()->find(array('condition'=>'active_status="S" and status="1" and shop_id='.$_REQUEST['shop_id']));
			if(!empty($model)){
				if($model->user_id == $_REQUEST['user_id']){
					//Mandatory fields
					$model->name=$_REQUEST['name'];
					$model->category_id=$_REQUEST['category_id']; 
					$model->description=$_REQUEST['description'];
					$model->address=$_REQUEST['address'];
					$model->city_id=$_REQUEST['city_id'];
					$model->state_id=$_REQUEST['state_id'];
					$model->locality_id=$_REQUEST['locality_id'];
					$model->zip_code=$_REQUEST['zip_code'];

					//Optional fields
					if(!empty($_REQUEST['contact_no']))
					{
						$model->contact_no=$_REQUEST['contact_no'];
					}
					if(!empty($_REQUEST['latitude']) && !empty($_REQUEST['longitude']))
					{
						$model->latitude=$_REQUEST['latitude'];
						$model->longitude=$_REQUEST['longitude'];
					}
					$model->updated_on = time();
					if ($model->validate()) {
						if($model->save()){
							if(!empty($_REQUEST['pic']))
							{
								$path = 'shop';
								$old_pic = '';
								if(!empty($model->shop_image_id))
								{
									$old_pic = $model->shopImage->image;
								}
								
								$pic = $this->uploadPic($model->shop_id,$_REQUEST['pic'],$path,$old_pic);
							}
							
							if(!empty($pic))
							{
								$model_shop_img=ShopImage::model()->findByPk($model->shop_image_id);
								if(!empty($model_shop_img)){
									$model_shop_img->image = $pic;
									$model_shop_img->updated_on = time();
									$model_shop_img->save();
								}else{
									$model_shop_img=new ShopImage;
									$model_shop_img->image = $pic;
									$model_shop_img->shop_id = $model->shop_id;
									$model_shop_img->added_on = time();
									$model_shop_img->updated_on = time();
									
									if($model_shop_img->save()){
										$model->updateByPk($model->shop_id,array('shop_image_id'=>$model_shop_img->shop_image_id));
									}	
								}	
									
							}
							//If saved successfully.
							$resp = array('code'=>$resp_code);
						}else{
							//If saving process fails. 
							$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
							$resp = array('code'=>$resp_code); 
						}
					}else
					{
						//If validation fails. 
						$resp_code = $this->status_code['BAD_REQUEST'];
						$resp = array('code'=>$resp_code); 
					}
				}else
				{
					//If user_id didn't match with saved user_id. 
					$resp_code = $this->status_code['FORBIDDEN'];
					$resp = array('code'=>$resp_code); 
				}
			}else{
					// If shop_id doesn't not exist.
					$resp_code = $this->status_code['NOT_FOUND'];
					$resp = array('code'=>$resp_code);
			}
			
		}else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
        
        /**
	 * Delete shop.
	 * @param 
	 */
	public function actionDelete()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$shop_id = $_REQUEST['shop_id'];
			$model = Shop::model()->find(array('condition'=>'active_status= "S" and status="1" and shop_id='.$shop_id));
			if(!empty($model))
			{
				if($model->user_id == $_REQUEST['user_id']){
					$model->status="0";
					if($model->save(false))
					{
						$resp = array('code'=>$resp_code);
					}else{
						//If Saving process fails.
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code); 
					}
				}else
				{
					//If user_id didn't match with saved user_id. 
					$resp_code = $this->status_code['FORBIDDEN'];
					$resp = array('code'=>$resp_code); 
				}	
				
			}else{
					//if shop_id doesn't exist.
					$resp_code = $this->status_code['NOT_FOUND'];
					$resp = array('code'=>$resp_code); 
			}
        }else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	/**
	 * Get shop categories.
	 * @param 
	 */
	public function actionCategory()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$categories = Category::model()->findAll(array('select'=>'category_id,category','condition'=>'active_status="S" and status=1','order'=>'category')); 
			if(!empty($categories)){
				
				foreach($categories as $key=>$category)
				{
					$data[$key]['category_id'] = $category->category_id;
					$data[$key]['category'] = $category->category;
				}
				
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else{
				 $resp_code = $this->status_code['NOT_FOUND'];
				 $resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	/**
	 * Get shop autosuggest.
	 * @param 
	 */
	public function actionAutosuggest()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$term = $_REQUEST['term'];
			$shops = Shop::model()->findAll(array('select'=>'shop_id,name','condition'=>'active_status="S" and status=1 and name like "%'.$term.'%"')); 
			if(!empty($shops)){
				
				foreach($shops as $key=>$shop)
				{
					$data[$key]['shop_id'] = $shop->shop_id;
					$data[$key]['name'] = $shop->name;
				}
				
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else{
				 $resp_code = $this->status_code['NOT_FOUND'];
				 $resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	public function actionStatistic()
	{			
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$shop_id = $_REQUEST['shop_id'];		
			$prev_date_stat = 0;
			$prev_week_stat = 0;
			$prev_month_stat = 0;
			
			$model = new ShopVisitStatistics;	
			
			$prev_date = date('Y-m-d', strtotime(' -1 day'));
			$prev_month = date('m', strtotime(' -1 month'));		
			$prev_month_year = date('Y', strtotime(' -1 month'));
			
			$prev_week_date = date('Y-m-d', strtotime(' -1 week'));
			$prev_week_month = date('m', strtotime(' -1 week'));
			$prev_week_year = date('Y', strtotime(' -1 week'));		
			$prev_week_num  =  ceil(substr($prev_week_date, -2) / 7); 
			$prev_week  =  'week'.$prev_week_num; 
			
			$dmodel = $model->find(array('condition'=>'shop_id="'.$shop_id.'" and count_type="daily" and count_date="'.$prev_date.'" '));
			$wmodel = $model->find(array('condition'=>'shop_id="'.$shop_id.'" and count_type="weekly" and month_week="'.$prev_week.'" and month="'.$prev_week_month.'" and year="'.$prev_week_year.'"'));
			$mmodel = $model->find(array('condition'=>'shop_id="'.$shop_id.'" and count_type="monthly" and month="'.$prev_month.'" and year="'.$prev_month_year.'"'));
							
			if(isset($dmodel->total_count)){
				$prev_date_stat = $dmodel->total_count;
			}
			if(isset($wmodel->total_count)){
				$prev_week_stat = $wmodel->total_count;
			}
			if(isset($mmodel->total_count)){
				$prev_month_stat = $mmodel->total_count;
			}		
			
			$statistics = array(
							'last_day' => $prev_date_stat,
							'last_week' => $prev_week_stat,
							'last_month' => $prev_month_stat,
						);
			$resp = array('code'=>$resp_code,'data'=>$statistics);
			
		}else
		{		
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	public function actionCreateStatistic()
	{			
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$curr_week_of_the_month  =  ceil(substr($_REQUEST['date'], -2) / 7); 
			$curr_week_of_the_month  = 'week'.$curr_week_of_the_month;			
			$curr_month_number = date('m',strtotime($_REQUEST['date']));				
			$curr_year = date('Y',strtotime($_REQUEST['date']));
			$curr_date = date('Y-m-d');			
			$curr_timestamp = date('Y-m-d H:i:s');
			
			$model = new ShopVisitDetails();
			$model->shop_id   	  = $_REQUEST['shop_id'];
			$model->user_id   	  = $_REQUEST['user_id'];
			$model->datetime  	  = $curr_timestamp;
			$model->latitude  	  = $_REQUEST['latitude'];
			$model->longitude 	  = $_REQUEST['longitude'];
			$model->location  	  = $_REQUEST['location'];
			$model->other_details = $_REQUEST['other_details'];
				
			if($model->save())
			{
				$update_daily = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$_REQUEST['shop_id'].'" AND count_type="daily" AND count_date="'.$curr_date.'" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				if($update_daily<1)
				{
					$stat_model_d = new ShopVisitStatistics;
					$stat_model_d->shop_id = $_REQUEST['shop_id'];
					$stat_model_d->total_count = 1;
					$stat_model_d->count_type = 'daily';
					$stat_model_d->count_date = $curr_date;
					$stat_model_d->month_week = $curr_week_of_the_month;
					$stat_model_d->month = $curr_month_number;
					$stat_model_d->year = $curr_year;
					$stat_model_d->last_updated_on = $curr_timestamp;
					$stat_model_d->save();					
				}
				
				$update_weekly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$_REQUEST['shop_id'].'" AND count_type="weekly" AND month_week="'.$curr_week_of_the_month.'" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				
				if($update_weekly<1)
				{
					$stat_model_w = new ShopVisitStatistics;
					$stat_model_w->shop_id = $_REQUEST['shop_id'];
					$stat_model_w->total_count = 1;
					$stat_model_w->count_type = 'weekly';					
					$stat_model_w->month_week = $curr_week_of_the_month;
					$stat_model_w->month = $curr_month_number;
					$stat_model_w->year = $curr_year;
					$stat_model_w->last_updated_on = $curr_timestamp;
					$stat_model_w->save();					
				}
				
				$update_monthly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$_REQUEST['shop_id'].'" AND count_type="monthly" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				if($update_monthly<1)
				{
					$stat_model_m = new ShopVisitStatistics;
					$stat_model_m->shop_id = $_REQUEST['shop_id'];
					$stat_model_m->total_count = 1;
					$stat_model_m->count_type = 'monthly';
					$stat_model_m->month = $curr_month_number;
					$stat_model_m->year = $curr_year;
					$stat_model_m->last_updated_on = $curr_timestamp;
					$stat_model_m->save();					
				}
				
				$update_yearly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$_REQUEST['shop_id'].'" AND count_type="yearly" AND year="'.$curr_year.'"' )->execute();
				if($update_yearly<1)
				{
					$stat_model_y = new ShopVisitStatistics;
					$stat_model_y->shop_id = $_REQUEST['shop_id'];
					$stat_model_y->total_count = 1;
					$stat_model_y->count_type = 'yearly';
					$stat_model_y->year = $curr_year;
					$stat_model_y->last_updated_on = $curr_timestamp;
					$stat_model_y->save();					
				}
			}
			
			$resp = array('code'=>$resp_code);
		}
		else
		{		
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{

		$resp_code = $this->validateRequest();
		$this->apiResponse(array('code'=>$resp_code),$this->type,'E');
		$this->writeLog($resp_code,$this->action);
	}
	
	/**
		generate access key for testing
	 */
	
	public function actionTest()
	{
		$key = $this->api_key[$this->device];
                // 96b68e304b10d95710e88c5726f24587273933d7 - list
                // d92f821a0044449a63a54957869ccc523bfe9f39 - create
                // ea3bc274c67bad436ecaca73d8fa8f5b24fe210e - view
                // 18d460b675fe4787c7d49498e5d1897041516e14 - update
                // 94c1b397da9fcdd239ffd9db7e5bbd920208b376 - delete
				// f624a98d57e479782a8a9855a54d21d7a2ca74e3 - apicall
				// 86d70c6b2c3f7dcafa803249c346bfae4e3fed4d - forgotPassword
				// d92095efa4b0d3bf077416ff5734ec1c013f4267 - login
				// b8ee415f7dbad803ecbce8c77c78b3385d9b4e2d - category
				// 58344663c99ebca45089a1a484b8635237a1512f - changePassword
				// a9311ee11a3981e4f0e88853e5400db4cdc7772d - autosuggest
			
		$encrypted_key = sha1('user'.'list'.$key);

		echo $encrypted_key;
		
	}
	
	public function actionUpload()
	{
		$profile_pic = $this->uploadPic(1,$byte);
		echo $profile_pic;
	}	
        
	/**
	 * Function to handle a api call for testing.
	 */
	public function actionApiCall()
	{   
		$host = 'http://localhost';
		$url = $host.'/shopnext/api/deal/create';
		
		 // For shop
		// LIST
		// $data = 'access_key=96b68e304b10d95710e88c5726f24587273933d7&type=json';

		// CREATE	
		// $data = 'access_key=d92f821a0044449a63a54957869ccc523bfe9f39&name=Champion 1&category_id=6&address=Tanvi super market Acropolis&city_id=4&state_id=3&zip_code=400059&locality_id=2&user_id=2&latitude=19.2246738&longitude=73.1445492&contact_no=9087654321&type=xml&pic='.$byte;
		$data = 'access_key=74531bafa2ac60b9ab227b4a7040f05ca7573515&shop_id=5&title=Flat 20% off&desc=On ocon all products&code=&start_date=05/10/15&end_date=08/15/15&amount=20&deal_type=P&is_hot_deal=No&deal_image='.$byte.'&type=json';
		// $data = 'access_key=28dbd49a6d13c124d4d2353e0b14aa6ffd99a58d&deal_id=12&shop_id=5&title=Flat 10120% off&desc=On occasion of Diwali flat 20% off on all products&code=&start_date=02/10/15&end_date=02/15/15&amount=20&deal_type=P&deal_image='.$byte.'&type=json';
		// $data = 'access_key=18d460b675fe4787c7d49498e5d1897041516e14&name=Champion 3&category_id=6&address=Tanvi super market Acropolis&city_id=4&state_id=3&zip_code=400059&locality_id=2&user_id=2&shop_id=11&type=xml&pic='.$byte;
		// $data = 'access_key=18d460b675fe4787c7d49498e5d1897041516e14&type=xml&user_id=1&name=garima%20singh&email=garima@sts.in&gender=F&dob=2/12/2015&contact_no=45789652144&address=mmmuungj&locality_id=1&city_id=3&state_id=2&country_id=3&send_newsletter=Y&profile_pic='.$byte;
		
		// For review
		//$data ='create?access_key=d92f821a0044449a63a54957869ccc523bfe9f39&shop_id=11&user_id=2&comment=Reply%20from%20api&review_type=R&parent_id=34&type=xml';
		// $url .= $data;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		echo $output;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Shop $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='shop-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
}