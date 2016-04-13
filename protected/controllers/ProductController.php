<?php 
class ProductController extends Controller
{ 
	public function actionIndex()
	{
		$this->render('index');
	}
	/* 
	Neha
	*	This is the action to handle ajax request for products pagination.
	*/
	public function actionProductList()
	{	
		$return = "";
		if(isset($_POST) && !empty($_POST))
		{
			$themeBaseUrl = Yii::app()->theme->baseUrl;
			$pagination = $_POST['pagination'];
			$shop_id = $_POST['shopId'];
			$user_id = ApplicationSessions::run()->read('user_id');
			
			$model = $this->actionLoadShopProducts($shop_id,$pagination);
			
			if(count($model>0))
			{
				foreach($model as $products)
				{
					if(!empty($products->product_id))
					{ 
						$product_id = $products->product_id; 
					}
					else
					{
						$product_id = '';
					}
					
					if($products->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$products->productImage->image)){ 
						$prod_url = Yii::app()->params['SERVER']."upload/product/".$products->productImage->image;
					}else{
						$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
					}
					$return .='<div class="prod_cont">
							<div class="product_ic">
								<div class="prod_img"> 
									<a href="'.Yii::app()->params['SERVER'].'product/detail/'.$products->name.'">
									<img src="'.$prod_url.'" alt="'.$products->name.'" style="height:150px;width:150px;"/> 
									</a>
									<div onclick="addToCart(this);" class="over_img cart_btn" data-product="'.$product_id.'" data-shop="'.$shop_id.'" data-user="'.$user_id.'">
										<a href="javascript:void(0);">Add To Cart</a>
									</div>
								</div>
								<div class="prod_cost">
									<div class="price_title">'.$products->name.'</div>
									<div class="price">Rs. '.$products->price.'INR</div>
									<div class="art_footerlist">
										<a href="javascript:void(0);" onclick="postToFeed(\''.$products['name'].'\',\''.addslashes($products['description']).'\',\''.Yii::app()->params['SERVER'].'\',\''.$prod_url.'\');"><img src="'.$themeBaseUrl.'/images/facebook-20.png"/></a> 
											<a class="twitter popup" href="http://twitter.com/share?url='.Yii::app()->params['SERVER'].'product/sharing/'.$products['product_id'].'"onclick="javascript:window.open(this.href,\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><img src="'.$themeBaseUrl.'/images/twitter-20.png"/></a>
											<a href="https://plus.google.com/share?url='.Yii::app()->params['SERVER'].'product/sharing/'.$products['product_id'].'"onclick="javascript:window.open(this.href,\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;">
											<img src="'.$themeBaseUrl.'/images/google-20.png" alt="Share on Google+"/></a>
										
									</div>
								</div>
							</div>
						</div>';
				}
			}
		}
		echo json_encode(array('data'=>$return));
	}
	/* 
		*Getting the data of shops productss
		@params: $name(shop name)
	*/
	public function actionShopProducts($name)
	{
		$product_data = Shop::model()->find(array('condition'=>'name="'.$_GET['name'].'" and status="1" and active_status="S"'));
		if(!empty($product_data))
		{
			$model = $this->actionLoadShopProducts($product_data->shop_id);
			$shop_data = Shop::model()->find(array('condition'=>'status = "1" and active_status="S" and shop_id='.$product_data->shop_id));
			$user_rating = 0; 
			if($this->user_id)	{
				$rating = Rating::model()->findByAttributes(array('user_id'=>$this->user_id,'shop_id'=>$shop_data->shop_id,'active_status'=>'S','status'=>"1"));
				if($rating)
				$user_rating = $rating->rating;
			}
			$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
			$this->render('shop_all_products',array(
				'model'=>$model,
				'banners'=>$banners,
				'limit'=>'9','offset'=>'0',
				'shop_id'=>$product_data->shop_id,
				'shop_data'=>$shop_data,
				'user_rating'=>$user_rating,
			));
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
	}
	/* 
		*Getting all data from products table.
	*/
	public function actionLoadShopProducts($shop_id,$offset=0,$limit=9)
	{
		$model = Product::model()->findAll(array('condition'=>'shop_id='.$shop_id.' and status="1" and active_status="S"','limit'=>$limit,'offset'=>$offset));
		return $model;
	}
	
	public function actionProductDetail()
	{
		if(!empty($_GET['name']))
		{
			$model = Product::model()->find(array('condition'=>'name="'.$_GET['name'].'" and status="1" and active_status="S"'));
			if(!empty($model))
			{
				$product_id = $model->product_id;
			}else{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}else{
			if(!empty($product_id))
			{
				$product_id = $_GET['product_id'];
				$model = Product::model()->find(array('condition'=>' status = "1" and active_status="S" and product_id='.$product_id));
			}else{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		if(!empty($model->productImage->image))
		{
			$prod_image = Yii::app()->basePath."/../upload/product/".$model->productImage->image;
			
			if($model->product_image_id!='' && file_exists($prod_image))
			{
				$image = Yii::app()->params['SERVER']."upload/product/".$model->productImage->image;
			}
			else
			{
				$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
			}
		}else{
			$image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
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

		$criteria=new CDbCriteria;
		$criteria->select="p.product_id,p.product_image_id,p.image,GROUP_CONCAT(c.product_id,':',c.product_image_id,':',c.image SEPARATOR '~') AS reply";
		$criteria->alias="p";
		$criteria->join="LEFT OUTER JOIN product_image c ON p.product_image_id=c.active_status='S' AND c.status=1";
		$criteria->condition="p.product_id=".$product_id." AND p.active_status='S' AND p.status='1'";
		$criteria->group="p.product_image_id";
		$product_images = ProductImage::model()->findAll($criteria);
		// echo "<pre>";print_r($product_images);exit;
		
		$this->page_title = 'Shopnext - '.$model->name;
		$this->page_type = 'Product';
		$this->page_description = $model->description;
		$this->page_image = $image;
		$this->tweet_url = Yii::app()->params['SERVER']."product/ProductDetail/".$product_id;

		$criteria = new CDbCriteria();
		$criteria->select = "c.comment_id,c.comment,c.added_on,u.name as user_name,c.user_id";
		$criteria->alias ='c';
		$criteria->join = "LEFT OUTER JOIN user u ON u.user_id=c.user_id";
		$criteria->condition = "c.status=1 AND c.active_status='S' AND c.parent_id IS NULL AND c.type='P' AND c.product_id=".$product_id;	
		$criteria->order = 'c.comment_id desc';
		$criteria->limit = '5';
		$comments = Comment::model()->findAll($criteria);
		
		
		$this->render('product_detail',array(
			'model'=>$model,
			'image'=>$image,
			'product_images'=>$product_images,
			'comments'=>$comments,
			'shop_data'=>$shop_data,
			'user_rating'=>$user_rating,
			'limit'=>'5',
			'offset'=>'0',
		));
	}
		
	public function loadModel($id)
	{
		$model=Product::model()->findByPk($id);
		if($model==null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}	

?>