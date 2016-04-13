<?php
	$this->pageTitle = 'My profile';
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 
	$order_status = array('P'=>'Pending','PR'=>'Processing','I'=>'InTransit','C'=>'Completed','NC'=>'Not Completed');	
?>

<div id="wrapper">
	<div id="main_div">
	   <aside id="left_side">
				<?php foreach($banners as $banner){ 
					if($banner->type == 'I')
					{
						$banner_img = $imagePath."/../upload/banner/".$banner->banner;
						if($banner->banner!='' && file_exists($banner_img)){
						?>
							<div class="local_ads"><img src="<?php echo $imageUrl."/upload/banner/".$banner->banner; ?>"/></div>
						<?php 
						}else
						{ ?>
							<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image"/></div>
						<?php 	
						} 
					}
					else 
					{ ?>
						<div class="local_ads"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=code+image"/></div>	
					<?php 
					} 
				} ?>
		</aside>
		<aside id="center_div">
			<article class="products">
			<div class="prod_pic">
			 <div class="order_title">Order History</div></div>
				<?php if(!empty($orders)){ ?>
					<table border="1" width="100%" class="prod_det">
						<thead class="head_col">
						  <th class="order_col"> Order No </th>
						  <th class="total_col">   Total </th>
						   <th class="nofprod_col">  No of products </th>
						</thead>
						<tbody>
					<?php foreach($orders as $order){ ?>
					
							<tr>
								<td class="order_col">
								<a href='<?php echo Yii::app()->params['SERVER'].'user/vieworder?order_no='.$order->order_no; ?>'><?php echo $order->order_no; ?></a>
								</td>
								<td class="total_col">
								<?php echo $order->total_price; ?>
								</td>
								<td class="nofprod_col">
								<?php echo $order->product_count; ?>
								</td>
							</tr>
					<?php } ?>
					</tbody>
					</table>
					<?php $this->widget('CLinkPager', array(
						'pages' => $pages,
					)) ?>
				<?php }else { ?>
					<div>No order history found!</div>
				<?php } ?>
				
			</article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	</div>
</div>	

