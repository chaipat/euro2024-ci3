<div class="warpper bg-blue billboard">
	<a href="https://www.ballnaja.com/" target="_blank"><img src="./assets/images/banner-wc2022.webp" alt="บอลนะจ๊ะ" /></a>
</div>
<section class="warpper bg-white score-box">
	<div class="headline">
		<h1><?php echo $head ?></h1>
		<div class="sharesocial">
			<!-- <span> View <strong>120k</strong>  Share <strong>53k</strong></span> -->
			<div><?php echo $social_block ?></div>
		</div>
	</div>

	<div class="score-full">
		<?php echo $html ?>
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