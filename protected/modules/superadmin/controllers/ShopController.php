<?php
Yii::import("xupload.models.XUploadForm");

class ShopController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public function actions() 
	{
		return array(
					'upload' => array(
							'class' 		=> 'xupload.actions.XUploadAction', 
							'path' 			=> Yii::app()-> getBasePath() . "/../upload/shop", 
							'publicPath' 	=> Yii::app()->getBaseUrl()."/upload/shop" , 
						),
				);
	}
	
	
	public $layout='//layouts/column2';

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
		return Yii::app()->GetAccessRule->get();
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
		$admin_id = ApplicationSessions::run()->read('admin_id');
		$model = new Shop;
		if(!is_dir("upload/shop/"))
			 	mkdir("upload/shop/" , 0777,true);
				
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$states = CHtml::listData(State::model()->findAll(array('condition'=>'status="1"','order' => 'state')), 'state_id', 'state');
		
		if(!empty($_POST['Shop']['state_id']))
		{
			$cities = CHtml::listData(City::model()->findAll(array('condition'=>'status="1" and state_id="'.$_POST['Shop']['state_id'].'"', 'order'=>'city')),'city_id','city');
		}
		else 
		{
			$cities = array();
		}
		
		if(!empty($_POST['Shop']['city_id']))
		{
			$localities = CHtml::listData(Locality::model()->findAll(array('condition'=>'status="1" and city_id="'.$_POST['Shop']['city_id'].'"', 'order'=>'locality')),'locality_id','locality');
		}
		else 
		{
			$localities = array();
		}
		
		if(isset($_POST['Shop']))
		{
			$model->attributes  = $_POST['Shop'];
			$model->category_id = $_POST['Shop']['category_id_autocomplete'];
			$model->owner_id     = $_POST['Shop']['owner_id_autocomplete'];
			$model->admin_id     = $admin_id;
			
			
			$address  = $model->name.",  ".$model->address;
			
			if($model->locality_id!=''){
				$address  .= " ".trim($model->locality->locality);
			}if($model->city_id!=''){
				$address  .= " ".trim($model->city->city);
			}if($model->state_id!=''){
				$address  .= " ".trim($model->state->state);
			}
			$address  .= " India";
			
			if($address!='')
			{
				$geo_data = $this->getLocationGeometry($address,$model->zip_code);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}				
			}			
			
			$model->added_on 	= time();
			$model->updated_on 	= time();
			
			$modelObject = CUploadedFile::getInstance($model,'shop_image_id');
						 
			if($model->save())
			{
				if(!empty($modelObject))
				{
					$ext = explode(".",$modelObject->name);
					$image_name = $model->shop_id.".".$ext[1];
					
					$image_path = Yii::app()->basePath . '/../upload/shop/'.$image_name;
					$return = $modelObject->saveAs($image_path);
					
					$shop_image_model = new ShopImage;
					$shop_image_model->shop_id = $model->shop_id;
					$shop_image_model->image = $image_name;
					$shop_image_model->added_on 	= time();
					$shop_image_model->updated_on 	= time();
					
					if($shop_image_model->save())
					{
						$model->updateByPk($model->shop_id,array("shop_image_id"=>$shop_image_model->shop_image_id));
					}	
					
				}else {
					$image_name = $_POST['Shop']['shop_image_id'];
				}	
				$this->redirect(array('view','id'=>$model->shop_id));
			}else{
				if(!empty($model->state_id))
				{
					$cities = CHtml::listData(City::model()->findAll(array('condition'=>'status="1" and state_id="'.$model->state_id.'"', 'order'=>'city')),'city_id','city');
				}
				else 
				{
					$cities = array();
				}
				
				if(!empty($model->city_id))
				{
					$localities = CHtml::listData(Locality::model()->findAll(array('condition'=>'status="1" and city_id="'.$model->city_id.'"', 'order'=>'locality')),'locality_id','locality');
				}
				else 
				{
					$localities = array();
				}
			}	
				
		}

		$this->render('create',array(
			'model'=>$model,
			'states'=>$states,
			'cities'=>$cities,
			'localities' => $localities,			
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$states 	= CHtml::listData(State::model()->findAll(array('condition'=>'status="1"','order' => 'state ASC')), 'state_id', 'state');
		$cities 	= CHtml::listData(City::model()->findAll(array('condition'=>'status="1" and state_id="'.$model->state_id.'"', 'order'=>'city')),'city_id','city');
		$localities = CHtml::listData(Locality::model()->findAll(array('condition'=>'status="1" and city_id="'.$model->city_id.'"', 'order'=>'locality')),'locality_id','locality');
		
		if(!is_dir("upload/shop/"))
			 	mkdir("upload/shop/" , 0777,true);
		$prev_image_id = $model->shop_image_id;	 // to store previous image id to delete from ShopImage	
		if(isset($_POST['Shop']))
		{
			$previous_address  = $model->address;
			$previous_locality = $model->locality_id;
			$previous_city  = $model->city_id;
			$previous_state = $model->state_id;
			
			$model->attributes  = $_POST['Shop'];
			$model->category_id = $_POST['Shop']['category_id_autocomplete'];
			$model->owner_id     = $_POST['Shop']['owner_id_autocomplete'];
									
			$address  = $model->name.",  ".$model->address;
			
			if($model->locality_id!=''){
				$address  .= " ".trim($model->locality->locality);
			}if($model->city_id!=''){
				$address  .= " ".trim($model->city->city);
			}if($model->state_id!=''){
				$address  .= " ".trim($model->state->state);
			}
			$address  .= " India";
			
			if($address!='' && ($previous_address!=$model->address || 
								$previous_locality!=$model->locality_id || 
								$previous_city!=$model->city_id ||	
								$previous_state!=$model->state_id )	)
			{
				$geo_data = $this->getLocationGeometry($address,$model->zip_code);
				$model->latitude 	= $geo_data['lat'];
				$model->longitude 	= $geo_data['lng'];
			}		
			
			$model->updated_on 	= time();
			$model->mark_invalid = "0";
			
			$modelObject = CUploadedFile::getInstance($model,'shop_image_id');
			
			if($model->save())
			{				
				if(!empty($modelObject))
				{
					$ext = explode(".",$modelObject->name);
					$image_name = $model->shop_id.".".$ext[1];
					
					$image_path = Yii::app()->basePath . '/../upload/shop/'.$image_name;
					$return = $modelObject->saveAs($image_path);
					
					$shop_image_model = new ShopImage;
					$shop_image_model->shop_id = $model->shop_id;
					$shop_image_model->image = $image_name;
					$shop_image_model->added_on 	= time();
					$shop_image_model->updated_on 	= time();
					
					if($shop_image_model->save())
					{
						$model->updateByPk($model->shop_id,array("shop_image_id"=>$shop_image_model->shop_image_id));
						
						// if image is changed, delete previous image from ShopImage
						if(!empty($prev_image_id)){
							$shop_image_model = new ShopImage;
							$shop_image_model->deleteByPk($prev_image_id);
						}	
						
					}	
					
				}else {
					// if image is removed, delete previous image from ShopImage
					if($prev_image_id != $model->shop_image_id){
						$shop_image_model = new ShopImage;
						$shop_image_model->deleteByPk($prev_image_id);
					}
				}				
							
				$this->redirect(array('view','id'=>$model->shop_id));
			}else{
				if(!empty($model->state_id))
				{
					$cities = CHtml::listData(City::model()->findAll(array('condition'=>'status="1" and state_id="'.$model->state_id.'"', 'order'=>'city')),'city_id','city');
				}
				else 
				{
					$cities = array();
				}
				
				if(!empty($model->city_id))
				{
					$localities = CHtml::listData(Locality::model()->findAll(array('condition'=>'status="1" and city_id="'.$model->city_id.'"', 'order'=>'locality')),'locality_id','locality');
				}
				else 
				{
					$localities = array();
				}
			}	
		}

		$this->render('update',array(
			'model'=>$model,
			'states'=>$states,
			'cities'=>$cities,
			'localities' => $localities,			
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
			$ret = Controller::updateDeletedStatus('Shop',$id);
			if($ret)
			{
				$images_delete = ShopImage::model()->updateAll(array('status'=>'0'),'status="1" and shop_id="'.$id.'"');					
				$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and shop_id="'.$id.'"');					
				$deal_delete = Deal::model()->updateAll(array('status'=>'0'),'status="1" and shop_id="'.$id.'"');					
				$ratings_delete = Rating::model()->updateAll(array('status'=>'0'),'status="1" and shop_id="'.$id.'"');	
				$products = Product::model()->findAll(array('condition'=>'status="1" and shop_id="'.$id.'"' ));
				if(!empty($products))
				{
					$products_in = implode(', ',array_map(function ($object) { return $object->product_id; },$products) );
					$product_delete = Product::model()->updateAll(array('status'=>'0'),'product_id in ('.$products_in.')');					
					$productimages_delete = ProductImage::model()->updateAll(array('status'=>'0'),'product_id in ('.$products_in.')');					
				}
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
		$model=new Shop('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Shop']))
			$model->attributes=$_GET['Shop'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Shop the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Shop::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested shop record has been deleted !!!');			
		}			
		return $model;
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
	
	public function actionAutocompleteCategory()
	{
		$category = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new Category;			
			$resp = $model->findAll(array('condition'=>'category like "%'.$_GET['term'].'%" and status="1" and active_status="S"','select'=>'category_id,category'));
			
			if(!empty($resp))
			{
				$i = 0;
				$category = array();		
				
			 	foreach($resp as $val)
				{
					$category[$i]['id'] = $val['category_id'];
					$category[$i]['label'] = $val['category'];
					$i++;
				}
			}
		}		
		
		echo CJSON::encode($category);
	
	}
	
	public function actionAutocompleteUser()
	{
		$users = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new User;			
			$resp = $model->findAll(array('condition'=>'name like "%'.$_GET['term'].'%" and status="1"  and active_status="S" ','select'=>'user_id,name'));
			
			if(!empty($resp))
			{
				$i = 0;
				$users = array();		
				
			 	foreach($resp as $val)
				{
					$users[$i]['id'] = $val['user_id'];
					$users[$i]['label'] = $val['name'];
					$i++;
				}
			}
		}		
		
		echo CJSON::encode($users);
	
	}
	
	public function actionAutocompleteOwner()
	{
		$users = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new Owner;			
			$resp = $model->findAll(array('condition'=>'name like "%'.$_GET['term'].'%" and status="1" and created_by is null  and active_status="S" ','select'=>'owner_id,name'));
			
			if(!empty($resp))
			{
				$i = 0;
				$users = array();		
				
			 	foreach($resp as $val)
				{
					$users[$i]['id'] = $val['owner_id'];
					$users[$i]['label'] = $val['name'];
					$i++;
				}
			}
		}		
		
		echo CJSON::encode($users);
	
	}
	
	public function actionGetDynamicCity()
	{
		$data = City::model()->findAll(array('condition'=>'state_id="'.$_POST['state_id'].'" and status="1"', 'order'=>'city'));
		
	     $data=CHtml::listData($data,'city_id','city');

		echo CHtml::tag('option',
	                   array('value'=>''),CHtml::encode('-- Select City --'),true);

	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	
	public function actionGetDynamicLocality()
	{
		$data = Locality::model()->findAll(array('condition'=>'city_id="'.$_POST['city_id'].'" and status="1"', 'order'=>'locality'));
		
	    $data = CHtml::listData($data,'locality_id','locality');

		echo CHtml::tag('option',
	                   array('value'=>''),CHtml::encode('-- Select Locality --'),true);

	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
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
	/* 
		Performs activation and deactivation of shops.
	*/
	public function actionSetstatus($id,$active_status)
	{
		$model = $this->loadModel($id);	
		$model->active_status=$active_status;
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/* 
		*For activation and deactivation of multiple records.
	*/
	public function actionchangeStatus()
	{		
		$status = false;		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['shop_id_arr'];
			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Shop',$id_arr);					
				}else 
				{					
					$status = $this->setActiveStatus('Shop',$action,$id_arr,'shop_id');
				}		
			}
		}
		
		if( ($status==true && $status!='update') || $status=='1')
		{
		
			if($action=='S')
			{
				$msg = 'Selected shops have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected shops have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected shops have been deleted successfully !!';
			}
			
			$this->setFlashMessage($msg);
		}
		echo json_encode(array('success'=>$status));		
	}
	
	public function actionUploadData()
	{
		$restaurant_data = Shop::model()->findbypk($_POST['id']);
		$shop_image_id 	 = $restaurant_data['shop_image_id'];
		
		$data = ShopImage::model()->findAll(array('condition'=>'shop_id='.$_POST['id'].' AND status="1" AND shop_image_id!="'.$shop_image_id.'"'));
		$res = '';
		
		// if($_SERVER['REMOTE_ADDR']=='192.168.0.100'){
			// $path = "/php/shopnext/upload/shop/";
			// $del_path = "/php/shopnext/owner/shop/upload/_method/delete/file/";
		// }else{
			// $path = "/shopnext/upload/shop/";
			// $del_path = "/shopnext/owner/shop/upload/_method/delete/file/";
		// }
		
		if(!empty($data))
		{
			$path = "/shopnext/upload/shop/";
			$del_path = "/shopnext/superadmin/shop/upload/_method/delete/file/";
			
			foreach($data as $val)
			{						
				$menu 	   = "";
				$cover 	   = "";
				$shop_image = "";
				$res .= '<tr class="template-download fade in" style="height: 81px;">';
				
				if(file_exists(Yii::app()->basePath.'/../upload/shop/'.$val->image))
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
							<button data-url="'.$del_path.$val->image.'/id/'.$val->shop_image_id.'/model/Shop'.'" data-type="POST" class="btn btn-danger">
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
