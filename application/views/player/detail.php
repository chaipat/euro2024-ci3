<section class="warpper bg-white score-box">

<div class="container-main playerinfo">

<?php echo $display_player ?>

<aside class="sidebar">
  <?php
  $this->load->view('widgets-rectangle1');
  // $this->load->view('widgets-facebook');
  if($relate_team)
    echo $relate_team;
  ?>
</aside>

</section>