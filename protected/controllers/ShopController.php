<?php

class ShopController extends Controller
{       
        
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionGetShops()
	{
		$geodata = $this->getUserGeolocation();				
		$model   = $this->actionGetNearestShops($geodata);		

		$this->render('shop_list',array(
			'geodata'=>$geodata,
			'model'=>$model,
		));
	}
	
	public function actionShopdetails()
	{
		$state = $_GET['state_id'];
		$city = $_GET['city_id'];
		$locality = $_GET['locality_id'];
		
		$state = State::model()->find(array('condition'=>'state="'.$state.'"'));
		if(!empty($state))
		{
			$city = City::model()->find(array('condition'=>'state_id='.$state->state_id.' and city="'.$city.'"'));
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if(!empty($city))
		{
			$locality = Locality::model()->find(array('condition'=>'city_id='.$city->city_id.' and locality="'.$locality.'"'));
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if(!empty($state) && !empty($city) && !empty($locality))
		{
			if(!empty($_GET['name']))
			{
				$shop_data = Shop::model()->find(array('condition'=>'name="'.$_GET['name'].'" and state_id='.$state->state_id.' and city_id='.$city->city_id.' and locality_id='.$locality->locality_id.' and status="1" and active_status="S"'));
				if(!empty($shop_data))
				{
					$id = $shop_data->shop_id;
				}else{
					throw new CHttpException(404,'The requested page does not exist.');
				}
			}else{
				if(!empty($id))
				{
					$id = $_GET['shop_id'];
					$shop_data = Shop::model()->find(array('condition'=>'status = "1" and active_status="S" and shop_id='.$id));
				}else{
					throw new CHttpException(404,'The requested page does not exist.');
				}
			}
			if(isset($id) && !empty($id))
			{			
				$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
				
				$user_rating = 0; 
				if($this->user_id)	{
					$rating = Rating::model()->findByAttributes(array('user_id'=>$this->user_id,'shop_id'=>$shop_data->shop_id,'active_status'=>'S','status'=>"1"));
					if($rating)
					$user_rating = $rating->rating;
				}
						
				$criteria = new CDbCriteria();
				$criteria->select = "c.comment_id,c.comment,c.added_on,u.name as user_name,r.rating as rate,c.user_id";
				$criteria->alias ='c';
				$criteria->join = "LEFT OUTER JOIN user u ON u.user_id=c.user_id";
				$criteria->join .= " LEFT OUTER JOIN rating r ON u.user_id=r.user_id AND r.shop_id=".$id." AND r.status='1'" ;
				$criteria->condition = "c.status=1 AND c.active_status='S' AND c.parent_id IS NULL AND c.type='P' AND c.shop_id=".$id;	
				$criteria->order = 'c.comment_id desc';
				$criteria->limit = '5';
				$comments = Comment::model()->findAll($criteria);
				$this->actionLogShopVisit($id);//to log shop visit
				
				$this->dataImages = $shop_data;
				
				$this->render('shop_details',array(
					'model'=>$shop_data,
					'comments'=>$comments,'limit'=>'5','offset'=>'0',
					'user_rating'=>$user_rating, // Rating to shop by loggedin user.
					'banners'=>$banners,
				));
			}
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
	}

	/* 
	Neha
	*This is the action to handle ajax request for reviews pagination.
	*/
	public function actionReviewList()
	{
		$return = "";
        $pagination = $_POST['pagination'];

        if(!empty($_POST['shopId']))
        {
        	$condition = "shop_id=".$_POST['shopId'];
        }

        if(!empty($_POST['dealId']))
        {
        	$condition = "deal_id=".$_POST['dealId'];
        }

        if(!empty($_POST['productId']))
        {
        	$condition = "product_id=".$_POST['productId'];
        }

        $criteria = new CDbCriteria();
        $criteria->select = "c.comment_id,c.comment,c.added_on,u.name as user_name,c.user_id";
        $criteria->alias ='c';
        $criteria->join = "LEFT OUTER JOIN user u ON u.user_id=c.user_id";
        $criteria->condition = "c.status=1 AND c.active_status='S' AND c.parent_id IS NULL AND c.type='P' AND ".$condition;// ." AND c.deal_id=".$deal_id." AND c.product_id=".$product_id;    
        $criteria->order = 'c.comment_id desc';
        $criteria->limit = '5';
        $criteria->offset = $pagination;
        $comments = Comment::model()->findAll($criteria);
        if(count($comments>0))
        {
            
            foreach($comments as $comment)
            {
                            
                $return .= '<div class="review_cont">
                            <div class="sender_img">
                                '.$this->getUserImage($comment->user,'64','64').
                                $comment->user_name.
                            '</div>';
                            $this->widget('ext.timeago.JTimeAgo', array(
                                'selector' => ' .timeago',
                            ));
                            $return.='
                            <div class="time">
                            <abbr class="timeago" title="'.date('Y-m-d h:i:s a',$comment->added_on).'"></abbr>
                        </div>
                            <div class="status">'.$comment->comment.'</div>
                        </div>
                        <div class="post_separator"></div>';
            }
        }
        echo json_encode(array('data'=>$return));
		
	}
	
	public function actionCategory($category)
	{
		$category_data = Category::model()->find(array('condition'=>'status="1" and active_status="S" and category="'.$_GET['category'].'"'));
		if(!empty($category_data))
		{
			$category_id = $category_data->category_id;
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));	
		$search_criteria = ApplicationSessions::run()->read('search_criteria');	

		$post['category_id'] = $category_id;			
		$post['category_name'] = $category;	
		if(isset($search_criteria) && is_array($search_criteria))	{
			$post = array_merge($search_criteria,$post);
		}		
		ApplicationSessions::run()->write('search_criteria', $post);
		$dealdataProvider = '';		
		$shop_data = $this->getNearestShops($post);	
		$shopdataProvider = new CActiveDataProvider('Shop',array('data'=>$shop_data));	
		
		$this->render('/site/listShop',array(
			'shop_data'=>$shop_data,
			'shops'=>$shopdataProvider,
			'criteria'=>$post,
			'limit'=>'10','offset'=>'0',
			'banners'=>$banners,	
		));
	}

	/*Garima
	*@actionLogShopVisit : logs shop visit in table 
	*@PARAM : shop_id 
	*/
	private function actionLogShopVisit($shop_id)
	{
		if($this->actionHandleCookie($shop_id))
		{
			$curr_week_of_the_month  =  ceil(substr(date('Y-m-d'), -2) / 7); 
			$curr_week_of_the_month  = 'week'.$curr_week_of_the_month;			
			$curr_month_number = date('m');				
			$curr_year = date('Y');	
			$curr_date = date('Y-m-d');			
			$curr_timestamp = date('Y-m-d H:i:s');				
			$browser    = $this->getBrowser();
			$ip_address = $this->getUserIP();
			
			$latitude  = isset(Yii::app()->request->cookies['clatitude']) ? Yii::app()->request->cookies['clatitude']->value : '';
			$longitude = isset(Yii::app()->request->cookies['clongitude']) ? Yii::app()->request->cookies['clongitude']->value : '';
			
			$clocality  = isset(Yii::app()->request->cookies['clocality']) ? Yii::app()->request->cookies['clocality']->value : '';
			$ccity 		= isset(Yii::app()->request->cookies['ccity']) ? Yii::app()->request->cookies['ccity']->value : '';
			$cstate 	= isset(Yii::app()->request->cookies['cstate']) ? Yii::app()->request->cookies['cstate']->value : '';
			$ccountry 	= isset(Yii::app()->request->cookies['ccountry']) ? Yii::app()->request->cookies['ccountry']->value : '';
			
			$location = $clocality." ".$ccity." ".$cstate." ".$ccountry;
			if(!empty($browser) && is_array($browser))
			{
				$other_details = $browser['name']." ".$browser['version']." ".$browser['platform'];
			}else{
				$other_details = "";
			}
			
			$model = new ShopVisitDetails();
			$model->shop_id   	  = $shop_id;
			$model->user_id   	  = $this->user_id;
			$model->datetime  	  = $curr_timestamp;
			$model->latitude  	  = $latitude;
			$model->longitude 	  = $longitude;
			$model->location  	  = $location;
			$model->ip_address 	  = $ip_address;
			$model->other_details = $other_details;
				
			if($model->save())
			{
				$update_daily = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$shop_id.'" AND count_type="daily" AND count_date="'.$curr_date.'" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				if($update_daily<1)
				{
					$stat_model_d = new ShopVisitStatistics;
					$stat_model_d->shop_id = $shop_id;
					$stat_model_d->total_count = 1;
					$stat_model_d->count_type = 'daily';
					$stat_model_d->count_date = $curr_date;
					$stat_model_d->month_week = $curr_week_of_the_month;
					$stat_model_d->month = $curr_month_number;
					$stat_model_d->year = $curr_year;
					$stat_model_d->last_updated_on = $curr_timestamp;					
					$stat_model_d->save();
				}
				
				$update_weekly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$shop_id.'" AND count_type="weekly" AND month_week="'.$curr_week_of_the_month.'" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				if($update_weekly<1)
				{
					$stat_model_w = new ShopVisitStatistics;
					$stat_model_w->shop_id = $shop_id;
					$stat_model_w->total_count = 1;
					$stat_model_w->count_type = 'weekly';					
					$stat_model_w->month_week = $curr_week_of_the_month;
					$stat_model_w->month = $curr_month_number;
					$stat_model_w->year = $curr_year;
					$stat_model_w->last_updated_on = $curr_timestamp;
					$stat_model_w->save();					
				}
				
				$update_monthly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$shop_id.'" AND count_type="monthly" AND month="'.$curr_month_number.'" and year="'.$curr_year.'"' )->execute();
				if($update_monthly<1)
				{
					$stat_model_m = new ShopVisitStatistics;
					$stat_model_m->shop_id = $shop_id;
					$stat_model_m->total_count = 1;
					$stat_model_m->count_type = 'monthly';
					$stat_model_m->month = $curr_month_number;
					$stat_model_m->year = $curr_year;
					$stat_model_m->last_updated_on = $curr_timestamp;
					$stat_model_m->save();					
				}
				
				$update_yearly = Yii::app()->db->createCommand( 'UPDATE shop_visit_statistics SET total_count=total_count+1,last_updated_on="'.$curr_timestamp.'" WHERE shop_id="'.$shop_id.'" AND count_type="yearly" AND year="'.$curr_year.'"' )->execute();
				if($update_yearly<1)
				{
					$stat_model_y = new ShopVisitStatistics;
					$stat_model_y->shop_id = $shop_id;
					$stat_model_y->total_count = 1;
					$stat_model_y->count_type = 'yearly';
					$stat_model_y->year = $curr_year;
					$stat_model_y->last_updated_on = $curr_timestamp;
					$stat_model_y->save();					
				}
			}
		}
		return true;
	}
	/*Garima
	*@actionHandleCookie : reads and manages shop visit data in cookie
	*@PARAM : shop_id 
	*@RETURN : return true if log shop visit is required
	*/
	private function actionHandleCookie($shop_id)
	{
		$save_required = true;
		$recent_shop_visited  = isset(Yii::app()->request->cookies['RVS_DATA']) ? Yii::app()->request->cookies['RVS_DATA']->value : "";
				
		$new_cookie_arr = array();
		if(!empty($recent_shop_visited))
		{			
			$cookies_arr =  explode(",",$recent_shop_visited);
			$match = false;
			foreach($cookies_arr as $row)
			{	
				$data_arr =  explode("~",$row);
				$key = $data_arr[0];
				$time_diff = time()-$data_arr[1];
				if($shop_id == $key)
				{	
					$match = true;
					if( $time_diff >= 1800)
					{						
						$new_cookie_arr[] = $key."~".time();
						$save_required = true;// save shop visit detail is required(since expired)
					}else
					{
						$new_cookie_arr[] = $row;
						$save_required = false;// save shop visit detail is not required(since not expired)
					}			
				}else{
					$new_cookie_arr[] = $row;
					//$save_required = false;// save shop visit detail is not required(since not expired)				
				}
			}
			if(!$match){
				$new_cookie_arr[] = $shop_id."~".time();
				$save_required = true;// save shop visit detail is required(since not in cookie)
			}
			
		}else
		{
			$new_cookie_arr[] = $shop_id."~".time();
			$save_required = true;// save shop visit detail is required(since cookie empty)
		}
		
		unset(Yii::app()->request->cookies['RVS_DATA']);
		
		$new_cookie = implode(",", $new_cookie_arr);
		
		$cookie = new CHttpCookie('RVS_DATA', $new_cookie);
		$cookie->expire = time()+60*60*24*180; 
		Yii::app()->request->cookies['RVS_DATA'] = $cookie;
		
		return $save_required;
	
	}
	
	public function loadModel($id)
	{
		$model=Shop::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionRating()
	{
		$return= false;
		$status = "0";
		$count="";
          
		if(isset($_POST) && Controller::loggedInStatus())
		{			
			$shop_id = $_POST['shop_id'];
			$ratings = $_POST['ratings'];
			
			$data = Rating::model()->findByAttributes(array('user_id'=>$this->user_id,'shop_id'=>$shop_id,'active_status'=>'S','status'=>'1'));
			$my_rating = Rating::model()->findAll(array('condition'=>'status = "1" and shop_id='.$shop_id));
			$count = count($my_rating);
			 if(count($data)>0 && $ratings=="0")
			{
				$data->status = "0";
				$data->rating = $ratings;
				if($data->save())
				{
					$my_rating = Rating::model()->findAll(array('condition'=>'status ="1" and shop_id='.$shop_id));
					$count = count($my_rating);
					$return= true;
				}
			}else if(count($data)>0)
			{
				$data->rating = $ratings;
				$data->save();			
				$return= true;
			}
			else
			{
				if($ratings>0)
				{
					$model=new Rating;
					$model->product_id = null;				
					$model->shop_id = $shop_id;			
					$model->user_id = $this->user_id;		
					$model->rating = $ratings;			
					$model->added_on = time();
					$model->updated_on = time();
					if($model->save())
					{
						$my_rating = Rating::model()->findAll(array('condition'=>'status ="1" and shop_id='.$shop_id));
						$count = count($my_rating);
						$return= true;
					}
				}else{
						$return= true;
				}
			}	
			$status = "1";
		}else{
			$status = "2";
		}
		print json_encode(array('success'=>$return,'status'=>$status,'count'=>$count));
	}
        
	public function actionReview()
	{       
			$return= false;
			$login_status = false;
	
			if(isset($_POST) && Controller::loggedInStatus()){
				
				$p = new CHtmlPurifier();
				$p->options = array(
					'URI.AllowedSchemes'=>array(
						'http' => true, 
						'https' => true,
					),
					'HTML.Allowed' => 'p,a[href],b,i', 
				);
				$review = $p->purify($_POST['review']);
				if(!empty($review)){
					$shop_id = $_POST['shop_id'];
					$deal_id = $_POST['deal_id'];
					$product_id = $_POST['product_id'];

					$comment_model=new Comment;
					$comment_model->product_id = $product_id;
					$comment_model->deal_id = $deal_id;
					$comment_model->parent_id = null;
					$comment_model->shop_id = $shop_id;			
					$comment_model->user_id = $this->user_id;		
					$comment_model->comment = $review;
					$comment_model->type = 'P';
					$comment_model->active_status = 'H';
					$comment_model->added_on = time();
					$comment_model->updated_on = time();
					if($comment_model->save())
					{
						$return= true;
						$login_status = true;
					}
					else
					{
						$login_status = true;
					}
				}			   
			}
			else
			{
				$login_status = false;
			}
		print json_encode(array('success'=>$return,'login_status'=>$login_status));
	}
        
	public function actionReply()
	{       
			$return= false;
	
			if(isset($_POST) && !empty($this->user_id)){
				
				$p = new CHtmlPurifier();
				$p->options = array(
					'URI.AllowedSchemes'=>array(
						'http' => true, 
						'https' => true,
					),
					'HTML.Allowed' => 'p,a[href],b,i', 
				);
				$review = $p->purify($_POST['reply']);
				if(!empty($review)){
					$shop_id = $_POST['shop_id'];
					$parent_id = $_POST['parent_id'];

					$comment_model=new Comment;
					$comment_model->product_id = null;
					$comment_model->deal_id = null;
					$comment_model->parent_id = $parent_id;
					$comment_model->shop_id = $shop_id;			
					$comment_model->user_id = $this->user_id;		
					$comment_model->comment = $review;
					$comment_model->type = 'R';
					$comment_model->active_status = 'H';
					$comment_model->added_on = time();
					$comment_model->updated_on = time();
					if($comment_model->save())
						$return= true;  
				}              
			}                
			print json_encode(array('success'=>$return));            
	}




        // Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	private function actionGetNearestShops($geodata,$searchtext = null)
	{
		$model = null;
		$user_search  = ApplicationSessions::run()->read('user_search');
		if(!empty($user_search['latitude']) && !empty($user_search['longitude']))
		{
			$lat = $user_search['latitude'];
			$lng = $user_search['longitude'];
		}
		elseif(is_array($geodata) && !empty($geodata)) {
			$lat = $geodata['latitude'];
			$lng = $geodata['longitude'];
		}
		if(is_array($geodata) && !empty($geodata))
		{	
			// $lat = $geodata['latitude'];
			// $lng = $geodata['longitude'];
			if( ($lat>0 || $lat<0) && ($lng>0 || $lng<0) ) 
			{
				$distance = 10;
			
				$criteria = new CDbCriteria();
				$criteria->select = "s.*,c.*, SQRT(
									POW(69.1 * (s.latitude - $lat), 2) +
									POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
				$criteria->alias ='s';
				$criteria->join = "LEFT OUTER JOIN category c ON s.category_id = c.category_id";
				$criteria->condition = "s.status=1";
				if($searchtext!='')
				{
					$criteria->condition = "s.name like '%$searchtext%' OR 
											s.address like '%$searchtext%' OR 
											s.zip_code like '%$searchtext%' OR 
											s.description like '%$searchtext%' OR 
											s.address like '%$searchtext%' OR 
											c.category like '%$searchtext%' OR   
										";
				}
				$criteria->having = "distance < $distance ";
				$criteria->order = "distance, name ";
				$model = Shop::model()->findAll($criteria);
			}			
		}
		if($model=='' || is_null($model) || empty($model))
		{
			$model = Shop::model()->findAll(array('condition'=>'status=1','limit'=>10,'order'=>'shop_id desc'));
		}
		
		return $model;
	}
	
	public function actionAddToCart()
	{
		if(isset($_POST) && Controller::loggedInStatus()){
			$product_data = Product::model()->findByPk($_POST['product_id']);
			
			if(empty($product_data->discount_price) || $product_data->discount_price == 0.00)
			{
				$price = $product_data->price;
			}
			else
			{
				$price = $product_data->discount_price;
			}
			
			$order_id = $this->generateOrderId();
			
			$order_data = Order::model()->find(array('condition'=>'order_no='.$order_id.' and product_id='.$_POST['product_id'].' and status="1"'));
			
			if(empty($order_data))
			{
				$model = new Order;
				$qty = $_POST['qty'];
				$cart_count = ApplicationSessions::run()->read('cart_count') + 1;
			}
			else
			{
				$cart_count = ApplicationSessions::run()->read('cart_count');
				$model = $order_data;
				$qty = ($order_data->quantity + $_POST['qty']);
			}
			if($qty<=10)
			{
				$model->order_no = $order_id;
				$model->user_id = $_POST['user_id'];
				$model->product_id = $product_data->product_id;
				$model->shop_id = $product_data->shop_id;
				$model->unit_price = $price;
				$model->quantity = $qty;
				$model->sub_total =  ($price*$qty);
				$model->added_on = time();
				$model->updated_on = time();
				
				if($model->save())
				{
					$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"')); 
					$cart_count = Order::model()->count(array('condition'=>'order_no='.$order_id.' and status="1"')); 
					$cart = $this->getCartHtml($order_data);
					ApplicationSessions::run()->write('cart_count',$cart_count);
					echo $cart."::".$cart_count;
				}
			}else{
				$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"')); 
				$cart_count = Order::model()->count(array('condition'=>'order_no='.$order_id.' and status="1"')); 
				$cart = $this->getCartHtml($order_data);
				ApplicationSessions::run()->write('cart_count',$cart_count);
				echo $cart."::".$cart_count;
			}
		}else{
			echo '401';
		}	
		
	}
	
	public function actionDeleteCart()
	{
		$order_id = ApplicationSessions::run()->read('order_id');
		$product_id = $_POST['product_id'];
		Order::model()->updateAll(array('status'=>'0'),'order_no='.$order_id.' and product_id='.$product_id);
		$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"')); 
		$cart_count = Order::model()->count(array('condition'=>'order_no='.$order_id.' and status="1"')); 
		$cart = $this->getCartHtml($order_data);
		ApplicationSessions::run()->write('cart_count',$cart_count);
		echo $cart."::".$cart_count;
	}
	
	public function actionUpdateCart()
	{
		$order_id = ApplicationSessions::run()->read('order_id');
		$product_id = $_POST['product_id'];
		$qty = $_POST['qty'];
		$product_data = Product::model()->findByPk($_POST['product_id']);
		
		if(empty($product_data->discount_price) || $product_data->discount_price == 0.00)
		{
			$price = $product_data->price;
		}
		else
		{
			$price = $product_data->discount_price;
		}
		
		$model = Order::model()->find(array('condition'=>'order_no='.$order_id.' and product_id='.$product_id.' and status="1"'));
		$model->unit_price = $price;
		$model->quantity = $qty;
		$model->sub_total =  ($price*$qty);
		$model->updated_on = time();
		
		if($model->save())
		{
			$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"')); 
			$cart = $this->getCartHtml($order_data);
			echo $cart;
		}
	}
	
	public function actionViewCart()
	{
		if(isset($_POST) && Controller::loggedInStatus()){
			$order_id = ApplicationSessions::run()->read('order_id');
		
			if(!empty($order_id))
			{
				$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"')); 
			}
			else
			{
				$order_data = array();
			}
			
			$cart = $this->getCartHtml($order_data);
			echo $cart;
		}else{
			echo '401';
		}	
		
	}
	
	public function actionCheckout()
	{
		$order_id = ApplicationSessions::run()->read('order_id');
		
		if(!empty($order_id))
		{
			$user_id = ApplicationSessions::run()->read('user_id');
			$user = User::model()->find(array('condition'=>'user_id='.$user_id));
			
			$shipping_data = Shipping::model()->find(array('condition'=>'order_no='.$order_id));
			$model=new Shipping;
			
			if(!empty($shipping_data))
			{
				$model = $shipping_data;
			}
			
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);
			$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
			if(isset($_POST['Shipping']))
			{
				
				$model->attributes=$_POST['Shipping'];
				$model->order_no = $order_id;
				$model->user_id = $user_id;
				$model->added_on = time();
				$model->updated_on = time();
				
				if($model->save())
				{
					Order::model()->updateAll(array('order_status'=>'P','order_no='.$order_id.' and status="1"'));
					$order_data = Order::model()->findAll(array('select'=>'distinct(shop_id)','condition'=>'order_no='.$order_id.' and status="1"'));
					
					
					foreach($order_data as $key=>$val)
					{
						$total = 0;
						$all_total = 0;
						$discount = 0;
						$i = 1;
						$subject = 'Your Shop Order : '.$order_id; 
						$owner_email = $val->shop->owner->email;
						$owner_name = $val->shop->owner->name;
						
						$msg = '<!doctype html>
								<html>
									<head>
										<meta charset="utf-8">
										<title>shopnext-emailar</title>
									</head>
									<body style="margin: 0; padding: 0;">
										<table cellpadding="0" cellspacing="0" width="100%" style="width:100%;margin:0 auto;border-top:4px solid #000000;">
										<tbody>
											<tr>
												<td colspan="2" style="border-bottom:2px solid #E8E8E8;">
													<img src="'.Yii::app()->theme->baseUrl.'/images/shopnex_logo.jpg"/>
												</td>
											</tr>
											<tr>
												<td align="center" style="padding: 10px 0;">
													<table cellpadding="0" cellspacing="0" width="350px" style="margin:0 auto;">
														<tr>
															<td>
																<img src="'.Yii::app()->theme->baseUrl.'/images/Check.png" />
															</td>
															<td style="margin:0 0 0 10px;">
																<p>ORDER IS RECEIVED FOR YOUR SHOP</p>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td style="color:#6E6A6A;font-weight:bold; font-size:14px;">
													<p> Hi '.$owner_name.',<p>
												</td>
											</tr>
											<tr>
												<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">
													<p> We have received order for your shop.Please check below details of order :-</p>
												</td>
											</tr>
											<tr>
												<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">
													<p> Your Order ID :<span style="color:#294CED;"> '.$order_id.' </span></p>
													
													<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 0 0 0;">
														<tr>
															<td style="vertical-align: top;color:#9F9F9F;font-weight:normal; font-size:14px;">
																<p>Order Placed on: '.date('d-m-Y h:i:s a').'</p>
															</td>
															<td style="float:right;color:#9f9f9f;font-weight:normal;font-size:14px;margin:0;padding:0;line-height:22px;word-break:break-word">
																<span style="color:#6E6A6A; font-size:14px;font-weight:bold;">SHIPPING ADDRESS</span>
																	<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
																		'.$model->name.'</p>
																	<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
																		'.$model->address.'</p>
																	<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">										
																		<span style="color:#294CED;">'.$model->mobile_no.'</span>
																	</p>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 auto;">
														<thead>
															<tr>
																<td colspan="5" style="padding: 15px 0;">
																	ORDER DETAILS
																</td>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>S. No.</td>
																<td>Item Name</td>
																<td>Quantity</td>
																<td>Price</td>
															</tr>';
															
												$item_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and shop_id='.$val->shop_id));
												
												foreach($item_data as $item)
												{
													$total += $item->sub_total;
													$all_total += ($item->quantity*$item->product->price);
													if(!empty($item->product->discount_price) && $item->product->discount_price != 0.00)
													{
														$discount += ($item->quantity*($item->product->price - $item->product->discount_price));
													}
													
													$msg .= '<tr>
																<td>'.$i.'</td>
																<td><p style="font-size: 14px;">'.$item->product->name.'</td>
																<td>'.$item->quantity.'</td>
																<td>'.($item->quantity*$item->product->price).'</td>
															</tr>';
															
													$i++;
												}

												$msg .= '</tbody> 
													</table>
												</td>
											</tr>
											<tr>
												<td  align="center" style="padding: 10px 0;">&nbsp;</td>
											</tr>
											<tr>
												<td  align="center" style="padding: 10px 0;">
													<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(250, 251, 206);">
														<tr>
															<td colspan="2" style="border-right:1px solid #000000;word-spacing:5px;padding:0 10px;">
																Estimated Dispatch within <span style="font-weight:bold">3 to 4 working days</span> 
															</td>
															<td colspan="3" style="  padding:0 10px;word-spacing:5px;">
																Estimated Delivery by:<span style="font-weight:bold"> 7 working days </span>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												</td>
													<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
														<tr>
															<td></td>
															<td style=" float:right; padding:0 10px;word-spacing:5px;">
																<p style="color:#9F9F9F;line-height:24px;">
																	<span style="font-weight:bold;">
																	Total Amount : '.$all_total.'</span></br>
																	Discount (-) : '.$discount.'
																</p>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												</td>
													<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(237, 237, 237);
											  border-bottom: 2px solid rgb(209, 209, 209);">
													<tr>
														 <td style=" float:right; padding:0 10px;word-spacing:5px;">
															   <h1>Payable Amount:	Rs. '.$total.'</h1>
														 </td>
													</tr>
												   </table>
												</td>
											</tr>
											<tr>
												<td>
													<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
														<tr>
															<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
																<p> -- Regards</p>
																<p>Shopnext Team</p>
															</td>
														</tr>
														<tr>
															<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
																<p> For any query or assistance, feel free to<a href="#" style="color:#8BD4EF;"> Contact Us</a></p>
															</td>
														</tr>
												   </table>
												</td>
											</tr>
										</tbody>	 
										</table>
									</body>
								</html>';
								
						$msg = preg_replace( "/\r|\n/", "", $msg );	
						$this->sendMail($subject,$msg,$owner_email,$owner_name);
					}
					
					
					// Email to user and superadmin
						$order_data = Order::model()->findAll(array('condition'=>'order_no='.$order_id.' and status="1"'));
						$receiver = array(	
											'name'=>$model->name,
											'email'=>$model->email
										 );	
						$this->sendOrderEmail($order_id,$order_data,$receiver,$model,'user');
						
						$receiver = array(	
											'name'=>'Superadmin',
											'email'=>Yii::app()->params['superAdminEmail']
										 );	
						$this->sendOrderEmail($order_id,$order_data,$receiver,$model,'superadmin');

					// End of email to user and superadmin
					
					
					
					ApplicationSessions::run()->delete('order_id');
					ApplicationSessions::run()->write('cart_count',0);
					Yii::app()->user->setFlash('msg', "Your order #$order_id has been placed successfully!");
					$this->render('checkout');
					exit;
				}	
			}

			$this->render('shipping',array(
				'model'=>$model,
				'banners'=>$banners,
				'user'=>$user
			));
		}
		else
		{
			$this->redirect(Yii::app()->createUrl('site/index'));
		}
	}
	
	public function getCartHtml($order_data)
	{
		$total = 0;
		$discount = 0;
		$i = 1;
		
		$html = '
		           <div class="row margin-bottom-40">
					<div class="col-md-12 col-sm-12">
					<h1 class="cart_header">Shopping cart <div id="c_close" onclick="return closeCart();"></div></h1>
							<div class="goods-page">
								<div id="cart_data" class="goods-data clearfix mCustomScrollbar">
									<div class="table-wrapper-responsive ">
										<table summary="Shopping cart" class="table">
										  <tr>
											<th class="goods-page-image">Image</th>
											<th class="goods-page-description">Name</th>
											<th class="goods-page-quantity">Quantity</th>
											<th class="goods-page-price">Unit price</th>
											<th class="goods-page-total" colspan="2">Total</th>
										  </tr>';
			  
		if(!empty($order_data))
		{
			foreach($order_data as $row)
			{
				$sub_total = ($row->quantity*($row->product->price));
				$total += ($row->quantity*($row->product->price));
							
				if(!empty($row->product->discount_price) && $row->product->discount_price != 0.00)
				{
					$discount += ($row->quantity*($row->product->price - $row->product->discount_price));
				}
				
				$product_data = Product::model()->findByPk($row->product_id);
				
				if($product_data->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$product_data->productImage->image))
				{ 
					$prod_url = Yii::app()->baseUrl."/upload/product/".$product_data->productImage->image;
				}
				else
				{
					$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
				}
				
				$html .= '<tr>
							<td class="goods-page-image">
								<a href="'.Yii::app()->params['SERVER'].'product/detail/'.$product_data->name.'"><img src="'.$prod_url.'" alt="'.$product_data->name.'"></a>
							</td>
							<td class="goods-page-description">
								<h3><a href="'.Yii::app()->params['SERVER'].'product/detail/'.$product_data->name.'">'.$product_data->name.'</a></h3>
							</td>
							<td class="goods-page-quantity">
								<div class="product-quantity">
									<input id="'.$product_data->product_id.'" type="text" value="'.$row->quantity.'" class="form-control input-sm">
									<a href="javascript:void(0);" onclick="updateCart('.$product_data->product_id.')">Change</a>
								</div>
							</td>
							<td class="goods-page-price">
								<strong><span>Rs. </span>'.$row->product->price.'</strong>
							</td>
							<td class="goods-page-total">
								<strong><span>Rs. </span>'.$sub_total.'</strong>
							</td>
							<td class="del-goods-col">
								<a class="del-goods" href="javascript:void(0);" onclick="return delItem('.$product_data->product_id.')">&nbsp;</a>
							</td>
						</tr>';
				$i++;
			}
				
			$html .= '  </table>
						</div>
						<div class="shopping-total">
						  <ul>
							<li>
							  <em>Sub total</em>
							  <strong class="price"><span>Rs.</span>'.$total.'</strong>
							</li>
							<li>
							  <em>Discount Amount</em>
							  <strong class="price"><span>Rs.</span>'.$discount.'</strong>
							</li>
							<li class="shopping-total-price">
							  <em>Total</em>
							  <strong class="price"><span>Rs.</span>'.($total - $discount).'</strong>
							</li>
						  </ul>
						</div>
					  </div>
					  <a href="'.Yii::app()->createUrl('site/listShop').'"><button class="btn btn-default" type="button" >Continue shopping <i class="fa fa-shopping-cart"></i></button></a>
					  <a href="'.Yii::app()->createUrl('shop/checkout').'"><button class="btn btn-primary" type="button">Checkout <i class="fa fa-check"></i></button></a>
					</div>
				  </div>
				  <!-- END CONTENT -->
				</div>';
		}
		else
		{
			$html .= '<tr>
							<td colspan="6">Your cart is empty!</td>
						</tr>
					</table>
						</div>
					  </div>
					  <a href="'.Yii::app()->createUrl('site/listShop').'"><button class="btn btn-default" type="button" >Continue shopping <i class="fa fa-shopping-cart"></i></button></a>
					</div>
				  </div>
				  <!-- END CONTENT -->
				</div>';
		}
		
		return $html;
	}
	
