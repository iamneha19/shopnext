<?php
/* @var $this OwnerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Owners',
);

$this->menu=array(
	array('label'=>'Create Owner', 'url'=>array('create')),
	array('label'=>'Manage Owner', 'url'=>array('admin')),
);
?>

<h1>Owners</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
