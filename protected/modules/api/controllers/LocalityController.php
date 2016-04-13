<?php

class LocalityController extends ApiController
{
	
	/**
	 * List of all localities.
	 * @param 
	 */
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$criteria=new CDbCriteria();
			if(!empty($_REQUEST['city_id']))
			{
				$criteria->compare('city_id',$_REQUEST['city_id']);
			}
			$criteria->select = 'locality_id, locality';
			$criteria->AddCondition('active_status="S" and status=1');
			$localities = Locality::model()->findAll($criteria);
			if(!empty($localities)){
				foreach($localities as $key=>$locality)
				{
					$data[$key]['locality_id']	= $locality->locality_id;
					$data[$key]['locality']		= $locality->locality;
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else if(Locality::model()->findAll(array('condition'=>'status="0" and city_id ='.$_REQUEST['city_id']))) 
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
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
}