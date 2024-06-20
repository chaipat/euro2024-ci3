<div class="warpper bg-blue billboard">
	<a href="https://www.ballnaja.com/" target="_blank"><img src="<?php echo base_url() ?>assets/images/banner-wc2022.webp" alt="บอลนะจ๊ะ" /></a>
</div>
<section class="warpper bg-white score-box fixtures">
	<div class="headline">
		<h1><?php echo $head ?></h1>
		<div class="sharesocial">
			<!-- <span> View <strong>120k</strong>  Share <strong>53k</strong></span> -->
			<div><?php echo $social_block?></div>
		</div>
	</div>
	<div class="container-main program-result">
		<div class="menu-prore">
			<a href="<?php echo base_url('fixtures').'?stage=10561027&w=1' ?>" <?php if($stage_id == 10561027 && $week == 1) echo 'class="proactive"'; ?> data-value="10561027">รอบแบ่งกลุ่มนัด 1</a>
			<a href="<?php echo base_url('fixtures').'?stage=10561027&w=2' ?>" <?php if($stage_id == 10561027 && $week == 2) echo 'class="proactive"'; ?> data-value="10561027">รอบแบ่งกลุ่มนัด 2</a>
			<a href="<?php echo base_url('fixtures').'?stage=10561027&w=3' ?>" <?php if($stage_id == 10561027 && $week == 3) echo 'class="proactive"'; ?> data-value="10561027">รอบแบ่งกลุ่มนัด 3</a>
			<a href="<?php echo base_url('fixtures').'?stage=10561511' ?>" <?php if($stage_id == 10561511) echo 'class="proactive"'; ?> data-value="10561511">รอบ 16 ทีมสุดท้าย</a>
			<a href="<?php echo base_url('fixtures').'?stage=10562591' ?>" <?php if($stage_id == 10562591) echo 'class="proactive"'; ?> data-value="10562591">รอบ 8 ทีมสุดท้าย</a>
			<a href="<?php echo base_url('fixtures').'?stage=10561089' ?>" <?php if($stage_id == 10561089) echo 'class="proactive"'; ?> data-value="10561089">รอบรองชนะเลิศ</a>
			<!-- <a href="<?php echo base_url('fixtures').'?stage=10561069' ?>" <?php if($stage_id == 10561069) echo 'class="proactive"'; ?> data-value="10561069">รอบชิงอันดับ 3</a> -->
			<a href="<?php echo base_url('fixtures').'?stage=10561444' ?>" <?php if($stage_id == 10561444) echo 'class="proactive"'; ?> data-value="10561444">รอบชิงชนะเลิศ</a>
		</div>
		<!-- <div class="menu-subprore"> -->
			<?php
			/*if($sel_date){
				$num = count($sel_date);
				for($i=0;$i<$num;$i++){

					// Debug($sel_date[$i]);
					$ch_date = $sel_date[$i]->sel_date;
					$n_date = date('d/m', strtotime($ch_date));

					echo '<a href="#" class="sel_date" data-value="'.$ch_date.'">'.$n_date.'</a>';
				}

				//<a href="#" class="proactive">24</a>
			}*/
			?>
		<!-- </div> -->
		<div class="clear"></div>
		<?php echo $html ?>
	</div>
	<?php
	$this->load->view('sidebar');
	?>
</section>