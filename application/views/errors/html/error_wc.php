<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="">
<!--<![endif]-->
<head>
<title> 404 ไม่พบหน้า </title>
<meta charset="UTF-8">
<meta id="myViewport" name="viewport" content="width=device-width">

<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/reset.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/main.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/flexslider.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery.fancybox.css">

<script src="<?php echo base_url() ?>assets/js/jquery-1.11.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/selectivizr-min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mobile-menu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/scroller.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.fancybox.js"></script>

<script type="text/javascript">
$(document).ready(function(){

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
if($(this).scrollTop() >90)
{   
	$(".warpper-head").addClass("warpper-head-fixed");
	$(".spacemenu").addClass("spacemenu-fixed");	
}
else {
	$(".warpper-head").removeClass("warpper-head-fixed");	
	$(".spacemenu").removeClass("spacemenu-fixed");	
}
if($(this).scrollTop() >70)
{   
	$(".menumobile").addClass("menumobile-fixed");
	$(".spacemenu-m").addClass("spacemenu-fixed-m");	
	
}
else {
	$(".menumobile").removeClass("menumobile-fixed");
	$(".spacemenu-m").removeClass("spacemenu-fixed-m");	
}
	;});
	
  </script>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PRFBB2S');</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-202731779-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-202731779-1');
</script>
</head>

<body>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PRFBB2S"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<div id="overlay"></div>
<div id="page">

  <div class="menumobile"> 
  <div>
  <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>assets/images/logo-new-wc2022.png" alt=""/></a>
  <nav class="mm-toggle"><span></span><span></span><span></span></nav>
  </div>
  <div class="toggle">
<form><input type="text" name="textfield" id="search-m"><input type="submit" name="submit" id="submit-m"></form>
<a href="javascript:void(0)" onClick="$('.toggle').slideUp('fast');" class="close-search">x Close</a></div>
  </div>

  <div class="warpper-head">
    <header class="menuhead"> <a href="https://www.ballnaja.com/" target="_blank"><img src="<?php echo base_url() ?>assets/images/logo-new-wc2022.png" alt="บอลนะจ๊ะ" /></a>
    <nav>
    <a href="<?php echo base_url() ?>">หน้าแรก</a>
    <a href="<?php echo base_url() ?>news">ข่าว</a>
    <a href="<?php echo base_url() ?>fixtures">โปรแกรม ผลบอล</a>
    <a href="<?php echo base_url() ?>standing">ตารางคะแนน</a>
    </nav>
    </header>
  </div>
  
  <div class="spacemenu"></div>
	<div class="spacemenu-m"></div>
  <div class="warpper bg-blue billboard">
    <a href="https://www.ballnaja.com/" target="_blank"><img src="<?php echo base_url() ?>assets/images/banner-wc2022.jpg" alt="บอลนะจ๊ะ"></a>
  </div>
<section class="warpper bg-white score-box">
    <div class="headline">
        <h1> 404 ไม่พบหน้า </h1>
        <div class="container-main program-result">
            404 ไม่พบหน้า<br>ไม่พบหน้าที่คุณร้องขอ
        </div>
    </div> 
    <aside class="sidebar">
        <div class="rectangle">
        <a href="https://www.ballnaja.com/" target="_blank"><img src="<?php echo base_url() ?>assets/images/banner-300x250.webp" alt="บอลนะจ๊ะ" /></a>
        </div> <div class="rectangle">
        <a href="https://www.ballnaja.com/" target="_blank"><img src="<?php echo base_url() ?>assets/images/banner-300x250.webp" alt="บอลนะจ๊ะ" /></a>
        </div> 
    </aside>
</section>

<footer class="footersection">
  <div>
  <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>assets/images/logo-new-wc2022.png" alt="ฟุตบอลโลก 2022 บอลนะจ๊ะ.com"></a>
  <ul>
  <li>
  <div>
  <a href="<?php echo base_url() ?>">หน้าแรก</a>
  <a href="<?php echo base_url() ?>fixtures">โปรแกรม ผลบอล</a>
  </div>
  <div>
  <a href="<?php echo base_url() ?>standing">ตารางคะแนน</a>
  </div>
  </li>
  <li>
  <div>
  <a href="<?php echo base_url() ?>news">ข่าว</a>
  </div>
  <div>
  </div>
  </li>
  <li>
  <a href="https://www.facebook.com/profile.php?id=100086290803929" rel="nofollow" target="_blank">facebook</a>
  <a href="https://twitter.com/dooballnaja" rel="nofollow" target="_blank">twitter</a>
  <a href="https://www.youtube.com/channel/UC70aoVc0_i4bJvX3S3Xm4WQ" rel="nofollow" target="_blank">youtube</a>
  </li>
  </ul>
  </div>
  <div>&copy; Copyright 2022 all Rights Reserved <a href="https://www.ballnaja.com" target="_blank">www.ballnaja.com</a></div>
  </footer>
</div>

<div class="pagetop"><span><a href="#" class="scrollup">Scroll</a></span></div>

<div id="mobile-menu">
<ul>
<li class="mm-toggle close-mm">
<nav><strong></strong><strong></strong></nav>
</li>
<li><a href="<?php echo base_url() ?>">หน้าแรก</a></li>
<li><a href="<?php echo base_url() ?>news">ข่าว</a></li>
<li><a href="<?php echo base_url() ?>fixtures">โปรแกรม ผลบอล</a></li>
<li><a href="<?php echo base_url() ?>standing">ตารางคะแนน</a></li>
</ul>
</div>
</body>
</html>
