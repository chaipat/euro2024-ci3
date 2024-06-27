<!DOCTYPE html>
<html lang="th">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <meta id="myViewport" name="viewport" content="width=device-width">
  <title> <?php echo $webtitle ?> </title>
  <meta name="description" content="<?php echo $description = (isset($meta['description'])) ? $meta['description'] : ''; ?>" />
  <meta name="keywords" content="<?php echo $keywords = (isset($meta['keywords'])) ? @implode(', ', $meta['keywords']) : ''; ?>" />
  <meta name="Author" content="Ballnaja">
  <meta name="Copyright" content="Ballnaja" />
  <meta name="robots" content="index,follow" />
  <link rel="alternate" href="<?php echo base_url() ?>" hreflang="th" />
  <link rel="canonical" href="<?php echo base_url(uri_string()); ?>">
<?php

  $path_info = explode('?', $_SERVER['REQUEST_URI']);
  // Debug($path_info);
  /*$description = '';
  if (isset($meta)) {
    foreach (@$meta as $key => $value) {
      $v = '';
      if (is_array($value)) {
        $v = implode(', ', $value);
        echo '<meta name="' . $key . '" content="' . $v . '"/>';
        if ($key == 'description') {
          $description = $value;
        }
      } else
        echo '<meta name="' . $key . '" content="' . $value . '"/>';
    }
  } else {
  }*/
?>
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="//www.googletagmanager.com">
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="dns-prefetch" href="//kit.fontawesome.com">  
  <link rel="dns-prefetch" href="//www.google-analytics.com">
  <link rel="dns-prefetch" href="//ballnaja.s3.ap-southeast-1.amazonaws.com">
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  <!-- <link rel="dns-prefetch" href="//sweetalert.js.org">   -->
  <!-- <link rel="dns-prefetch" href="//connect.facebook.net">
  <link rel="dns-prefetch" href="//www.facebook.com">
  <link rel="dns-prefetch" href="//connect.facebook.net"> -->
  <meta property="fb:app_id" content="1616255538832638" />
  <!-- <meta property="fb:pages" content="100086290803929" /> -->
<?php
  $this->load->view('favicon');
?>

  <link rel="image_src" type="image/jpeg" href="<?php echo $page_image = (isset($meta['page_image'])) ? $meta['page_image'] : '' ?>">
  <meta itemprop="thumbnailUrl" content="<?php echo $page_image ?>">
  <?php if (isset($meta['page_published_time']) != '') { ?>
  <meta itemprop="datePublished" content="<?php echo $page_published_time  = (isset($meta['page_published_time'])) ? $meta['page_published_time'] : '' ?>">
  <?php } ?>
  <meta itemprop="dateModified" content="<?php echo $page_lastupdated_date  = (isset($page_lastupdated_date)) ? $page_lastupdated_date : '' ?>">
  <meta property="article:modified_time" content="<?php echo $page_lastupdated_date ?>">
  <meta property="og:updated_time" content="<?php echo $page_lastupdated_date ?>">
  <meta property="og:url" content="<?php echo base_url(uri_string()); ?>">
  <meta property="og:type" content="article">
  <meta property="og:title" content="<?php echo $webtitle ?>">
  <meta property="og:description" content="<?php echo $description ?>">
  <meta property="og:locale" content="th_TH">
  <meta property="og:image" content="<?php echo $page_image ?>">
  <meta property="og:image:secure_url" content="<?php echo $page_image ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@dooballnaja">
  <meta name="twitter:creator" content="@dooballnaja">
  <meta name="twitter:title" content="<?php echo $webtitle ?>">
  <meta name="twitter:description" content="<?php echo $description ?>">
  <meta name="twitter:image:src" content="<?php echo $page_image ?>">
  <meta name="twitter:domain" content="worldcup2022.ballnaja.com">

  <!-- <link rel="preload" href="/assets/js/runtime~main.60a3964f.js" as="script"> -->
  <link rel="preload" href="https://fancyapps.com/assets/js/main.00713ad2.js" as="script">

