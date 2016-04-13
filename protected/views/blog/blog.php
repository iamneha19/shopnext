<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			
		</div>
		<div class="panel-body noPad clearfix">
			<?php foreach($list as $comments){ ?>
					<h4><?php echo $comments->title; ?></h4>
					<b>Total Comments:- </b><?php echo $comments->total_comments;?>
					<b><p>Blog Description :-</b>	<?php echo truncate($comments->description,$comments->blog_id); ?></p>
			<?php } ?>
		</div>
	</div>
</div>
<?php 
	function truncate($desc,$id)
	{
		$desc_link='<a href="'.Yii::app()->createUrl('blog/blogcommentslist/'.$id).'"> readmore </a>';
		$count_len = strlen($desc);
		$remove_tag = strip_tags($desc, '');
			if($count_len>25)
			{
				return substr($remove_tag, 0, 25)."... ".$desc_link;
			}else{
				return substr($remove_tag, 0, 25).$desc_link;
			}
	}
?>

