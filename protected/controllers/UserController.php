<?php

class UserController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		$this->render('index');
	}

	public function actionMyProfile()
	{
		$user_id = $this->user_id;
		$model = $this->loadModel($user_id);
		$model->scenario = 'update_myprofile';
		
		$states = CHtml::listData(State::model()->findAll(array('condition'=>'status="1"','order' => 'state')), 'state_id', 'state');
		
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
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
		if(isset($_POST['ajax']) && $_POST['ajax']==='myprofile-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}	
		if(isset($_POST['User']))
		{
			$model->attributes = array_map('trim',$_POST['User']);
			$model->updated_on = time();
			
			if(isset($_POST['User']['dob']) && $_POST['User']['dob']!=''){
				$model->dob = strtotime($_POST['User']['dob']);
			}else{
				$model->dob = null;
			}
			if($model->save()){
				$this->setFlashMessage('Your profile has been updated successfully !!','user_msg');
				$this->redirect('myprofile');
			}else{
				$this->setFlashMessage('An error occurred while update, Please try again !!','user_msg');
			}
		}
		
		if(isset($model->dob) && $model->dob!='0' && $model->dob!=''){
			 $model->dob = Controller::dateFromTimestamp($model->dob,'d-m-Y');
		}else{
			 $model->dob = null;
		}
		$this->render('myprofile',array(
				'model'=>$model,
				'states'=>$states,
				'cities'=>$cities,
				'localities' => $localities,
				'banners' => $banners,
			));			
	}
	
	public function actionProfile()
	{
		$user_id = $this->user_id;
		$model = $this->loadModel($user_id);
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
		
		$this->render('profile',array('user' => $model,'banners'=>$banners));			
	}
	
	public function actionOrders()
	{
		$user_id = $this->user_id;
		// $orders  = Yii::app()->db->createCommand()
				// ->select('*,sum(sub_total) as total,count(order_no) as count')
				// ->from('order')
				// ->where('order_status!="NC" and status="1" and active_status="S" and user_id='.$user_id)
				// ->group('order_no')
				// ->order('order_no DESC')
				// ->queryAll();
				
		$criteria=new CDbCriteria();
		$criteria->select = '*,sum(sub_total) as total_price,count(order_no) as product_count';
		$criteria->condition = 'order_status!="NC" and status="1" and active_status="S" and user_id='.$user_id;
		$criteria->group = 'order_no';
		$criteria->order = 'order_no DESC';
		$count=Order::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize=10;
		$pages->applyLimit($criteria);
		$models=Order::model()->findAll($criteria);
		
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
		$this->render('orders', array(
			'orders' => $models,
			 'pages' => $pages,
			 'banners'=>$banners
		));
		
	}
	
	public function actionViewOrder($order_no)
	{
		$order = Order::model()->findAll(array('condition'=>'order_status!="NC" and status="1" and active_status="S" and order_no='.$order_no));
		if(isset($order[0]->user_id) && ($order[0]->user_id != $this->user_id))
		{
			$this->redirect('orders');
		}	
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));
		$this->render('vieworder',array('order' => $order,'banners'=>$banners));			
	}
	
	public function actionChangePassword()
	{	
		$user_id = $this->user_id;
		$model = $this->loadModel($user_id);
		
		if(trim($model->password)=='' && $model->register_type!='Registration'){
			$model->scenario = 'change_password_scene2';
		}else{
			$model->scenario = 'change_password_scene1';
		}	
		
		if(isset($_POST['ajax']) && $_POST['ajax']==='changepass-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>4));		
		if(isset($_POST['User']))
		{
			$model->attributes = array_map('trim',$_POST['User']);
			$model->password = md5(trim($model->repeat_password));
			$model->updated_on = time();
			if($model->save()){
				$subject = 'Password changed successfully - Shopnext !!!';
				$body = "Dear <b>".$model->name."</b>,
						<br/>Greetings !!!<br/>
						This is to inform that your password has been successfully changed.
						<br/>
						Action performed at <b>".date('h:i A')." on ".date('l, jS F Y.')."</b>
						<br/>
						Kindly take a note of it.
						<br/>
						<b>-Shopnext Team.</b>
						<br/><br/>
						";
				$this->sendMail($subject,$body,$model->email,$model->name);				
				$this->setFlashMessage('Your password is updated successfully !!','user_msg');
			}else{
				$this->setFlashMessage('An error occurred while update, Please try again !!','user_msg');
			}
			
		}
		
		$this->render('change_password',array(
				'model'=>$model,
				'banners'=>$banners,
			));	
		
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
	
	public function actionChangeImage()
	{
		$user_id = $this->user_id;
		$model = $this->loadModel($user_id);
		
		if(isset($_POST['User']))
		 {
			 $modelObject = CUploadedFile::getInstance($model,'profile_pic');
			 if(!empty($modelObject))
			  {
				 $ext = explode(".",$modelObject->name);
				 $image_name = $model->user_id.".".$ext[1];
				 $image_path = Yii::app()->basePath . '/../upload/user/'.$image_name;
					
				  if($modelObject->saveAs($image_path))
				  {
					 $model->updateByPk($model->user_id,array("profile_pic"=>$image_name));
					 ApplicationSessions::run()->write('profile_pic',Yii::app()->baseUrl.'/upload/user/'.$image_name);
					 print json_encode(array('success'=>true,'img'=>Yii::app()->baseUrl.'/upload/user/'.$image_name));
				  }else{
					  print json_encode(array('success'=>false));
				  }  
			 }else{
					print json_encode(array('success'=>false));
			 }	 
			
		 }			
	}
	
	public function beforeAction($action) 
	{
		parent::beforeAction($action);
		
		if(Controller::loggedInStatus())
		{
			return true;
		} else 
		{	
			Yii::app()->user->setFlash('login_required', "Please login to continue action!");
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}