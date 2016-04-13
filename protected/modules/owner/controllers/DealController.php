<?php

class DealController extends Controller
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
		return Yii::app()->GetAccessRule->getOwner();
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
		$model=new Deal;
		
        /* $code = $this->getUniqueCode(); // Unique code generation.
        $model->code = $code; */
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Deal']))
		{
			$model->attributes = array_map('trim',$_POST['Deal']);
            $model->shop_id    = $_POST['Deal']['shop_id_autocomplete'];
			$model->added_by = "O";
            $model->added_on   = time();
			$model->updated_on = time();
            $model->start_date = strtotime($model->start_date);
			
            if(!empty($_POST['Deal']['end_date'])){
				$model->end_date = strtotime($model->end_date.' 23:59:59');
            }	
			$modelObject = CUploadedFile::getInstance($model,'deal_image');
			if($model->save()){
				if(!empty($modelObject))
					{
						$ext = explode(".",$modelObject->name);
						$image_name = $model->deal_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/deal/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->deal_id,array("deal_image"=>$image_name));
						}
					}
				$this->redirect(array('view','id'=>$model->deal_id));
            }else{
				$model->start_date=$_POST['Deal']['start_date'];
                $model->end_date=$_POST['Deal']['end_date'];
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
		
        $model->start_date = Controller::dobConvert($model->start_date);
		$model->end_date   = Controller::dobConvert($model->end_date);       

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Deal']))
		{
			
			$model->attributes = array_map('trim',$_POST['Deal']);
			$model->shop_id    = $_POST['Deal']['shop_id_autocomplete'];
            $model->updated_on = time();
            $model->start_date = strtotime($model->start_date);
			$model->end_date   = strtotime($model->end_date.' 23:59:59');
			$modelObject = CUploadedFile::getInstance($model,'deal_image');
			if($model->save()){
				
				if(!empty($modelObject))
					{
						$ext = explode(".",$modelObject->name);
						$image_name = $model->deal_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/deal/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->deal_id,array("deal_image"=>$image_name));
						}
					}
				$this->redirect(array('view','id'=>$model->deal_id));
            }else{
				$model->start_date=$_POST['Deal']['start_date'];
                $model->end_date=$_POST['Deal']['end_date'];
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
			$ret = Controller::updateDeletedStatus('Deal',$id);
			if($ret)
			{
				$comment_delete = Comment::model()->updateAll(array('status'=>'0'),'status="1" and deal_id="'.$id.'" and product_id is null');					
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
		$model=new Deal('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Deal'])) 
		{
			$model->attributes = $_GET['Deal'];
			$model->start_date = strtotime($_GET['Deal']['start_date']);
			if($_GET['Deal']['end_date'] != '')
			{
				$model->end_date=strtotime($_GET['Deal']['end_date'].' 23:59:59');
			}
			
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/* 
		*Performs activation and deactivation of blogcomments.
	*/
	public function actionSetstatus($active_status,$id)
	{
		$model=$this->loadModel($id);
		$model->active_status = $active_status;
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/* 
		*For activation and deactivation of multiple records.
	*/
	public function actionChangeStatus()
	{
		$status = false;
		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['deal_id_arr'];
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Deal',$id_arr);					
				}else 
				{	
					$status = $this->setActiveStatus('Deal',$action,$id_arr,'deal_id');
				}	
			}	
				
		}
		if( ($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected deals have been approved successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected deals have been disapproved successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected deals have been deleted successfully !!';
			}
			
			$this->setFlashMessage($msg);
		}
		
		echo json_encode(array('success'=>$status));		
	}
	        
	public function actionAutocompleteShop()
	{
		if(!empty($_GET['term']))
			$term = $_GET['term'];
		else
			$term = null;
		
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$parent_id = ApplicationSessions::run()->read('parent_id');
		if(empty($parent_id)){
			$user_id = null;
		}else{
			$owner_id = $parent_id;
			$user_id = null;
		}	
		$shop_ids_arr = Controller::getOwnerShopsIds($owner_id,$user_id);
		$ids_string = implode(",",$shop_ids_arr);
		
		 echo Controller::autocompleteOwnerShopJson($term,$ids_string);		
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Deal the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		// find deal only from shops which owner has created.
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$created_by = ApplicationSessions::run()->read('created_by');
		if(empty($created_by)){
			$user_id = null;
		}else{
			$user_id = $owner_id;
			$owner_id = null;
		}	
		$shop_ids = Controller::getOwnerShopsIds($owner_id,$user_id);
		$shop_ids_string = implode(",",$shop_ids);

		$condition = array("condition"=>'shop_id in ('.$shop_ids_string.')');	
	
		$model=Deal::model()->findByPk($id,$condition);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested deal record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Deal $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='deal-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
