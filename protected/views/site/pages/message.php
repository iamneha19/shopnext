<h1>Shopnext</h1>

<?php if(Yii::app()->user->hasFlash('msg')): ?>
 
<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('msg'); ?>
</div>
 
<?php endif; ?>