<?php
// <!-- bootstrap & fontawesome -->
    echo css_asset('bootstrap.min.css');
    echo css_asset('reset.min.css');
    // echo css_asset('main.css?v=123');
    echo css_asset('main.css?v=7');
    echo css_asset('jquery.mobile-menu.css?v=3');
    echo css_asset('flexslider.css?v=4');
    // echo css_asset('jquery.fancybox.css');
    echo css_asset('sweetalert.css?v=2.1.2.0');
    // /<link rel="stylesheet" href="https://sweetalert.js.org/assets/css/app.css">

    // echo '<link rel="stylesheet" href="https://fancyapps.com/assets/css/styles.8172b60e.css">';
    
    if(isset($css)){
      foreach($css as $val){

        if($val == 'jquery.fancybox.css' || $val == 'jquery.fancybox-thumbs.css'){
          
          echo css_asset($val, 'gallery');
        }else if($val == 'fancybox.css'){

          echo css_asset('fancybox.css', 'fancyapps@4.0');
        }else
          echo css_asset($val);
      }
    }

$this->load->view('gtm_head');
$this->load->view('ga');
?>
<style>
.clear{clear: both;}
.program-result div.menu-subprore a {
    display: block;
    float: left;
    font-size: 18px;
    font-weight: 600;
    padding: 5px 0;
    width: 14%!important;
    margin: 0 1px 1px 0;
    border: 1px solid #7f0025;
    width: 49.82%;
    text-align: center;
    text-decoration: none;
    transition: all .2s ease-in-out;
}
</style>
  <!-- ace settings handler -->
  <script type="text/javascript">
    //window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.min.js'>"+"<"+"/script>");
  </script>

<?php
    //echo js_asset('jquery-3.1.0.min.js');
    echo js_asset('jquery-1.11.2.min.js');
    echo js_asset('modernizr-2.8.3-respond-1.4.2.min.js');
    echo js_asset('selectivizr-min.js');
    echo js_asset('jquery.mobile-menu.min.js');
    echo js_asset('scroller.js');
    echo js_asset('jquery.flexslider-min.js');
    // echo js_asset('jquery.fancybox.js');
    echo js_asset('sweetalert.min.js?v=2.1.2');
    
   

    if(isset($js)){
      foreach($js as $val){

        if($val == 'jquery.fancybox.js' || $val == 'jquery.fancybox-thumbs.js'){
          
          echo js_asset($val, 'gallery');
        }else if($val == 'fancybox.umd.js'){

          echo js_asset('fancybox.umd.js', 'fancyapps@4.0');
        }else
          echo js_asset($val);
      }
    }
?>
<script src="https://kit.fontawesome.com/97cd5ead0e.js" crossorigin="anonymous"></script>
<!-- <script src="https://fancyapps.com/assets/js/main.00713ad2.js"></script> -->
<script>
Fancybox.bind('[data-fancybox="gallery"]', {
  Image: {
    zoom: false,
  },
});
</script>
</head>
<body>
<?php
$this->load->view('gtm_body');
?>
<div id="overlay"></div>
<div id="page">

  <div class="menumobile"> 
  <div>
  <a href="<?php echo base_url() ?>"><img src="<?php echo base_url('assets/images/logo/euro2024-4.jpeg') ?>" alt="ยูโร 2024"/></a>
  <nav class="mm-toggle"><span></span><span></span><span></span></nav>
  <a href="#" id="toggle" class="hidden">search</a>
  </div>
  <div class="toggle">
    <form><input type="text" name="textfield" id="search-m"><input type="submit" name="submit" id="submit-m"></form>
    <a href="javascript:void(0)" onClick="$('.toggle').slideUp('fast');" class="close-search">x Close</a></div>
  </div>

  <div class="warpper-head">
    <header class="menuhead">
      <a href="<?php echo base_url() ?>"><img src="<?php echo base_url('assets/images/logo/euro2024-4.jpeg') ?>" alt="ยูโร 2024"/></a>
      <nav>
        <a href="<?php echo base_url() ?>" <?php echo ($path_info[0] == '/') ? "class=\"mactive\"" : ""; ?>>หน้าแรก</a>
        <a href="<?php echo base_url('news') ?>" <?php echo ($path_info[0] == '/news') ? "class=\"mactive\"" : ""; ?>>ข่าว</a>
        <a href="<?php echo base_url('fixtures').'#'.date('Y-m-d') ?>" <?php echo ($path_info[0] == '/fixtures') ? "class=\"mactive\"" : ""; ?>>โปรแกรม ผลบอล</a>
        <a href="<?php echo base_url('standing') ?>" <?php echo ($path_info[0] == '/standing') ? "class=\"mactive\"" : ""; ?>>ตารางคะแนน</a>
        <a href="<?php echo base_url('topscore') ?>" <?php echo ($path_info[0] == '/topscore') ? "class=\"mactive\"" : ""; ?>>ดาวซัลโว</a>
        <!-- <a href="#" <?php echo ($path_info[0] == '/column') ? "class=\"mactive\"" : ""; ?>>สกู๊ปข่าว </a> -->
        <a href="<?php echo base_url('analyze').'#'.date('Y-m-d') ?>" <?php echo ($path_info[0] == '/analyze') ? "class=\"mactive\"" : ""; ?>>วิเคราะห์บอล</a>
        <!-- <a href="#" <?php echo ($path_info[0] == '/clip') ? "class=\"mactive\"" : ""; ?>>วิดีโอ</a> -->
        <a href="<?php echo base_url('team') ?>" <?php echo ($path_info[0] == '/team') ? "class=\"mactive\"" : ""; ?>>ข้อมูลทีม</a>
      </nav>
      <div class="search-bar">
        <form class="hidden">
          <input type="text" name="textfield" id="search">
          <input type="submit" name="submit" id="submit">
        </form>
        <div class="sociallink"> <!-- target="_blank" -->
            <a href="#<?php echo _FB ?>" rel="nofollow" >facebook</a> 
            <a href="#<?php echo _TW ?>" rel="nofollow" >twitter</a> 
            <a href="#<?php echo _YT ?>" rel="nofollow" >youtube</a> 
        </div>
      </div>
    </header>
  </div>
  
  <div class="spacemenu"></div>
	<div class="spacemenu-m"></div>

