<?php
class StateController extends ApiController
{
	/* 
		*list of all states.
	 */
	public function actionList()
	{
		$resp_code = $this->validateRequest();
		if($resp_code == '200')
		{
			$criteria = new CDbCriteria();
			$criteria->select = 'state_id,state';
			$criteria->AddCondition('active_status = "S" and status="1"');
			$states   = State::model()->findAll($criteria);
			if(!empty($states))
			{
				foreach($states as $key=>$state)
				{
					$data[$key]['state_id'] = $state->state_id;
					$data[$key]['state'] = $state->state;
				}
				$resp = array('code'=>$resp_code,'data'=>$data);
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