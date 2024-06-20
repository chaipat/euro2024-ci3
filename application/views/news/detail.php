<div class="warpper bg-blue billboard">
  <a href="https://www.ballnaja.com/" target="_blank">
    <img src="<?php echo base_url() ?>assets/images/banner-wc2022.jpg" alt="บอลนะจ๊ะ" />
  </a>
</div>
<section class="warpper bg-white score-box news-detail">
  <div class="headline">
    <h1><?php echo $webtitle ?></h1>
    
    <div class="sharesocial">
      <span><?php echo $news_date_th.' '.$news_time?></span>
      <!-- <span> View <strong>120k</strong> Share <strong>53k</strong> -->
      </span>
      <div>
        <?php echo $social_block ?>
      </div>
    </div>
  </div>
  <div class="highlightpic">
    <figure>
      <img src="<?php echo $meta['page_image'] ?>" alt="<?php echo $webtitle ?>" />
    </figure>
    <aside class="sidebar">
      <?php
      $this->load->view('widgets-rectangle1');
      // $this->load->view('widgets-facebook');
      if(isset($widgets_program)){
        $this->load->view('widgets-program', $widgets_program);
      }

      if($relate_team)
        echo $relate_team;
      ?>
    </aside>
  </div>
  <div class="detailnews">
    <div>
      <?php echo $html ?>
      <div class="tagnews">
        <strong>TAG</strong>
        <?php echo $view_tags?>
      </div>
    </div>
    
    <aside class="sidebar">
        <!-- <div class="teammini">
            <a href="#"><img src="<?php echo base_url() ?>assets/images/demo-team-icon.png" alt="ข้อมูลทีมอังกฤษ"/>
            <strong>ข้อมูลทีมอังกฤษ</strong></a>
        </div> -->
        <?php
        $this->load->view('widgets-facebook');
        $this->load->view('widgets-rectangle2');
        $this->load->view('widgets-result');
        ?>
    </aside>
</section>