
<div class="form">
	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'deal-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		)); 
	?>
		<p class="note note-danger">Fields with <span class="required">*</span> are required.</p>
			
		<div class="form-group row">
			<?php echo $form->labelEx($model,'shop_id',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
							<?php $this->widget('ext.MyAutoComplete', array(
					'model'=>$model,
					'attribute'=>'shop_id',
					'htmlOptions'=>array('Placeholder'=>'Type to get list of shops....','class'=>'form-control input-large'),
					'value'=>(!empty($model->shop_id))?$model->shop->name:'',
					'source'=>$this->createUrl('deal/AutocompleteShop'),
					'options'=>array(
							'showAnim'=>'fold',
							'minLength'=>'1',
					),
				)); ?>
				<?php echo $form->error($model,'shop_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'title',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'title',array('class'=>'form-control input-large','size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'desc',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textArea($model,'desc',array('class'=>'form-control input-large','rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'desc'); ?>
			</div>
		</div>

		<!--<div class="form-group row">
			   <?//php echo $form->labelEx($model,'code',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?//php echo $form->textField($model,'code',array('class'=>'form-control input-small','size'=>60,'maxlength'=>100,'readonly'=>'readonly')); ?>
				<?//php echo $form->error($model,'code'); ?>
			</div>
		</div>-->
		<div class="form-group row">
			<?php echo $form->labelEx($model,'validity',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textArea($model,'validity',array('class'=>'form-control input-large','rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'validity'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'start_date',array('class'=>'col-lg-2 control-label')); ?>
				<div class="col-md-4">
					<?php if($model->isNewRecord || empty($model->start_date) || empty($model->end_date)){ ?>
						<div class="input-group input-small date" id="div_start_date">
							<?php echo $form->textField($model,'start_date',array('class'=>'form-control','style'=>'z-index:0!important')); ?>
							<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					<?php }else { ?>
						<?php if((strtotime($model->start_date) >= strtotime("+3 days")) || ($model->start_date = date('d-m-Y',strtotime("+3 days")))){ ?>
							<div class="input-group input-small date" id="div_start_date">
								<?php echo $form->textField($model,'start_date',array('class'=>'form-control','style'=>'z-index:0!important')); ?>
								<span class="input-group-btn">
								<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						<?php }else { ?>
							<div class="input-group input-small">
								<?php echo $form->textField($model,'start_date',array('class'=>'form-control','style'=>'z-index:0!important')); ?>
							</div>
						<?php } ?>
					<?php } ?>
					
					
					<?php echo $form->error($model,'start_date'); ?>
				</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'end_date',array('class'=>'col-lg-2 control-label')); ?>
					<div class="col-md-4">
				<div class="input-group input-small date" id="div_end_date">
									<?php echo $form->textField($model,'end_date',array('class'=>'form-control','style'=>'z-index:0!important')); ?>
									<span class="input-group-btn">
									<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
									</span>
							</div>
				<?php echo $form->error($model,'end_date'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<?php echo $form->labelEx($model,'type',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'type', array('P'=>'Percentage','R'=>'Rupees'), array('empty'=>'-- Select --','class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'type'); ?>
			</div>
		</div>
			
		<div class="form-group row">
			<?php echo $form->labelEx($model,'amount',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->textField($model,'amount', array('class'=>'form-control input-small',)); ?>
				<?php echo $form->error($model,'amount'); ?>				
			</div>
		</div>			

		<div class="form-group row">
			<?php echo $form->labelEx($model,'active_status',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'active_status', array('S'=>'Approved','H'=>'Disapproved'), array('class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'active_status'); ?>
			</div>
		</div>
		<?php if($this->isAccessAllowedFor(Yii::app()->controller->id,'ManageHot')) { 	?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'is_hot_deal',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<?php echo $form->dropDownList($model,'is_hot_deal', array('No'=>'No','Yes'=>'Yes'), array('class'=>'form-control input-small') );?>
				<?php echo $form->error($model,'is_hot_deal'); ?>
			</div>
		</div>
		<?php }	?>
		<?php 
			if($model->deal_image!='' && file_exists(Yii::app()->basePath."/../upload/deal/".$model->deal_image)){ 
				$image 	   = Yii::app()->baseUrl."/upload/deal/".$model->deal_image;
				$deal_img = "fileinput-exists";
			}else{
				$image     = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image";
				$deal_img = "fileinput-new";
			}
		?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'deal_image',array('class'=>'col-lg-2 control-label')); ?>
			<div class="col-lg-10">
				<div class="fileinput <?php echo $deal_img?>" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
						<?php echo CHtml::image("http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image",$model->deal_image) ;?>
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
						<?php echo CHtml::image($image,$model->deal_image) ;?>
					</div>
					<div>
						<span class="btn green btn-file">
						<span class="fileinput-new">Select image </span>
						<span class="fileinput-exists">	Change </span>
							<?php echo $form->fileField($model,'deal_image'); ?>
						</span>
						<a href="#" class="btn blue fileinput-exists" data-dismiss="fileinput">
							Remove 
						</a>					
					</div>
					<?php echo $form->hiddenField($model, 'deal_image'); ?>
				</div>		
			</div>
		</div>		
		<div class="form-group row">
			<div class="col-lg-offset-2 col-md-9">
				<button class="btn btn-default marginR10" type="submit">Save changes</button>
				<button class="btn btn-danger"  type="button" onclick="javascript:window.location.href='<?php echo Yii::app()->baseUrl?>/superadmin/deal/admin'">Cancel</button>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>
<?php 	$cs = Yii::app()->getClientScript();$baseUrl = Yii::app()->theme->baseUrl; ?>
<?php $cs->registerCssFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>
<?php $cs->registerScriptFile($baseUrl.'/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>
<script>
	$(document).ready(function(){
		$('#Deal_start_date').prop("readonly",true);
		$('#Deal_end_date').prop("readonly",true);
		var today = new Date();
		selected_start_date = '<?php echo ($model->start_date) ? $model->start_date : ''; ?>';
		selected_end_date = '<?php echo ($model->end_date) ? $model->end_date : ''; ?>';
		$('#div_start_date').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: '+3d',
			endDate: new Date('12-31-2020')
			}).on('changeDate',function(){
				if($('#Deal_start_date').val() == ''){
					$('#Deal_start_date').val(selected_start_date);
				}else{
					selected_start_date = $('#Deal_start_date').val();
				}
				
				$('#div_start_date').datepicker('update', selected_start_date);
				sdate = $('#Deal_start_date').val().split("-");
				var start_date = new Date(sdate[1]+'-'+sdate[0]+'-'+sdate[2]);
				$('#div_end_date').datepicker('setStartDate', start_date);
				$('#Deal_end_date').val('');
			
			});
		 
		$('#div_end_date').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			startDate: '<?php echo  ($model->start_date) ? $model->start_date : '+3d' ?>',
			endDate: new Date('12-31-2020')
		}).on('changeDate',function(){
			if($('#Deal_end_date').val() == ''){
				$('#Deal_end_date').val(selected_end_date);
			}else{
				selected_end_date = $('#Deal_end_date').val();
			}
			
			$('#div_end_date').datepicker('update', selected_end_date);
			
		
		});
		$('#Deal_shop_id_autocomplete').val('<?php echo $model->shop_id; ?>');
	});
</script>
<?php  /*  script for handling image. */ ?>
<script>
	$('.fileinput').fileinput().on('clear.bs.fileinput',function(){
		$('#Deal_deal_image').val('');
	});
</script>
<script type="text/javascript">
	//<![CDATA[
	
	// This call can be placed at any point after the
	// <textarea>, or inside a <head><script> in a
	// window.onload event handler.
	
	// Replace the <textarea id="editor"> with an CKEditor
	// instance, using default configurations.
	
	var server= 'http://localhost/shopnext';
	
	CKEDITOR.replace( 'Deal_validity',
	{
		filebrowserBrowseUrl :server+'/ckeditor/filemanager/browser/default/browser.html?Connector='+server+'/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserImageBrowseUrl : server+'/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector='+server+'/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserFlashBrowseUrl :server+'/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector='+server+'/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserUploadUrl  :server+'/ckeditor/filemanager/connectors/php/upload.php?Type=File',
		filebrowserImageUploadUrl : server+'/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
		filebrowserFlashUploadUrl : server+'/ckeditor/filemanager/connectors/php/upload.php?Type=Flash',
		height:200,
		width:400
	});
	
	
	
	//]]>
</script>
<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->createUrl('/ckeditor/ckeditor.js')); ?>