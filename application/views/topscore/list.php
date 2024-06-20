<div class="warpper bg-blue billboard">
  <a href="https://www.ballnaja.com/" target="_blank"><img src="./assets/images/banner-wc2022.webp" alt="บอลนะจ๊ะ" /></a>
</div>
<section class="warpper bg-white score-box">
  <div class="headline">
    <h1><?php echo $head ?></h1>
    <div class="sharesocial">
      <!-- <span> View <strong>120k</strong> Share <strong>53k</strong></span> -->
      <div><?php echo $social_block ?></div>
    </div>
  </div>
  <div class="container-main contop">
    <ul id="tab-salvo">
      <li class="tab-active">ดาวซัลโว</li>
      <li>แอสซิส</li>
    </ul>
    <ul id="tab-data">
      <li class="salvo-tab tab-active">
        <?php echo $display_topscore ?>
        <!-- <a class="more-link" href="#" target="_blank">อันดับทั้งหมด</a> -->
      </li>
      <li class="salvo-tab">
        <?php echo $display_topassist ?>
        <!-- <a class="more-link" href="#" target="_blank">อันดับทั้งหมด</a> -->
      </li>
    </ul>
  </div>
  <aside class="sidebar">
		<?php
		$this->load->view('widgets-rectangle1');

		if(isset($widgets_program)){
			$this->load->view('widgets-program', $widgets_program);
		}
		$this->load->view('widgets-facebook');
		$this->load->view('widgets-rectangle2');

		if(isset($widgets_result) && $widgets_result != ''){
			$this->load->view('widgets-result', $widgets_result);
		}
		?>
	</aside>

</section>