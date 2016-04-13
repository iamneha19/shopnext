<?php

class DealController extends Controller
{
	public function actionIndex()
	{
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('latestdeals'));
	}

	public function actionLatestDeals()
	{		
		$geodata = $this->getUserGeolocation();		
		$model   = $this->actionDeals($geodata);		
		
		// $this->page_title = 'Deals';
		$this->render('index',array(
			'geodata'=>$geodata,
			'model'=>$model,
		));
	}
	
	public function actionDetail()
	{
		if(!empty($_GET['title']))
		{
			$model = Deal::model()->find(array('condition'=>'title="'.$_GET['title'].'"'));
			if(!empty($model))
			{
				$deal_id = $model->deal_id;
			}else{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}else{
			if(!empty($deal_id))
			{
				$deal_id = $_GET['deal_id'];
			}else{
				throw new CHttpException(404,'The requested page does not exist.');
			}
			$model = Deal::model()->find(array('condition'=>'deal_id='.$deal_id));
		}
		$deal_img = Yii::app()->basePath."/../upload/deal/".$model->deal_image;
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>5));
		
		if($model->deal_image!='' && file_exists($deal_img))
		{
			$image = Yii::app()->params['SERVER']."upload/deal/".$model->deal_image;
		}
		else
		{
			$shop_data = Shop::model()->find(array('condition'=>'shop_id='.$model->shop_id.' and status="1"'));
			if(!empty($shop_data->shop_image_id)){
				$image = Yii::app()->params['SERVER']."upload/shop/".$shop_data->shopImage->image;
			}else{
				$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
			}				
		}

		if(!empty($model))
		{
			$shop_data = Shop::model()->find(array('condition'=>'status = "1" and active_status="S" and shop_id='.$model->shop_id));
			$user_rating = 0; 
			if($this->user_id)	{
				$rating = Rating::model()->findByAttributes(array('user_id'=>$this->user_id,'shop_id'=>$shop_data->shop_id,'active_status'=>'S','status'=>"1"));
				if($rating)
				$user_rating = $rating->rating;
			}
		}

		$criteria = new CDbCriteria();
		$criteria->select = "c.comment_id,c.comment,c.added_on,u.name as user_name,c.user_id";
		$criteria->alias ='c';
		$criteria->join = "LEFT OUTER JOIN user u ON u.user_id=c.user_id";
		$criteria->condition = "c.status=1 AND c.active_status='S' AND c.parent_id IS NULL AND c.type='P' AND c.deal_id=".$deal_id;	
		$criteria->order = 'c.comment_id desc';
		$criteria->limit = '5';
		$comments = Comment::model()->findAll($criteria);
	
		/**
		 Meta data for deal view page
		**/
		$this->page_title = 'Shopnext - '.$model->title;
		$this->page_type = 'Deal';
		$this->page_description = $model->desc;
		$this->page_image = $image;
		$this->tweet_url = Yii::app()->params['SERVER']."deal/detail/".$deal_id;
		
		$this->render('view',array(
			'model'=>$model,
			'image'=>$image,
			'banners'=>$banners,
			'comments'=>$comments,
			'shop_data'=>$shop_data,
			'user_rating'=>$user_rating,
			'limit'=>'5',
			'offset'=>'0',
		));
	}	
	
	private function actionDeals($geodata,$searchtext = null)
	{
		$model = null;
		if(is_array($geodata) && !empty($geodata))
		{	
			$lat = $geodata['latitude'];
			$lng = $geodata['longitude'];
			if( ($lat>0 || $lat<0) && ($lng>0 || $lng<0) ) 
			{
				$distance = 10;
				
				$criteria = new CDbCriteria();
				$criteria->select = "d.*,s.*,c.*, SQRT(
									POW(69.1 * (s.latitude - $lat), 2) +
									POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
				$criteria->alias ='d';
				$criteria->join = "LEFT OUTER JOIN shop s ON d.shop_id=s.shop_id LEFT OUTER JOIN category c ON s.category_id=c.category_id";
				$criteria->condition = "d.status=1  AND d.end_date >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP())";
				if($searchtext!='')
				{
					$criteria->condition = "
											d.name like '%$searchtext%' OR 
											d.description like '%$searchtext%' OR 
											c.category like '%$searchtext%' OR 
											s.address like '%$searchtext%' OR 
											s.zip_code like '%$searchtext%' OR 
											s.description like '%$searchtext%' OR 
											s.address like '%$searchtext%'  										
										";
				}
				$criteria->having = "distance < $distance ";
				$criteria->order = "distance, deal_id DESC";
				$model = Deal::model()->findAll($criteria);
			}
		}
		if($model=='' || is_null($model) || empty($model))
		{
			$model = Deal::model()->findAll(array('condition'=>'status=1','limit'=>10,'order'=>'deal_id desc'));
		}
		
		return $model;
	}
	
	public function loadModel($id)
	{
		$model=Deal::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}