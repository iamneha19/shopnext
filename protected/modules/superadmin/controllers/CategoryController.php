<?php

class CategoryController extends Controller
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
		$model=new Category;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Category']))
		{
			$model->attributes  = array_map('trim',$_POST['Category']);
			$model->added_on 	= time();
			$model->updated_on 	= time();
			$model->created_by 	= ApplicationSessions::run()->read('admin_id');
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->category_id));
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

		if(isset($_POST['Category']))
		{
			$model->attributes = array_map('trim',$_POST['Category']);
			$model->updated_on 	= time();	
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->category_id));
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
			$shop = Shop::model()->findAll(array('condition'=>'status="1" and category_id="'.$id.'"'));
			if(!empty($shop))
			{
				if(!isset($_GET['ajax']))
				{
					Yii::app()->user->setFlash('success', " Could not process delete request, since shop(s) for to this category have been created !  ");
				}
				else
				{
					echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<i class="fa-lg fa fa-warning"> Could not process delete request, since shop(s) for to this category have been created ! </i> 
					  </div>';
				}
				
			} else 
			{
				$ret = Controller::updateDeletedStatus('Category',$id);
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
		$model=new Category('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Category']))
			$model->attributes=$_GET['Category'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Category the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Category::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested category record has been deleted !!!');			
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
			$id_arr = $_POST['category_id_arr'];
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{	
					$shop = Shop::model()->findAll(array('condition'=>'status="1" and category_id IN ('.implode(",",$id_arr).')'));
					if(empty($shop))
					{
						$status = $this->deleteMultiple('Category',$id_arr);
						if($status=='1'){
							$msg = 'Selected categories have been deleted successfully !!';
						}
					}else
					{
						$shop_cat = array_unique(array_map(function($obj){return $obj->category_id;},$shop));
						$categories = array_diff($id_arr,$shop_cat);
						if(is_array($categories) && !empty($categories))
						{
							$status = $this->deleteMultiple('Category',$categories);
							if($status=='1'){
								$msg = 'Selected categories have been deleted successfully !!! <br> <b>Please Note </b>: Could not process delete request for some category(s), since shop(s) for those have been created.';
							}
						}
					}
										
				}else 
				{	
					$status = $this->setActiveStatus('Category',$action,$id_arr,'category_id');
				}
			}		
		}		
		if($status==true && $status!='update')
		{
			if($action=='S')
			{
				$msg = 'Selected categories have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected categories have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected categories have been deleted successfully !!';
			}
			
		}
		if(!is_null($msg)){$this->setFlashMessage($msg);}
		echo json_encode(array('success'=>$status));			
	}
	

	/**
	 * Performs the AJAX validation.
	 * @param Category $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
