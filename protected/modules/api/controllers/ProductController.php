<?php

class ProductController extends ApiController
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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array();
	}

	/**
	 * List all products.
	 * @param 
	 */
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			if(!empty($_REQUEST['shop_id'])){
			   $criteria->compare('shop_id',$_REQUEST['shop_id']); 
			}
			
			if(!empty($_REQUEST['product_category_id'])){
			   $criteria->compare('product_category_id',$_REQUEST['product_category_id']); 
			}

			
			if(!empty($_REQUEST['sort'])){
			   $criteria->order = $_REQUEST['sort']; 
			}

			$criteria->AddCondition('active_status="S" and status=1');
		   
			$products = Product::model()->findAll($criteria);
			if(!empty($products)){
					$data = array();
					foreach($products as $key => $product){ 
						$data[$key] = $product->attributes;
						$data[$key]['category'] = $product->productCategory->product_category;
						$data[$key]['shop'] = $product->shop->name;
						if(!empty($product->product_image_id)){
							$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/product/'.$product->productImage->image;
						}else{
							$data[$key]['image'] = Yii::app()->params['SERVER'].'upload/product/default.png';
						}	
						$data[$key]['added_on']	 =  $this->dateConvert($product->added_on);
						$data[$key]['updated_on']=  $this->dateConvert($product->updated_on);
					}
					$resp = array('code'=>$resp_code,'data'=>$data);
			}else if(Product::model()->findAll(array('condition'=>'status="0" and shop_id='.$_REQUEST['shop_id'])))
			{
				//if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			} 
		}else{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}

	/**
	 * View product.
	 * @param 
	 */
	public function actionView()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{      
			$product_id = $_REQUEST['product_id'];
			$product = Product::model()->find(array('condition'=>'active_status="S" and status="1" and product_id ='.$product_id)); 
			if(!empty($product)){
				$data= $product->attributes;
				$data['category'] = $product->productCategory->product_category;
				$data['shop'] = $product->shop->name;
				if(!empty($product->product_image_id)){
					$data['image'] = Yii::app()->params['SERVER'].'upload/product/'.$product->productImage->image;
				}else{
					$data['image'] = Yii::app()->params['SERVER'].'upload/product/default.png';
				}	
				$data['added_on']	 =  $this->dateConvert($product->added_on);
				$data['updated_on']=  $this->dateConvert($product->updated_on);
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else if(Product::model()->findAll(array('condition'=>'status="0" and product_id='.$product_id)))
			{
				//if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
			}else{
				// If product is not found.
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}              
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
        
        /**
	 * Create New Product.
	 * @param 
	 */
	public function actionCreate()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{           
			$product_count = Product::model()->count(array('condition'=>'shop_id="'.$_REQUEST['shop_id'].'" and status = "1" and name like "'.$_REQUEST['name'].'"'));
			
			if($product_count==0)
			{
				$model=new Product;
				/* Mandatory fields */
				$model->name=$_REQUEST['name'];
				$model->product_category_id=$_REQUEST['product_category_id'];
				$model->shop_id=$_REQUEST['shop_id'];
				$price = $_REQUEST['price']; 
				$model->price=$price;
				
				/* Optional fiels */
				$discount = $_REQUEST['discount'];
				$discount_type = $_REQUEST['discount_type'];
				 
				if(!empty($_REQUEST['description']))
				{
					$model->description=$_REQUEST['description'];
				}
				if(!empty($discount_type))
				{
					$model->discount_type = $discount_type;
				}
				 
				if(!empty($discount))
				{
					$model->discount = $discount;
					if($discount_type=='P')
					{		
						$discount = (($price*$discount)/ 100);
						$new_price = ($price - $discount);
						$model->discount_price = $new_price;
					}else
					{					
						$new_price = $price - $discount;
						$model->discount_price = $new_price;
					}		
				}
			  
				$model->added_on = time();
				$model->updated_on = time();
				if ($model->validate()) {
					if($model->save()){
						
						if(!empty($_REQUEST['pic']))
						{
							$path = 'product';
							$pic = $this->uploadPic($model->product_id,$_REQUEST['pic'],$path);
						}
						
						if(!empty($pic))
						{
							$model_product_img=new ProductImage;
							$model_product_img->image = $pic;
							$model_product_img->product_id = $model->product_id;
							$model_product_img->added_on = time();
							$model_product_img->updated_on = time();
							
							if($model_product_img->save()){
								$model->updateByPk($model->product_id,array('product_image_id'=>$model_product_img->product_image_id));
							}	
						}
						
						// If saved successfully.
						$resp = array('code'=>$resp_code);
					}
					else{
						// If saving process fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code); 
					}
				}
				else
				{
					// If validation fails. 
					$resp_code = $this->status_code['BAD_REQUEST'];
					$resp = array('code'=>$resp_code); 
				}
			}
			else
			{
				// If product exists in same category. 
				$resp_code = $this->status_code['UPDATE_REQUIRED'];
				$resp = array('code'=>$resp_code); 
			}
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
        
        /**
	 * Update Product.
	 * @param 
	 */
	public function actionUpdate()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{          
			$product_id = $_REQUEST['product_id'];
			$model = Product::model()->find(array('condition'=>'active_status="S" and status="1" and product_id ='.$product_id)); 
			if(!empty($model)){
				//Mandatory fields
				$model->name=$_REQUEST['name'];
				$model->product_category_id=$_REQUEST['product_category_id'];
				$model->shop_id=$_REQUEST['shop_id'];
				$price = $_REQUEST['price']; 
				$model->price= $price; 
				

				//Optional fields
				$discount = $_REQUEST['discount'];
				$discount_type = $_REQUEST['discount_type'];
				 
				if(!empty($_REQUEST['description']))
				{
					$model->description=$_REQUEST['description'];
				}
				if(!empty($discount_type))
				{
					$model->discount_type = $discount_type;
				}
				 
				if(!empty($discount))
				{
					$model->discount = $discount;
					if($discount_type=='P')
					{		
						$discount = (($price*$discount)/ 100);
						$new_price = ($price - $discount);
						$model->discount_price = $new_price;
					}else
					{					
						$new_price = $price - $discount;
						$model->discount_price = $new_price;
					}		
				}

				// $model->added_on = time();
				$model->updated_on = time();
				if ($model->validate()) {
					if($model->save()){
						
						if(!empty($_REQUEST['pic']))
						{
							$path = 'product';
							$pic = $this->uploadPic($model->product_id,$_REQUEST['pic'],$path);
						}
					
						if(!empty($pic))
						{
							$model_product_img=ProductImage::model()->findByPk($model->product_image_id);
							
							if(!empty($model_product_img)){
								$model_product_img->image = $pic;
								$model_product_img->updated_on = time();
								$model_product_img->save();
							}else{
								$model_product_img=new ProductImage;
								$model_product_img->image = $pic;
								$model_product_img->product_id = $model->product_id;
								$model_product_img->added_on = time();
								$model_product_img->updated_on = time();
								
								if($model_product_img->save()){
									$model->updateByPk($model->product_id,array('product_image_id'=>$model_product_img->product_image_id));
								}
							}
								
						}
					
						// If saved successfully.
						$resp = array('code'=>$resp_code);
					}
					else{
						// If savings fails. 
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code); 
					}
				}
				else
				{
					// If validation fails. 
					$resp_code = $this->status_code['BAD_REQUEST'];
					$resp = array('code'=>$resp_code); 
				}
			}else{
				// Product does not exsits
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}  
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}

	/**
	 * Delete product.
	 * @param 
	 */
	public function actionDelete()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{    
			$product_id = $_REQUEST['product_id'];
			$product = Product::model()->find(array('condition'=>'active_status="S" and status="1" and product_id ='.$product_id)); 
			if(!empty($product)){
				$product->status = 0;
				$product->save();
				$resp = array('code'=>$resp_code);
			}else{
				// Product does not exsits
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code,$this->action->Id);
	}
	
	/**
	 * Get product categories.
	 * @param 
	 */
	public function actionCategory()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$categories = ProductCategory::model()->findAll(array('select'=>'product_category_id,product_category','condition'=>'active_status="S" and status=1','order'=>'product_category')); 
			if(!empty($categories)){
				
				foreach($categories as $key=>$category)
				{
					$data[$key]['category_id'] = $category->product_category_id;
					$data[$key]['category'] = $category->product_category;
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
	 * Function to handle a api call for testing.
	 */
	public function actionApiCall()
	{   
		$host = 'http://localhost';
		$url = $host.'/shopnext/api/product/update';

		// LIST
		// $data = 'access_key=96b68e304b10d95710e88c5726f24587273933d7&type=xml';
		// CREATE	
		//$data = 'access_key=d92f821a0044449a63a54957869ccc523bfe9f39&name=new product&shop_id=5&product_category_id=1&price=100&type=xml&pic='.$byte;
		// UPDATE	
		$data = 'access_key=18d460b675fe4787c7d49498e5d1897041516e14&name=test product A update&product_category_id=1&shop_id=5&price=200&description=description about product&product_id=2&pic='.$byte;
		
		
		// $url .= $data;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		echo $output;
		
		
            
	}

}