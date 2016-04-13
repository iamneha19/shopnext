<?php $categories = $this->getCategory();?>
<?php $abc = Shop::model()->findAll(array('condition'=>'status="1" and active_status="S"'));
// print_r($abc);exit;
 ?>
<nav id="nav">
	<div id="nav_container">
		<ul id="menu">
			<li>CATEGORIES
				<ul id="submenu" class="mCustomScrollbar">
					<?php foreach($categories as $row) {?>
						 <li><a href='<?php echo Yii::app()->params['SERVER']; ?>shop/category/<?php echo $row->category; ?>'><?php echo $row->category;?></a></li>
					<?php } ?>
				</ul>
			</li>
		</ul>
		
		<div class="nav_links lb"><a href="<?php echo Yii::app()->params['SERVER']; ?>">Deals</a></div> 
		<!--<div class="nav_links lb"><a href="#">Contact Us</a></div>
		<div class="nav_links "><a href="#">Site Map</a></div> 
		<div class="nav_links lb"><a href="<?php echo Yii::app()->params['SERVER']; ?>blog/BlogView">Blogs</a></div>-->
		<div class="clr"></div>
	</div>
</nav>
