<?php

class CityController extends Controller
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
		$model = new City;
		$countries = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1"','order'=>'country' )),'country_id','country');
		if(!empty($_POST['City']['country_id']))
		{
			$state = CHtml::listData(State::model()->findAll(array('condition'=>'country_id = "'.$_POST['City']['country_id'].'" and status="1"','order'=>'state' )),'state_id','state');
		}
		else 
		{
			$state = array();
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['City']))
		{
			$model->attributes = array_map('trim',$_POST['City']);
			$model->added_on   = time();
			$model->updated_on = time();
			if($model->state_id!='' && $model->city!='')
			{
				$address = trim($model->city)." ".trim($model->state->state)." ".trim($model->state->country->country);
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}	
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->city_id));
		}			
		
		
		$this->render('create',array(
			'model'=>$model,
			'countries'=>$countries,
			'state'=>$state,
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
		$model->country_id = $model->state->country_id;
		
		$countries = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'country' )),'country_id','country');
		$state = CHtml::listData(State::model()->findAll(array('condition'=>'country_id="'.$model->country_id.'" and status="1" and active_status="S"','order'=>'state' )),'state_id','state');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['City']))
		{
			$city_pre = $model->city;
			$state_pre = $model->state_id;
			$model->attributes = array_map('trim',$_POST['City']);
			$model->updated_on = time();
			if($city_pre!=$model->city || $state_pre!=$model->state_id || $model->latitude=='' || $model->longitude=='')
			{
				$address = trim($model->city)." ".trim($model->state->state)." ".trim($model->state->country->country);
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}	
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->city_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'state'=>$state,
			'countries'=>$countries,
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
			$locality = Locality::model()->findAll(array('condition'=>'status="1" and city_id="'.$id.'"' ));
			if(!empty($locality))
			{
				if(!isset($_GET['ajax']))
				{
					Yii::app()->user->setFlash('success', " Could not process delete request, since locality(s) for to this city have been created !");
				}
				else
				{
					echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<i class="fa-lg fa fa-warning"> Could not process delete request, since locality(s) for to this city have been created ! </i> 
					  </div>';
				}
				
			}else
			{
				$ret = Controller::updateDeletedStatus('City',$id);
				//$localities = implode(', ',array_map(function ($object) { return $object->locality_id; },$locality) );
				// if($ret && $localities!='')
				// {						
					// $localities_update = Locality::model()->updateAll(array('status'=>'0'),'locality_id in ('.$localities.')');	
					// $invalid_remarks = "Shop address detail is marked as invalid, since city (hence locality) for the same is being deleted by Superadmin : ".ApplicationSessions::run()->read('admin_name')." on ".date('l, jS F Y')." at ".date('h:i A.');
					// $shop_update = Shop::model()->updateAll(array('mark_invalid'=>'1','invalid_remarks'=>$invalid_remarks),'locality_id in ('.$localities.')');	
				// }	
			}
		}
		catch(CDbException $e)
		{
			echo "Please try after some time.";
		}
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
		$model=new City('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['City']))
			$model->attributes=$_GET['City'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return City the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=City::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested city record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param City $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='city-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/*Garima
	*@actionGetDynamicState
	*/
	public function actionGetDynamicState()
	{
		$data = State::model()->findAll(array('condition'=>'country_id="'.$_POST['country_id'].'" and status="1"', 'order'=>'state'));
		
	    $data = CHtml::listData($data,'state_id','state');
		if(!empty($data))
		{
			echo CHtml::tag('option',array('value'=>''),CHtml::encode('-- Select State --'),true);
			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
			}
		}else{
			echo CHtml::tag('option',array('value'=>''),CHtml::encode('-- No states found --'),true);
		}
		
	}
}
