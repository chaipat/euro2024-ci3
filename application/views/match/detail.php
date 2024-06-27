<section class="warpper bg-white score-box">

  <div class="headline">
    <h1><?php echo $head ?></h1>

    <div class="sharesocial">
      <div><?php echo $social_block ?></div>
    </div>
  </div>

  <div class="consihighlight">
    <div class="match-list consimatch">
      <?php echo $display_matchinfo ?>
    </div>

    <aside class="sidebar">
      <?php //$this->load->view('widgets-rectangle1'); ?>
    </aside>

  </div>

  <div class="container-main">
    <div class="live-timeline">
    <h2><img src="<?php echo base_url('assets/images') ?>/icon-time.png" alt=""/>รายงานผลบอลสด</h2>
    <?php echo $display_match_event ?>
    </div>
    <?php 
    echo $event_pen;
    echo $man_of_match;
    echo $lineup;
    echo $match_stat;
    ?>
  </div>

  <aside class="sidebar">
    <?php //echo $relate_content ?>
  </aside>

</section>