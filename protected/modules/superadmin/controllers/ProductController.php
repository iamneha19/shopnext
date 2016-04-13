<?php
Yii::import("xupload.models.XUploadForm");

class ProductController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public function actions() 
	{
		return array(
					'upload' => array(
							'class' 		=> 'xupload.actions.XUploadAction', 
							'path' 			=> Yii::app()-> getBasePath() . "/../upload/product", 
							'publicPath' 	=> Yii::app()->getBaseUrl()."/upload/product" , 
						),
				);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Product;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			if(!is_dir("upload/product/"))
			 	mkdir("upload/product/" , 0777,true);
			$price = $_POST['Product']['price'];
			// $discount_price = $_POST['Product']['discount_price'];
			$model->attributes = $_POST['Product'];			
			$model->product_category_id = $_POST['Product']['product_category_id_autocomplete'];
			$model->shop_id     = $_POST['Product']['shop_id_autocomplete'];
			$model->added_on 	= time();
			$model->updated_on 	= time();
			$model->price       = str_replace(',','',$price);
			$discount = $model->discount;
			$discount_type = $model->discount_type;
			
			if(!empty($discount))
			{
				if($discount_type=='P')
				{		
					$discount = (($price*$discount)/ 100);
					$new_price = ($price - $discount);
					$model->discount_price = $new_price;
				}

				else
				{					
					$new_price = $price - $discount;
					$model->discount_price = $new_price;
				}		
			}		
			$modelObject = CUploadedFile::getInstance($model,'product_image_id');
			
			// echo "<pre>";
			// print_r($model->discount_price);
			// exit;
			
			if($model->save())
			{
				if(!empty($modelObject))
				{
					$ext = explode(".",$modelObject->name);
					$image_name = $model->product_id.".".$ext[1];
					
					$image_path = Yii::app()->basePath . '/../upload/product/'.$image_name;
					$return = $modelObject->saveAs($image_path);
					
					$product_image_model = new ProductImage;
					$product_image_model->product_id = $model->product_id;
					$product_image_model->image = $image_name;
					$product_image_model->added_on 	= time();
					$product_image_model->updated_on = time();
					
					if($product_image_model->save())
					{
						$model->updateByPk($model->product_id,array("product_image_id"=>$product_image_model->product_image_id));
					}	
					
				} else {
					$image_name = $_POST['Product']['product_image_id'];
				}	
				$this->redirect(array('view','id'=>$model->product_id));
			}else{
				if($discount == 0.00){
					$model->discount = '';
				}
			}	
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if($model->discount == 0.00){
			$model->discount = '';
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			if(!is_dir("upload/product/"))
			 	mkdir("upload/product/" , 0777,true);
			$price = $_POST['Product']['price'];	
			$model->attributes = $_POST['Product'];			
			$model->product_category_id = $_POST['Product']['product_category_id_autocomplete'];
			$model->shop_id     = $_POST['Product']['shop_id_autocomplete'];
			$model->price       = str_replace(',','',$price);
			$model->updated_on 	= time();
			$discount = $model->discount;
			$discount_type = $model->discount_type;
			
			if(!empty($discount))
			{
				if($discount_type=='P')
				{		
					$discount = (($price*$discount)/ 100);
					$new_price = ($price - $discount);
					$model->discount_price = $new_price;
				}

				else
				{					
					$new_price = $price - $discount;
					$model->discount_price = $new_price;
				}		
			}		
			
			$modelObject = CUploadedFile::getInstance($model,'product_image_id');			
			
			if($model->save())
			{				
				if(!empty($modelObject))
				{
					$ext = explode(".",$modelObject->name);
					$image_name = $model->product_id.".".$ext[1];
					
					$image_path = Yii::app()->basePath . '/../upload/product/'.$image_name;
					$return = $modelObject->saveAs($image_path);
					
					$product_image_model = new ProductImage;
					$product_image_model->product_id = $model->product_id;
					$product_image_model->image = $image_name;
					$product_image_model->added_on 	= time();
					$product_image_model->updated_on 	= time();
					
					if($product_image_model->save())
					{
						$model->updateByPk($model->product_id,array("product_image_id"=>$product_image_model->product_image_id));
					}		
					
				}else {
					$image_name = $_POST['Product']['product_image_id'];
				}				
							
				$this->redirect(array('view','id'=>$model->product_id));
			}else{
				if($discount == 0.00){
					$model->discount = '';
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		try
		{
			$ret = Controller::updateDeletedStatus('Product',$id);
			if($ret)
			{
				$images_delete = ProductImage::model()->updateAll(array('status'=>'0'),'status="1" and product_id="'.$id.'"');					
				$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and product_id="'.$id.'" and deal_id is null');					
			}
		}
		catch(CDbException $e)
		{
			echo "Please try after some time.";
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Product the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested product record has been deleted !!!');			
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Product $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
		
	public function actionSetStatus($id,$active_status)
	{
		$model = $this->loadModel($id);
		$model->active_status=$active_status;
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionChangeStatus()
	{
		$status = false;
		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['product_id_arr'];
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Product',$id_arr);					
				}else 
				{					
					$status = $this->setActiveStatus('Product',$action,$id_arr,'product_id');
				}		
			}		
		}
		if( ($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected products have been activated successfully !!';
			}else if($action=='H' )
			{
				$msg = 'Selected products have been deactivated successfully !!';
			}else if($action=='D' )
			{
				$msg = 'Selected products have been deleted successfully !!';
			}
			$this->setFlashMessage($msg);	
		}
		echo json_encode(array('success'=>$status));
	}
	
	public function actionAutocompleteCategory()
	{
		$output = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new ProductCategory;			
			$resp = $model->findAll(array('condition'=>'product_category like "%'.$_GET['term'].'%" and status="1" and active_status="S"','select'=>'product_category_id,product_category'));
			
			if(!empty($resp))
			{
				$i = 0;
				$output = array();		
				
			 	foreach($resp as $val)
				{
					$output[$i]['id'] = $val['product_category_id'];
					$output[$i]['label'] = $val['product_category'];
					$i++;
				}
			}
		}		
		
		echo CJSON::encode($output);	
	}
	
	public function actionAutocompleteShop()
	{
		if(!empty($_GET['term']))
			$term = $_GET['term'];
		else
			$term = null;
		
		 echo Controller::autocompleteShopJson($term);	
	}
	
	public function actionManageImages($id)
	{	
		$model = $this->loadModel($id);		
		$multiple_image_model = new XUploadForm;
		
		$this->render('manage_images',array(
			'model'=>$model,		
			'multiple_image_model'=>$multiple_image_model,		
		));
	}
	
	public function actionUploadData()
	{
		$shop_data = Product::model()->findbypk($_POST['id']);
		$product_image_id 	 = $shop_data['product_image_id'];
		
		$data = ProductImage::model()->findAll(array('condition'=>'product_id='.$_POST['id'].' AND status="1" AND product_image_id!="'.$product_image_id.'"'));
		$res = '';
		
		if(!empty($data))
		{
			$path = "/shopnext/upload/product/";
			$del_path = "/shopnext/superadmin/product/upload/_method/delete/file/";
			foreach($data as $val)
			{						
				$menu 	   = "";
				$cover 	   = "";
				$shop_image = "";
				$res .= '<tr class="template-download fade in" style="height: 81px;">';
				
				if(file_exists(Yii::app()->basePath.'/../upload/product/'.$val->image))
				{				
					$res .=	'							
							<td class="preview">
								<span class="preview">
								<a download="'.$val->image.'" rel="gallery" title="'.$val->image.'" href="'.$path.$val->image.'">
									<img src="'.$path.$val->image.'" width="80" height="60"></a>
								</span>
							</td>
							<td class="name">
								<a download="'.$val->image.'" rel="gallery" title="'.$val->image.'" href="'.$path.$val->image.'">'.$val->image.'</a>
							</td>';
				}
				else
				{
					$res .=	'							
							<td class="preview">
								<img src="'.$path.$val->image.'" width="80" height="60">
							</td>
							<td class="name">'.$val->image.'</td>';
				}
				
				$res .= '						
						<td colspan="2">&nbsp;</td>
						<td class="delete">
							<button data-url="'.$del_path.$val->image.'/id/'.$val->product_image_id.'/model/Product'.'" data-type="POST" class="btn btn-danger">
								<i class="icon-trash icon-white"></i>
								<span>Delete</span>
							</button>
							<input type="checkbox" name="delete" value="1">
						</td>
						</tr>';
			}
		}
		
		echo $res;
	}
}