<?php 
################################## content ######################################### 

		if(isset($content_view) and isset($content_data)){ 
			$this->load->view($content_view, $content_data);
		}else if(isset($content_view)){ 
			$this->load->view($content_view);
		}
		
################################ end content ####################################### 
?>
<footer class="footersection">
  <div>
    <input type="hidden" name="ipv4" id="ipv4">
    <a href="<?php echo base_url() ?>"><img src="<?php echo base_url('assets/images/logo/euro2024-4.jpeg') ?>" alt="ยูโร 2024"/></a>
  <ul>
  <li>
    <div>
      <a href="<?php echo base_url() ?>">หน้าแรก</a>
      <a href="<?php echo base_url('fixtures').'#'.date('Y-m-d') ?>">โปรแกรม ผลบอล</a>
      <a href="<?php echo base_url('analyze') ?>">วิเคราะห์บอล</a>
    </div>
    <div>
      <a href="<?php echo base_url('standing') ?>">ตารางคะแนน</a>
      <a href="<?php echo base_url('topscore') ?>">ดาวซัลโว</a>
      <!-- <a href="#">สกู๊ปข่าว</a> -->
    </div>
  </li>
  <li>
    <div>
      <a href="<?php echo base_url('news') ?>">ข่าว</a>
      <a href="<?php echo base_url('team') ?>">ข้อมูลทีม</a>
      <!-- <a href="#">วิดีโอ</a> -->
      
    </div>
    <div>
      <!-- <a href="#">แกลเลอรี่</a> -->
    </div>
  </li>
  <li>
    <a href="#<?php echo _FB ?>" rel="nofollow" >facebook</a> <!-- target="_blank" -->
    <a href="#<?php echo _TW ?>" rel="nofollow" >twitter</a>
    <a href="#<?php echo _YT ?>" rel="nofollow" >youtube</a>
  </li>
  </ul>
  </div>
  <div>&copy; Copyright 2022 all Rights Reserved </div>
  </footer>
</div>

<div class="pagetop"><span><a href="#" class="scrollup">Scroll</a></span></div>

