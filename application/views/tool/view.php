<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php if(isset($head)) echo $head ?></h1>
	</div>
</div>
<?php 
/*if($this->agent->is_mobile()){
	echo "<br>Mobile";
}else{
	echo "<br>Desktop";
}*/
?>
<div class="row">
	<div class="col-lg-12">
    	<?php 
		if(isset($data)) echo $data;

		if(isset($list_data)){
			$all = count($list_data);

			Debug($list_data);
		}
		
		?>
    </div>
</div>
