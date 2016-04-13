<?php
/* @var $this OwnerRoleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Owner Roles',
);

$this->menu=array(
	array('label'=>'Create ownerRole', 'url'=>array('create')),
	array('label'=>'Manage ownerRole', 'url'=>array('admin')),
);
?>

<h1>Owner Roles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