	public function sendOrderEmail($order_no,$order_data,$receiver,$shipping_model,$type)
	{
		$total = 0;
		$all_total = 0;
		$discount = 0;
		$i = 1;
		if($type == 'user'){
			$subject = 'Your ShopNext Order : '.$order_no; 
		}else{
			$subject = 'ShopNext Order : '.$order_no; 
		}	
		
		$receiver_name = $receiver['name'];
		$receiver_email = $receiver['email'];
		$msg = '<!doctype html>
					<html>
						<head>
							<meta charset="utf-8">
							<title>shopnext-emailar</title>
						</head>
						<body style="margin: 0; padding: 0;">
							<table cellpadding="0" cellspacing="0" width="100%" style="width:100%;margin:0 auto;border-top:4px solid #000000;">
							<tbody>
								<tr>
									<td colspan="2" style="border-bottom:2px solid #E8E8E8;">
										<img src="'.Yii::app()->params['SERVER'].'themes/frontendTheme/images/shopnex_logo.jpg"/>
									</td>
								</tr>
								<tr>
									<td align="center" style="padding: 10px 0;">
										<table cellpadding="0" cellspacing="0" width="350px" style="margin:0 auto;">
											<tr>
												<td>
													<img src="'.Yii::app()->params['SERVER'].'themes/frontendTheme/images/Check.png" />
												</td>
												<td style="margin:0 0 0 10px;">
													<p>ORDER IS RECEIVED</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="color:#6E6A6A;font-weight:bold; font-size:14px;">
										<p> Hi '.$receiver_name.',<p>
									</td>
								</tr>
								<tr>
									<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">';
									if($type == 'user'){
										$msg .= '<p> We have received order from you.Please check below details of order :-</p>';
									}else{
										$msg .= '<p> Received order from '.ApplicationSessions::run()->read('fullname').'. Below are the details of order :-</p>';
									}	
										
									$msg .= '</td>
								</tr>
								<tr>
									<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">
										<p> Order ID :<span style="color:#294CED;"> '.$order_no.' </span></p>
										
										<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 0 0 0;">
											<tr>
												<td style="vertical-align: top;color:#9F9F9F;font-weight:normal; font-size:14px;">
													<p>Order Placed on: '.date('d-m-Y h:i:s a').'</p>
												</td>
												<td style="float:right;color:#9f9f9f;font-weight:normal;font-size:14px;margin:0;padding:0;line-height:22px;word-break:break-word">
													<span style="color:#6E6A6A; font-size:14px;font-weight:bold;">SHIPPING ADDRESS</span>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
															'.$shipping_model->name.'</p>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
															'.$shipping_model->address.'</p>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">										
															<span style="color:#294CED;">'.$shipping_model->mobile_no.'</span>
														</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 auto;">
											<thead>
												<tr>
													<td colspan="5" style="padding: 15px 0;">
														ORDER DETAILS
													</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>S. No.</td>
													<td>Shop Name</td>
													<td>Item Name</td>
													<td>Quantity</td>
													<td>Price</td>
												</tr>';
											
			
									foreach($order_data as $item)
									{
										$total += $item->sub_total;
										$all_total += ($item->quantity*$item->product->price);
										if(!empty($item->product->discount_price) && $item->product->discount_price != 0.00)
										{
											$discount += ($item->quantity*($item->product->price - $item->product->discount_price));
										}
										
										$msg .= '<tr>
													<td>'.$i.'</td>
													<td><p style="font-size: 14px;">'.$item->shop->name.'</td>
													<td><p style="font-size: 14px;">'.$item->product->name.'</td>
													<td>'.$item->quantity.'</td>
													<td>'.($item->quantity*$item->product->price).'</td>
												</tr>';
												
										$i++;
									}

							$msg .= ' 		</tbody>
										</table>
									</td>
								</tr>
								 <tr>
									<td  align="center" style="padding: 10px 0;">&nbsp;</td>
								 </tr>
								<tr>
									<td  align="center" style="padding: 10px 0;">
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(250, 251, 206);">
											<tr>
												<td colspan="2" style="border-right:1px solid #000000;word-spacing:5px;padding:0 10px;">
													Estimated Dispatch within <span style="font-weight:bold">3 to 4 working days</span> 
												</td>
												<td colspan="3" style="  padding:0 10px;word-spacing:5px;">
													Estimated Delivery by:<span style="font-weight:bold"> 7 working days </span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
											<tr>
												<td></td>
												<td style=" float:right; padding:0 10px;word-spacing:5px;">
													<p style="color:#9F9F9F;line-height:24px;">
														<span style="font-weight:bold;">
														Total Amount : '.$all_total.'</span></br>
														Discount (-) : '.$discount.'
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
								   <td style="padding:10px 0" align="center">
									<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(237, 237, 237);
								  border-bottom: 2px solid rgb(209, 209, 209);">
										<tr>
										 <td style=" float:right; padding:0 10px;word-spacing:5px;">
											   <h1>Payable Amount:	Rs. '.$total.'</h1>
										 </td>
										</tr>
									   </table>
								   </td>
								</tr>
								<tr>
								   <td>
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
											<tr>
												<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
													<p> -- Regards</p>
													<p>Shopnext Team</p>
												</td>
											</tr>
											<tr>
												<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
													<!--<p> For any query or assistance, feel free to<a href="#" style="color:#8BD4EF;"> Contact Us</a></p>-->
												</td>
											</tr>
									   </table>
								   </td>
								</tr>
							</tbody>	
							</table>
						</body>
					</html>';	
		$msg = preg_replace( "/\r|\n/", "", $msg );						
		$this->sendMail($subject,$msg,$receiver_email,$receiver_name);
	}	
}