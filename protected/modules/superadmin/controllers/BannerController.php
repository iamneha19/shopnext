<?php
Yii::import("xupload.models.XUploadForm");

class BannerController extends Controller
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
							'path' 			=> Yii::app()-> getBasePath() . "/../upload/banner", 
							'publicPath' 	=> Yii::app()->getBaseUrl()."/upload/banner" , 
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
		$model=new Banner;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Banner']))
		{
			if(!is_dir("upload/banner/"))
			 	mkdir("upload/banner/" , 0777,true);
			$model->attributes = $_POST['Banner'];			
			$model->added_on 	= time();
			$model->updated_on 	= time();
			
			
			$uploadedFile = CUploadedFile::getInstance($model,'banner');
			if(!empty($uploadedFile)){
				$model->banner 	= $uploadedFile->name;
			}
			
			// echo"<pre>";
			// print_r($uploadedFile);
			// exit();
			if($model->save())
			{
				// print"<pre>";print_r($model);exit;
				if(!empty($uploadedFile))
					{
						// $fileName = $uploadedFile;
						// $model->banner = $fileName; 

						 $ext = explode(".",$uploadedFile->name);
						 $image_name = $model->banner_id.".".$ext[1];
						 $image_path = Yii::app()->basePath . '/../upload/banner/'.$image_name;
							
						if($uploadedFile->saveAs($image_path))
						  {
							 $model->updateByPk($model->banner_id,array("banner"=>$image_name));
						  }
					}
					// $image_path = Yii::app()->basePath . '/../upload/banner/'.$fileName;
					// $return = $uploadedFile->saveAs($image_path);
					$this->redirect(array('view','id'=>$model->banner_id));
			}else{
				$model->banner 	= '';
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
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Banner']))
		{
			if(!is_dir("upload/banner/"))
			 	mkdir("upload/banner/" , 0777,true);
			$model->attributes = $_POST['Banner'];			
			$model->updated_on 	= time();
			
			$modelObject = CUploadedFile::getInstance($model,'banner');			
			// echo"<pre>";
			// print_r($modelObject);
			// exit;
			if($model->save())
			{				
				if(!empty($modelObject))
				{
					// echo"<pre>";
					// print_r($modelObject);
					// exit;
					$ext = explode(".",$modelObject->name);
					$image_name = $model->banner_id.".".$ext[1];
					
					$image_path = Yii::app()->basePath . '/../upload/banner/'.$image_name;
					
					if($modelObject->saveAs($image_path))
					{
						$model->updateByPk($model->banner_id,array("banner"=>$image_name));
					}		
					
				}else {
					$fileName = $_POST['Banner']['banner'];
				}				
							
				$this->redirect(array('view','id'=>$model->banner_id));
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
			Controller::updateDeletedStatus('Banner',$id);
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
	public function actionManageImages($id)
	{	
		$model = $this->loadModel($id);		
		$multiple_image_model = new XUploadForm;
		
		$this->render('manage_images',array(
			'model'=>$model,		
			'multiple_image_model'=>$multiple_image_model,		
		));
	}
	
	public function actionAdmin()
	{
		$model=new Banner('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Banner']))
			$model->attributes=$_GET['Banner'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Banner the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Banner::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested banner record has been deleted !!!');			
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Banner $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='banner-form')
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
			$id_arr = $_POST['banner_id_arr'];
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Banner',$id_arr);					
				}else 
				{					
					$status = $this->setActiveStatus('Banner',$action,$id_arr,'banner_id');
				}		
			}		
		}
		if( ($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected banners have been activated successfully !!';
			}else if($action=='H' )
			{
				$msg = 'Selected banners have been deactivated successfully !!';
			}else if($action=='D' )
			{
				$msg = 'Selected banners have been deleted successfully !!';
			}
			$this->setFlashMessage($msg);	
		}
		echo json_encode(array('success'=>$status));
	}
	
	
	
	  // public function actionUploadData()
	 // {
		 // $shop_data = Banner::model()->findbypk($_POST['id']);
		 // $banner 	 = $shop_data['banner_id'];
		
		 // $data = Banner::model()->findAll(array('condition'=>'banner_id='.$_POST['id'].' AND status="1" AND banner_id!="'.$banner_id.'"'));
		 // $res = '';
		
		 // if(!empty($data))
		 // {
			 // if($_SERVER['REMOTE_ADDR']=='192.168.0.100'){
				 // $path = "/php/shopnext/upload/banner/";
				 // $del_path = "/php/shopnext/superadmin/banner/upload/_method/delete/file/";
			 // }else{
				 // $path = "/shopnext/upload/banner/";
				 // $del_path = "/shopnext/superadmin/banner/upload/_method/delete/file/";
			 // }
			 // foreach($data as $val)
			 // {						
				 // $menu 	   = "";
				 // $cover 	   = "";
				 // $shop_image = "";
				 // $res .= '<tr class="template-download fade in" style="height: 81px;">';
				
				 // if(file_exists(Yii::app()->basePath.'/../upload/banner/'.$val->image))
				 // {				
					 // $res .=	'							
							 // <td class="preview">
								 // <span class="preview">
								 // <a download="'.$val->image.'" rel="gallery" title="'.$val->image.'" href="'.$path.$val->image.'">
									 // <img src="'.$path.$val->image.'" width="80" height="60"></a>
								 // </span>
							 // </td>
							 // <td class="name">
								 // <a download="'.$val->image.'" rel="gallery" title="'.$val->image.'" href="'.$path.$val->image.'">'.$val->image.'</a>
							 // </td>';
				 // }
				 // else
				 // {
					 // $res .=	'							
							 // <td class="preview">
								 // <img src="'.$path.$val->image.'" width="80" height="60">
							 // </td>
							 // <td class="name">'.$val->image.'</td>';
				 // }
				
				 // $res .= '						
						 // <td colspan="2">&nbsp;</td>
						 // <td class="delete">
							 // <button data-url="'.$del_path.$val->image.'/id/'.$val->banner_id.'/model/banner'.'" data-type="POST" class="btn btn-danger">
								 // <i class="icon-trash icon-white"></i>
								 // <span>Delete</span>
							 // </button>
							 // <input type="checkbox" name="delete" value="1">
						 // </td>
						 // </tr>';
			 // }
		 // }
		
		 // echo $res;
	 // }
}
