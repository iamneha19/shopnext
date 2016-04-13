<?php
$this->layout = false;	
	if(isset($blogs) && !empty($blogs)){
		$this->widget('zii.widgets.CListView', array(
							// 'id'=>'site-list',
							'dataProvider'=>$blogs,
							'itemView'=>'webroot.protected.views.blog.latest_blogs',
							'ajaxUpdate'=>true,
							'enablePagination'=>false,
							'template'=>'{items}',
				));
	}?>