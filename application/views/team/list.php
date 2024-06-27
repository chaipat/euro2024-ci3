<section class="warpper bg-white score-box">
	<div class="container-main teamlist">
		<h1>ข้อมูลทีมชาติ</h1>
		<?php echo $list_teams ?>
	</div>

	<aside class="sidebar">
		<?php
		// $this->load->view('widgets-rectangle1');

		if(isset($widgets_program)){
			$this->load->view('widgets-program', $widgets_program);
		}

		// $this->load->view('widgets-rectangle2');
		$this->load->view('widgets-result');
		?>
	</aside>
</section>
