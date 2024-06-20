<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php //echo $head ?></h1>
		<?php 
    	$datenow = date('Y-m-d');
    	//echo base_url('data/xml/'.$datenow); 
    	?>
	</div>
<?php
$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
echo form_open_multipart('xml/savefile', $attributes);
?>
			<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> View files</label>
				<div class="col-sm-9">
					<?php echo anchor(base_url('data/xml/'.$datenow), base_url('data/xml/'.$datenow), array('target' => '_blank')); ?>
				</div>
			</div>

			<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> File name</label>
				<div class="col-sm-9">
					<input type="text" class="col-xs-10 form-control limited" placeholder="" id="filename" name="filename" maxlength="140" value="" autofocus />				
				</div>
			</div>

			<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> URL</label>
				<div class="col-sm-9">
					<input type="text" class="col-xs-10 form-control limited" placeholder="" id="url" name="url" maxlength="140" value="" />
					<span class="middle">
					<code>* Require</code>
					</span>
				</div>
			</div>

			<div style="clear: both;"></div>
			<div class="clearfix form-actions">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
						<i class="ace-icon fa fa-check bigger-110"></i>
						Save
					</button>
					&nbsp; &nbsp; &nbsp;
					<button type="reset" class="btn">
						<i class="ace-icon fa fa-undo bigger-110"></i>
						Reset
					</button>
				</div>
			</div>

<?php echo form_close();?>			
</div>
