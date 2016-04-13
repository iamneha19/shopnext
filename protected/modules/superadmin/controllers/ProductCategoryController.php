<?php

class ProductCategoryController extends Controller
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
		return array(
			
		);
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
		$model=new ProductCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes = array_map('trim',$_POST['ProductCategory']);
			$model->added_on 	= time();
			$model->updated_on 	= time();
			$model->created_by 	= ApplicationSessions::run()->read('admin_id');
			if($model->save())
				$this->redirect(array('view','id'=>$model->product_category_id));
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes=$_POST['ProductCategory'];
			$model->updated_on 	= time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->product_category_id));
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
		$product = Product::model()->findAll(array('condition'=>'status="1" and product_category_id="'.$id.'"'));
		if(!empty($product))
		{	
			if(!isset($_GET['ajax']))
			{
				Yii::app()->user->setFlash('success', 'Could not process delete request, since product(s) for to this category have been created !');
			}else{
				echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<i class="fa-lg fa fa-warning"> Could not process delete request, since product(s) for to this category have been created ! </i> 
			  </div>';
			 }
		} else {
			$ret = Controller::updateDeletedStatus('ProductCategory',$id);
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
		$model=new ProductCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductCategory']))
			$model->attributes=$_GET['ProductCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ProductCategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ProductCategory::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested product category record has been deleted !!!');			
		}	
		return $model;
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
		$msg = null;
		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['product_category_id_arr'];
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{	
					$product = Product::model()->findAll(array('condition'=>'status="1" and product_category_id IN ('.implode(",",$id_arr).')'));
					if(empty($product))
					{
						$status = $this->deleteMultiple('ProductCategory',$id_arr);
						if($status=='1'){
							$msg = 'Selected categories have been deleted successfully !!';
						}
					}else
					{
						$prod_cat = array_unique(array_map(function($obj){return $obj->product_category_id;},$product));
						$product_category = array_diff($id_arr,$prod_cat);
						if(is_array($product_category) && !empty($product_category))
						{
							$status = $this->deleteMultiple('ProductCategory',$product_category);
							if($status=='1'){
								$msg = 'Selected product categories have been deleted successfully !!! <br> <b>Please Note </b>: Could not process delete request for some product category(s), since product(s) for those have been created.';
							}
						}
					}
										
				}else 
				{	
					$status = $this->setActiveStatus('ProductCategory',$action,$id_arr,'product_category_id');
				}
			}	
		}			
		if($status==true && $status!='update')
		{
			if($action=='S')
			{
				$msg = 'Selected product categories have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected product categories have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected product categories have been deleted successfully !!';
			}
		}
		if(!is_null($msg)){$this->setFlashMessage($msg);}
		echo json_encode(array('success'=>$status));
			
	}
	

	/**
	 * Performs the AJAX validation.
	 * @param ProductCategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