<div id="mobile-menu">
  <ul>
    <li class="mm-toggle close-mm">
      <nav><strong></strong><strong></strong></nav>
    </li>
    <li><a href="<?php echo base_url() ?>#">หน้าแรก</a></li>
    <li><a href="<?php echo base_url('news') ?>">ข่าว</a></li>
    <li><a href="<?php echo base_url('fixtures') ?>">โปรแกรม ผลบอล</a></li>
    <li><a href="<?php echo base_url('standing') ?>">ตารางคะแนน</a></li>
    <li><a href="<?php echo base_url('topscore') ?>">ดาวซัลโว</a></li>
    <li><a href="<?php echo base_url('analyze') ?>">วิเคราะห์บอล</a></li>
    <li><a href="<?php echo base_url('team') ?>">ข้อมูลทีม</a></li>
  </ul>
</div>

<script type="text/javascript">
	//if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<?php
    // echo js_asset('bootstrap.min.js'); 
?>
<script type="text/javascript">
$(document).ready(function(){
	
	$(".various").fancybox({
		fitToView	: true,
		padding		: 0,
		width		: '100%',
		height		: '100%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade'
	});
	
  $('.flexslider').flexslider({
    animation:"slide",
    directionNav:true,
    animationLoop: true,
    pauseOnHover: true,
    touch:true,
    slideshowSpeed: 5000
  });

  $("ul#tab-menu li").click(function(){
      if (!$(this).hasClass("tab-active")) {
          var tabNum = $(this).index();
          var nthChild = tabNum+1;
          $("ul#tab-menu li.tab-active").removeClass("tab-active");
          $(this).addClass("tab-active");
          $("ul#tab-data li.tab-active").removeClass("tab-active");
          $("ul#tab-data li:nth-child("+nthChild+")").addClass("tab-active");
      }
  });
});
</script>
  
<script type="text/javascript"> 
$(function() { 
	$('#toggle').click(function() {
	$('.toggle').slideToggle('fast'); return false; }); 
});
</script>
<script type="text/javascript">
$(document).ready(function(){

  // SuccessAlert('ยินดีด้วย แจ่มมาก');
  // ErrorAlert('แง่ว');
<?php
if(isset($script_fancybox)){
  echo $script_fancybox;
}
if(isset($script_topscore)){
  $this->load->view('topscore/js');
}
?>
	$("#mobile-menu").mobileMenu({
      MenuWidth: 250,
      SlideSpeed : 300,
      WindowsMaxWidth : 767,
      PagePush : false,
      FromLeft : false,
      Overlay : true,
      CollapseMenu : true,
      ClassName : "mobile-menu"
  });
  $('.scrollup').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 800);
    return false;
  });
});
	
$(window).scroll(function(){
  if ($(this).scrollTop() > 100) {
    $('.scrollup').fadeIn();
  } else {
    $('.scrollup').fadeOut();
  }
});
	
$(window).scroll(function(){
  if($(this).scrollTop() >90){
    $(".warpper-head").addClass("warpper-head-fixed");
    $(".spacemenu").addClass("spacemenu-fixed");	
  }else {
    $(".warpper-head").removeClass("warpper-head-fixed");	
    $(".spacemenu").removeClass("spacemenu-fixed");	
  }

  if($(this).scrollTop() >70){   
    $(".menumobile").addClass("menumobile-fixed");
    $(".spacemenu-m").addClass("spacemenu-fixed-m");	
    
  }else {
    $(".menumobile").removeClass("menumobile-fixed");
    $(".spacemenu-m").removeClass("spacemenu-fixed-m");	
  }
});
</script>
<?php
$this->load->view('sweetalert');
// $this->load->view('getip');
// $this->load->view('backlink');
?>
<script type="text/javascript">
<?php 
  // echo file_get_contents(APPPATH.'../assets/js/jquery.min.js');
  // echo file_get_contents(APPPATH.'../assets/js/moment.min.js');
  // echo file_get_contents(APPPATH.'../assets/js/moment-th.min.js');
?>
function call_update_profile(profile_id, team_id, res){
  $.get('<?php echo base_url('xml/import_player/query') ?>/' + profile_id + '/' + team_id, function(data){ $('#' + res).html('Update player profile ' + profile_id + ' success.') });
}

function call_debug_profile(profile_id, team_id, res){
  $.get('<?php echo base_url('xml/import_player/debug') ?>/' + profile_id + '/' + team_id, function(data){ $('#' + res).html('Update player profile ' + profile_id + ' success.') });
}

</script>
<?php
if(isset($script_countdown)){
  echo $script_countdown;
}
?>

</body>
</html>
