<?php

class CityController extends ApiController
{
	/* 
		*List of all cities.
	*/
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			if(!empty($_REQUEST['state_id']))
			{
				$criteria->compare('state_id',$_REQUEST['state_id']);
			}
			$criteria->select = 'city_id,city';
			$criteria->AddCondition('active_status="S" and status=1');
			$cities = City::model()->findAll($criteria);
			if(!empty($cities)){
				
				foreach($cities as $key=>$city)
				{
					$data[$key]['city_id']		= $city->city_id;
					$data[$key]['city']		= $city->city;
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else if(City::model()->findAll(array('condition'=>'status="0" and state_id ='.$_REQUEST['state_id']))) 
			{
				// if record is deleted.
				$resp_code = $this->status_code['RECORD_DELETED'];
				$resp = array('code'=>$resp_code);
				
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
}