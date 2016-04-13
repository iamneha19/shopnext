<?php
$current_location = "";
if($geodata['city']!='' && $geodata['state']!='') {
	$current_location = $geodata['city'].", ".$geodata['state'];
}
?>

<h3>	
	<?php if($current_location!='') {?>
		Shops / stores near <?php echo $current_location;?> :
	<?php } else { print "Oops..... your current location could not be traced !!";}?>	
</h3>

<div class="span12" >

	<?php if(!empty($model)) {?>
	<?php foreach($model as $obj) {?>
		<div class="span3" style="padding-bottom:30px;">
			<div class="thumbnail">
				<?php
					if($obj->shop_image_id!=''){ 
						$image 	   = Yii::app()->baseUrl."/upload/shop/".$obj->shopImage->image;	
					}else{
						$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";	
					}					
				?>
				<img data-src="holder.js/300x200" alt="300x200" src="<?php echo $image;?>" style="width: 300px; height: 200px;">
				<div class="caption">
					<h4><?php echo $obj->name;?></h4>			
					<p>
					<?php			 
						$this->widget('CStarRating',array(
							'name'	  =>'ratings-'.$obj->shop_id,
							'value'	  =>$obj->rating,
							'readOnly'=>true,
							'minRating'=>1,
							'maxRating'=>5,
							'starCount'=>5,
						  ));
						echo "<div id='ratings-'".$obj->shop_id."></div>";					
					?>
					</p><br>
					<p><strong>Comments : </strong><?php echo ($obj->total_comments) ? $obj->total_comments : 0; ?></p>
					<p>
						<strong>Address : </strong>
						<i><?php echo Controller::formatedAddress($obj->name,$obj->address,$obj->zip_code,$obj->city->city,$obj->locality->locality);?></i>
					</p>
					<p><a href="<?php echo Yii::app()->createUrl("shop/shopdetails",array("id"=>$obj->shop_id)) ?>" class="btn btn-success">View more details</a> </p>
				</div>
			</div>		
		</div>		
	<?php } }else{print "No shops/stores found !!"; }?>

</div>