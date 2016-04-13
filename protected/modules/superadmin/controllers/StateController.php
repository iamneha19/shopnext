<?php

class StateController extends Controller
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
		$model=new State;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$country = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1"','order'=>'country' )),'country_id','country');
		if(isset($_POST['State']))
		{
			$model->attributes = array_map('trim',$_POST['State']);
			if($model->country_id!='' && $model->state && $model->country->country!='')
			{
				$address = $model->state." ".$model->country->country;
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}	
			}
			$model->added_on   = time();
			$model->updated_on = time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->state_id));
		}

		$this->render('create',array(
			'model'=>$model,
			'country'=>$country,
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
		$country = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1"','order'=>'country' )),'country_id','country');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['State']))
		{
			$country_id_pre = $model->country_id;
			$state_pre = $model->state;
			$model->attributes = array_map('trim',$_POST['State']);
			$model->updated_on = time();			
			if($country_id_pre!=$model->country_id || $state_pre!=$model->state || $model->latitude=='' || $model->longitude=='')
			{
				$address = $model->state." ".$model->country->country;
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->state_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'country'=>$country,
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
			$city = City::model()->findAll(array('condition'=>'status="1" and state_id="'.$id.'"' ));
			if(!empty($city))
			{
				if(!isset($_GET['ajax']))
				{
					Yii::app()->user->setFlash('success', " Could not process delete request, since city(s) for to this state have been created ! ");
				}
				else
				{
					echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<i class="fa-lg fa fa-warning"> Could not process delete request, since city(s) for to this state have been created ! </i> 
					  </div>';
				}
				
			}else	
			{			
				$ret = Controller::updateDeletedStatus('State',$id);
				// $cities = implode(', ',array_map(function ($object) { return $object->state_id; },$city) );
				// if($ret && $cities!='')
				// {
					// $cities_update = City::model()->updateAll(array('status'=>'0'),'city_id in ('.$cities.')');	
					// $localities = implode(', ',array_map(function ($object) { return $object->locality_id; },Locality::model()->findAll(array('condition'=>'status="1" and city_id in ('.$cities.')' ))) );
					// if($localities!='')
					// {
						// $localities_update = Locality::model()->updateAll(array('status'=>'0'),'locality_id in ('.$localities.')');	
						// $invalid_remarks = "Shop address detail is marked as invalid, since state (hence city & locality) for the same is being deleted by Superadmin : ".ApplicationSessions::run()->read('admin_name')." on ".date('l, jS F Y')." at ".date('h:i A.');
						// $shop_update = Shop::model()->updateAll(array('mark_invalid'=>'1','invalid_remarks'=>$invalid_remarks),'locality_id in ('.$localities.')');	
					// }			
				// }	
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
		$model=new State('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['State']))
			$model->attributes=$_GET['State'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return State the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=State::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested state record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param State $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='state-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}