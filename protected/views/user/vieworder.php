<?php
	$this->pageTitle = 'My profile';
	$baseUrl = Yii::app()->theme->baseUrl;
	$imageUrl = Yii::app()->baseUrl;
	$imagePath = Yii::app()->basePath; 
	$order_status = array('P'=>'Pending','PR'=>'Processing','I'=>'InTransit','C'=>'Completed','NC'=>'Not Confirm');	
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
				<div class="row margin-bottom-40">
					<div class="col-md-12 col-sm-12">
						<div class="prod_pic">
							<div class="order_title">Orders</div>
						</div>
						<?php if(!empty($order)){ ?>
							<table border="1" width="100%" class="prod_det">
							  <tr>
								<th class="cart_img_col" style="border:none;">Image</th>
								<th class="cart_nm_col">Order No.</th>
								<th class="cart_nm_col">Name</th>
								<th class="cart_qty_col">Quantity</th>
								<th class="cart_price_col">Unit price</th>
								<th class="cart_total_col" >Total</th>
								<th class="cart_total_col" >Status</th>
							  </tr>
							  <?php 
							  $total = 0;
							  foreach($order as $row){ 
								$product_data = Product::model()->findByPk($row->product_id);

								if($product_data->product_image_id!='' && file_exists(Yii::app()->basePath."/../upload/product/".$product_data->productImage->image))
								{ 
									$prod_url = Yii::app()->baseUrl."/upload/product/".$product_data->productImage->image;
								}
								else
								{
									$prod_url = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
								}
							  ?>
							  <tr>
								<td class="cart_img_col">
									<a href="<?php echo Yii::app()->params['SERVER'].'product/detail/'.$product_data->name; ?>"><img src="<?php echo $prod_url; ?>" alt="<?php echo $product_data->name; ?>"></a>
								</td>
								<td class="cart_qty_col">
										<?php echo $row->order_no; ?>
								</td>
								<td class="cart_nm_col">
									<a href="<?php echo Yii::app()->params['SERVER'].'product/detail/'.$product_data->name; ?>"><?php echo $product_data->name; ?></a>
								</td>
								
								<td class="cart_qty_col">
										<?php echo $row->quantity; ?>
									
								</td>
								<td class="cart_price_col">
									<strong><span>Rs. </span><?php echo $row->unit_price; ?></strong>
								</td>
								<td class="cart_total_col">
									<strong><span>Rs. </span><?php echo $row->sub_total; ?></strong>
								</td>
								<td class="cart_total_col">
									<strong><?php echo ($row->order_status) ? $order_status[$row->order_status] : 'Pending'; ?></strong>
								</td>
								
							</tr>
							  <?php $total += $row->sub_total; } ?>
							</table>
									
							<div class="shopping-total1">
							  <ul>
								<li>
								  <span class="total_head">Sub total :</span>
								  <strong class="price"><span class="order_mrg">Rs. <?php echo $total; ?></span></strong>
								</li>
								<li>
								  <span class="total_head">Shipping cost :</span>
								  <strong class="price"><span>Rs. 0.00</span></strong>
								</li>
								<li class="shopping-total-price">
								  <span class="total_head">Total :</span>
								  <strong class="price"><span class="order_mg">Rs. <?php echo $total; ?></span></strong>
								</li>
							  </ul>
							</div>
						<?php }else{ ?>
							<div>Empty</div>
						<?php } ?>
						
					 </div>
				  <!-- END CONTENT -->
				</div>
			</article>
        </aside>
        <aside id="right_side">
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
			<div class="google_ads"><img src="<?php echo $baseUrl; ?>/images/google_ads.jpg"/></div>
		</aside>
	</div>
</div>	

