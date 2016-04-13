<?php

class LocalityController extends Controller
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
		$model = new Locality;
		$countries = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'country' )),'country_id','country');
		
		if(!empty($_POST['Locality']['country_id']))
		{
			$states = CHtml::listData(State::model()->findAll(array('condition'=>'country_id = "'.$_POST['Locality']['country_id'].'" and status="1" and active_status="S"','order'=>'state' )),'state_id','state');
		}
		else 
		{
			$states = array();
		}
		if(!empty($_POST['Locality']['state_id']))
		{
			$cities = CHtml::listData(City::model()->findAll(array('condition'=>'state_id = "'.$_POST['Locality']['state_id'].'" and status="1" and active_status="S"','order'=>'city' )),'city_id','city');
		}
		else 
		{
			$cities = array();
		}
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Locality']))
		{
			$model->attributes=array_map('trim',$_POST['Locality']);
			$model->added_on = time();
			$model->updated_on = time();
			if($model->city_id!='' && $model->locality!='')
			{
				$address = trim($model->locality)." ".trim($model->city->city)." ".trim($model->city->state->state)." ".trim($model->city->state->country->country);
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->locality_id));
		}

		$this->render('create',array(
			'model'=>$model,
			'cities'=>$cities,
			'countries'=>$countries,
			'states'=>$states,
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
		$model->country_id = $model->city->state->country_id;
		$model->state_id = $model->city->state_id;
		
		$countries = CHtml::listData(Country::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'country' )),'country_id','country');
		$states = CHtml::listData(State::model()->findAll(array('condition'=>'country_id="'.$model->country_id.'" and status="1" and active_status="S"','order'=>'state' )),'state_id','state');
		$cities = CHtml::listData(City::model()->findAll(array('condition'=>'status="1" and state_id="'.$model->state_id.'" and active_status="S"','order'=>'city' )),'city_id','city');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Locality']))
		{
			$city_pre = $model->city_id;
			$locality_pre = $model->locality;
			$model->attributes=array_map('trim',$_POST['Locality']);
			$model->updated_on = time();
			if($city_pre != $model->city_id || $locality_pre != $model->locality || $model->latitude=='' || $model->longitude=='')
			{
				$address = trim($model->locality)." ".trim($model->city->city)." ".trim($model->city->state->state)." ".trim($model->city->state->country->country);
				$geo_data = $this->getLocationGeometry($address);
				if($geo_data['lat']!='' && $geo_data['lng']!='')
				{
					$model->latitude 	= $geo_data['lat'];
					$model->longitude 	= $geo_data['lng'];
				}
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->locality_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'cities'=>$cities,
			'countries'=>$countries,
			'states'=>$states,
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
			$shop = Shop::model()->findAll(array('condition'=>'status="1" and locality_id="'.$id.'"' ));
			if(!empty($shop))
			{
				if(!isset($_GET['ajax']))
				{
					Yii::app()->user->setFlash('success', "Could not process delete request, since shop(s) for to this locality have been created ! ");
				}
				else
				{
					echo '<div id="statusMsg" class="Metronic-alerts alert alert-success fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<i class="fa-lg fa fa-warning"> Could not process delete request, since shop(s) for to this locality have been created ! </i> 
					  </div>';
				}
				
			}else
			{
				$ret = Controller::updateDeletedStatus('Locality',$id);
				//$shops = implode(', ',array_map(function ($object) { return $object->shop_id; },$shop) );
				// if($ret && $shops!='')
				// {		
					// $invalid_remarks = "Shop address detail is marked as invalid, since locality for the same is being deleted by Superadmin : ".ApplicationSessions::run()->read('admin_name')." on ".date('l, jS F Y')." at ".date('h:i A.');
					// $shop_update = Shop::model()->updateAll(array('mark_invalid'=>'1','invalid_remarks'=>$invalid_remarks),'locality_id="'.$id.'"');					
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
		$model=new Locality('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Locality']))
			$model->attributes=$_GET['Locality'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Locality the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Locality::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested locality record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Locality $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='locality-form')
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
	/*Garima
	*@actionGetDynamicCity
	*/
	public function actionGetDynamicCity()
	{
		$data = City::model()->findAll(array('condition'=>'state_id="'.$_POST['state_id'].'" and status="1"', 'order'=>'city'));
		
	    $data = CHtml::listData($data,'city_id','city');
		if(!empty($data))
		{
			echo CHtml::tag('option',array('value'=>''),CHtml::encode('-- Select City --'),true);

			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
			}
		}else{
			echo CHtml::tag('option',array('value'=>''),CHtml::encode('-- No city found --'),true);
		}
		
	}
}
