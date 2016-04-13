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
	'Create',
);\n";
?>

$this->menu=array(
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<div class="col-lg-12">
	<div class="panel panel-default hover">
		<div class="panel-heading">
			<h4>Create <?php echo $this->modelClass; ?></h4>
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
			</div>
		</div>
		<div class="panel-body">
			<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
		</div>
	</div>
</div>
