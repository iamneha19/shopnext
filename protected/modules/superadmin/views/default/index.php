<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<?php
	$role_type = ApplicationSessions::run()->read('role_name');
?>
<h1><center>Welcome To Shopnext <?php echo ucfirst($role_type)?></center></h1>
