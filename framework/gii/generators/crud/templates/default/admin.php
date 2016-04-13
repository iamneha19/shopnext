<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

$this->menu=array(
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="col-lg-12">
	<div class="panel panel-default gradient">
		<div class="panel-heading">
			<h4>
				Manage <?php echo $this->pluralize($this->class2name($this->modelClass)); ?>
			</h4>
		</div>
		<div class="operations_ads row">
			<div id="sidebar"  class="operation_potlets">
				<?php echo "<?php
					\$this->beginWidget('zii.widgets.CPortlet');
					\$this->widget('zii.widgets.CMenu', array(
						'items'=>\$this->menu,
						'htmlOptions'=>array('class'=>'operations'),
					));
					\$this->endWidget(); ?>"; ?>
			</div><!-- sidebar -->
		</div>
		<div class="panel-body noPad clearfix">
			<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
				'type'=>'striped bordered condensed hover',
				'itemsCssClass' => 'dynamicTable display table table-bordered dataTable',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>array(
			<?php
			$count=0;
			foreach($this->tableSchema->columns as $column)
			{
				if(++$count==7)
					echo "\t\t/*\n";
				echo "\t\t'".$column->name."',\n";
			}
			if($count>=7)
				echo "\t\t*/\n";
			?>
					array(
						'class'=>'CButtonColumn',
					),
				),
			)); ?>
		</div>
	</div>
</div>
