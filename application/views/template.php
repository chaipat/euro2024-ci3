<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title> View Data <?php echo (isset($webtitle)) ? $webtitle : ''; ?></title>
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- bootstrap & fontawesome -->
<?php

  if($this->uri->segment(1) == 'xml')
    echo css_asset('bootstrap.min.css');
  else
    echo css_asset('bootstrap.min.css', 'bootstrap-5.2.2');
?>
<style>
  .left{text-align:left;}
  .right{text-align:right;}
  .center{text-align:center;}
  .red{color:red;}
  .green{color:green;}
  .blue{color:blue;}
  .orange{color:orange;}
</style>
<!-- ace settings handler -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.min.js'>"+"<"+"/script>");
</script>
<?php
    //echo js_asset('jquery-3.1.0.min.js'); 
    $breadcrumb_url = '';
?>
</head>
<body>
<head>

</head>

<main class="main-container" id="main-container">
  
  <div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
      
      <!-- <ul class="breadcrumb">
        <li> 
          <i class="ace-icon fa fa-home home-icon"></i> 
            <a href="<?php echo base_url(); ?>dashboard/index/<?php if(isset($adminid)){ echo $adminid; }?>">Home</a>
          </li>
<?php if(isset($breadcrumb)){
					foreach($breadcrumb as $b){	
						if(isset($b["url"]) != ""){
							$breadcrumb_url="<a href=\"".$b["url"]."\">".$b["title"]."</a>";	
						}else{
              if(isset($b["title"]))
							  $breadcrumb_url = $b["title"];	
						}
?>
        <li class="active"><?php echo $breadcrumb_url;?></li>
<?php 
					}
			  }	
?>
      </ul> -->
      <!-- /.breadcrumb -->
      <!-- #section:basics/content.searchbox -->
      <div class="nav-search" id="nav-search"></div>
      <!-- /.nav-search -->
      <!-- /section:basics/content.searchbox -->
    </div>
    <!-- /section:basics/content.breadcrumbs -->

    <div class="page-content">
      <!-- #section:settings.box -->
      <div class="ace-settings-container" id="ace-settings-container"> </div>
      <!-- /.ace-settings-container -->
      <!-- /section:settings.box -->
      <article class="page-content-area">
        <div class="page-header">
          <h1> <?php if(isset($web_title) and $web_title!=""){ echo $web_title; }?></h1>
        </div>
        <!-- /.page-header -->
<?php 
################################## content ######################################### 

		if(isset($content_view) and isset($content_data)){ 
			$this->load->view($content_view,$content_data);
		}else if(isset($content_view)){ 
			$this->load->view($content_view);
		}
		
################################ end content ####################################### 
?>
        <!-- /.row -->
      </article>
      <!-- /.page-content-area -->
    </div><!-- /.page-content -->
  </div><!-- /.main-content -->
    
</main>
<!-- /.main-content -->
<footer class="footer">
    <div class="footer-inner">
      <!-- #section:basics/footer -->
      <div class="footer-content"></div>
      <!-- /section:basics/footer -->
    </div>
</footer>

<script type="text/javascript">
	//if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<?php
    echo js_asset('jquery-3.1.0.min.js');
  if($this->uri->segment(1) == 'xml')
    echo js_asset('bootstrap.min.js');
  else
    echo js_asset('bootstrap.min.js', 'bootstrap-5.2.2');
?>
<script type="text/javascript">

function update_team_name(team_id, res){
  
  var url = '<?php echo base_url('update/update_team') ?>/' + team_id;
  var team_name = $('#team_name' + team_id).val();

  console.log('Updat team:' + team_name + ' ' + team_id);

  $.ajax({
      url:url,
      type:"POST",
      data:{
        team_id: team_id, team_name: team_name
      },
      success:function(response) {
        
        $('#' + res).html('<span class="green">' + response + '</span>');
      }
  });
}

function update_profile_name(profile_id, team_id, res){
  
  var url = '<?php echo base_url('update/profile_name') ?>/' + profile_id;
  var player_name_th = $('#player_name_th' + profile_id).val();

  $.ajax({
      url:url,
      type:"POST",
      data:{
        profile_id: profile_id, team_id: team_id, player_name_th: player_name_th
      },
      success:function(response) {
        
        $('#' + res).html('<span class="green">' + response + '</span>');
      }
  });
}

function update_manager_name(manager_id, team_id, res){
  
  var url = '<?php echo base_url('update/manager_name') ?>/' + manager_id;
  var manager_name = $('#manager_name' + manager_id).val();

  $.ajax({
      url:url,
      type:"POST",
      data:{
        manager_id: manager_id, team_id: team_id, manager_name: manager_name
      },
      success:function(response) {
        
        $('#' + res).html('<span class="green">' + response + '</span>');
      }
  });
}

function update_stadium(stadium_id){
  
  var url = '<?php echo base_url('update/update_stadium') ?>/' + stadium_id;
  var stadium_name = $('#stadium_name').val();

  $.ajax({
      url:url,
      type:"POST",
      data:{
        stadium_name: stadium_name
      },
      success:function(response) {
        
        $('#res_stadium').html('<span class="green">' + response + '</span>');
      }
  });
}

function update_analy(program_id){
  
  var url = '<?php echo base_url('update/program_analy') ?>/' + program_id;
  var analy_home = $('#analy_home').val();
  var analy_away = $('#analy_away').val();
  var player_home = $('#player_home').val();
  var player_away = $('#player_away').val();
  var vision = $('#vision').val();
  var predict = $('#predict').val();
  var interesting = $('#interesting').val();

  $.ajax({
      url:url,
      type:"POST",
      data:{
        analy_home: analy_home, analy_away: analy_away, player_home: player_home, player_away: player_away, vision: vision, predict: predict, interesting: interesting
      },
      success:function(response) {
        
        $('#res').html('<span class="green">' + response + '</span>');
      }
  });
}

</script>
</body>
</html>
