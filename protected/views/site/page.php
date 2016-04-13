<?php  	
	$this->layout = false;	
	if(isset($deals) && !empty($deals)){	
		$this->widget('zii.widgets.CListView', array(
					// 'id'=>'site-list',
					'dataProvider'=>$deals,
					'itemView'=>'webroot.protected.views.site.latest_deals',
					'ajaxUpdate'=>true,
					'enablePagination'=>false,
					'template'=>'{items}',
		));
	}
	if(isset($shops) && !empty($shops)){
			$this->widget('zii.widgets.CListView', array(
					// 'id'=>'shop-list',
					'dataProvider'=>$shops,
					'itemView'=>'webroot.protected.views.site.latest_shops',
					'ajaxUpdate'=>true,
					'enablePagination'=>false,
					'template'=>'{items}',
			));
		}
?>
