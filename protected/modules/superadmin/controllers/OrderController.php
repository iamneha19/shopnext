<?php

class OrderController extends Controller
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

	public function actionUpdate($id)
	{
		$total = 0;
		$all_total = 0;
		$discount = 0;
		$i = 1;
		$model=$this->loadModel($id);
		$prev_status = $model->order_status;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$shipping_model = Shipping::model()->find(array('condition'=>'order_no='.$model->order_no));
		if(isset($_POST['Order']))
		{
			$model->attributes = array_map('trim',$_POST['Order']);
			$model->updated_on = time();
			
			if($model->order_status=='P')
			{
				$order_status = "Pending";
			}else if($model->order_status=='PR')
			{
				$order_status = "Processing";
			}else if($model->order_status=='I')
			{
				$order_status = "Intransit";
			}else{
				$order_status = "Completed";
			}
			if($shipping_model->name!='')
			{
				$name = $shipping_model->name;
			}else{
				$name = $shipping_model->email;			
			}
			
			if($model->save())
			{
				if($prev_status != $model->order_status)
				{
					$site_url 	= Yii::app()->params['SITE_URL'];
					$subject 	= 'BASED ON YOUR ORDER STATUS';
					// $body 		= 'Hi <b>'.$name.'</b>, <br/> We have received your order. We will send you an Email and SMS the moment your order items are shipped to your address.<br/> Order Details:- <br/> Item Name: '.$model->product->name.' <br/>Your Order ID: '.$model->order_no.'<br/> Your Current Status: '.$order_status;
					
					$msg = '<!doctype html>
					<html>
						<head>
							<meta charset="utf-8">
							<title>shopnext-emailar</title>
						</head>
						<body style="margin: 0; padding: 0;">
							<table cellpadding="0" cellspacing="0" width="100%" style="width:100%;margin:0 auto;border-top:4px solid #000000;">
							<tbody>
								<tr>
									<td colspan="2" style="border-bottom:2px solid #E8E8E8;">
										<img src="'.Yii::app()->params['SERVER'].'themes/frontendTheme/images/shopnex_logo.jpg"/>
									</td>
								</tr>
								<tr>
									<td align="center" style="padding: 10px 0;">
										<table cellpadding="0" cellspacing="0" width="350px" style="margin:0 auto;">
											<tr>
												<td>
													<img src="'.Yii::app()->params['SERVER'].'themes/frontendTheme/images/Check.png" />
												</td>
												<td style="margin:0 0 0 10px;">
													<p>YOUR ORDER STATUS</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="color:#6E6A6A;font-weight:bold; font-size:14px;">
										<p> Hi '.$name.',<p>
									</td>
								</tr>
								<tr>
									<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">';
									// if($type == 'user'){
										$msg .= '<p> We have received order from you.Please check below details of order :-</p>';
									// }else{
										// $msg .= '<p> Received order from '.ApplicationSessions::run()->read('fullname').'. Below are the details of order :-</p>';
									// }	
										
									$msg .= '</td>
								</tr>
								<tr>
									<td style="color:#9F9F9F;font-weight:normal; font-size:14px;">
										<p> Order ID :<span style="color:#294CED;"> '.$model->order_no.' </span></p>
										
										<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 0 0 0;">
											<tr>
												<td style="vertical-align: top;color:#9F9F9F;font-weight:normal; font-size:14px;">
													<p>Order Placed on: '.date('d-m-Y h:i:s a').'</p>
												</td>
												<td style="float:right;color:#9f9f9f;font-weight:normal;font-size:14px;margin:0;padding:0;line-height:22px;word-break:break-word">
													<span style="color:#6E6A6A; font-size:14px;font-weight:bold;">SHIPPING ADDRESS</span>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
															'.$shipping_model->name.'</p>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">
															'.$shipping_model->address.'</p>
														<p style="color:#9F9F9F;font-weight:normal; font-size:14px;margin:0;padding:0;line-height: 22px;">										
															<span style="color:#294CED;">'.$shipping_model->mobile_no.'</span>
														</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table  cellpadding="0" cellspacing="0" style="width:100%;margin:0 auto;">
											<thead>
												<tr>
													<td colspan="5" style="padding: 15px 0;">
														ORDER DETAILS
													</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>S. No.</td>
													<td>Shop Name</td>
													<td>Item Name</td>
													<td>Quantity</td>
													<td>Price</td>
													<td>Order Status</td>
												</tr>';
											
										$total += $model->sub_total;
										$all_total += ($model->quantity*$model->product->price);
										if(!empty($model->product->discount_price) && $model->product->discount_price != 0.00)
										{
											$discount += ($model->quantity*($model->product->price - $model->product->discount_price));
										}
										
										$msg .= '<tr>
													<td>'.$i.'</td>
													<td><p style="font-size: 14px;">'.$model->shop->name.'</td>
													<td><p style="font-size: 14px;">'.$model->product->name.'</td>
													<td>'.$model->quantity.'</td>
													<td>'.($model->quantity*$model->product->price).'</td>
													<td>'.$order_status.'</td>
													
												</tr>';
												
										$i++;

							$msg .= ' 		</tbody>
										</table>
									</td>
								</tr>
								 <tr>
									<td  align="center" style="padding: 10px 0;">&nbsp;</td>
								 </tr>
								<tr>
									<td  align="center" style="padding: 10px 0;">
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(250, 251, 206);">
											<tr>
												<td colspan="2" style="border-right:1px solid #000000;word-spacing:5px;padding:0 10px;">
													Estimated Dispatch within <span style="font-weight:bold">3 to 4 working days</span> 
												</td>
												<td colspan="3" style="  padding:0 10px;word-spacing:5px;">
													Estimated Delivery by:<span style="font-weight:bold"> 7 working days </span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
											<tr>
												<td></td>
												<td style=" float:right; padding:0 10px;word-spacing:5px;">
													<p style="color:#9F9F9F;line-height:24px;">
														<span style="font-weight:bold;">
														Total Amount : '.$all_total.'</span></br>
														Discount (-) : '.$discount.'
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
								   <td style="padding:10px 0" align="center">
									<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;background:rgb(237, 237, 237);
								  border-bottom: 2px solid rgb(209, 209, 209);">
										<tr>
										 <td style=" float:right; padding:0 10px;word-spacing:5px;">
											   <h1>Payable Amount:	Rs. '.$total.'</h1>
										 </td>
										</tr>
									   </table>
								   </td>
								</tr>
								<tr>
								   <td>
										<table cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;">
											<tr>
												<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
													<p> -- Regards</p>
													<p>Shopnext Team</p>
												</td>
											</tr>
											<tr>
												<td style="color:#6E6A6A;font-weight:normal; font-size:14px;line-height:20px;">
													<!--<p> For any query or assistance, feel free to<a href="#" style="color:#8BD4EF;"> Contact Us</a></p>-->
												</td>
											</tr>
									   </table>
								   </td>
								</tr>
							</tbody>	
							</table>
						</body>
					</html>';	
		$msg = preg_replace( "/\r|\n/", "", $msg );	
					
					$to_email 	= $shipping_model->email;
					$to_name 	= $shipping_model->name;
					$from_email = ApplicationSessions::run()->read('admin_email');
					$this->sendMail($subject,$msg,$to_email,$to_name,$from_email); 
				}	
				
				$this->redirect(array('view','id'=>$model->order_id));
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
			Controller::updateDeletedStatus('Order',$id);
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
		$model=new Order('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Order the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Order::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested order record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Order $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionsetStatus($id,$active_status)
	{
		$model=$this->loadModel($id);
		$model->active_status=$active_status;
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	public function actionchangeStatus()
	{
		$status = false;		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['order_id_arr'];			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Order',$id_arr);					
				}else 
				{					
					$status = $this->setActiveStatus('Order',$action,$id_arr,'order_id');
				}	
			}		
		}		
		if(($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected orders have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected orders have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected orders have been deleted successfully !!';
			}
			
			$this->setFlashMessage($msg);
		}
		echo json_encode(array('success'=>$status));
	}
}
