<section class="warpper bg-blue highlight-listing">
  <h1>ข่าวฟุตบอลโลก 2022</h1>
  <?php if(isset($hightlight)) echo $hightlight ?>
  <?php $this->load->view('widgets-rectangle1'); ?>
</section>

<section class="warpper bg-white listing">
  <?php echo $list_news ?>
</section>

<?php echo $list_paging ?>
  
