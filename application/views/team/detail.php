<section class="warpper bg-blue team">

  <div class="headline headlinewhite">
    <h1><?php echo $show_team_logo.' '.$title ?></h1>
    <div class="sharesocial sharewhite">
      <div>
        <?php echo $social_block ?>
      </div>
    </div>
  </div>

  <div class="newsteambox">
    <?php
    $team_cover = './assets/images/'.$team_name_en.'-2022x620.webp';
    if(file_exists($team_cover)){

      echo '<div class="newsteamhi">
        <figure><img src="'.base_url($team_cover).'" width="100%" alt="'.$title.'"/></figure>
        <span><strong>'.$title.'</strong></span>
      </div>';
    }
    
    ?>

    <aside class="sidebar">
      <?php $this->load->view('widgets-rectangle1'); ?>
    </aside>

  </div>

</section>
  
<section class="warpper bg-white score-box">

  <div class="score-full scoreteam">
    <?php echo $display_standing ?>
    <a href="<?php echo base_url('standing') ?>" target="_blank">ตารางคะแนนทั้งหมด</a>
  </div>

  <aside class="sidebar">
    <?php $this->load->view('widgets-rectangle2'); ?>
  </aside>

</section>

<section class="warpper bg-white score-box">

  <div class="container-main program-result">
  <?php echo $display_program ?>
  <a class="more-link" href="<?php echo base_url('fixtures') ?>"  target="_blank">ดูทั้งหมด</a> 
  </div>

  <aside class="sidebar">
  
  </aside>

</section>

<section class="warpper bg-white">
  <?php echo $display_player ?>
</section>

<!-- <section class="warpper bg-blue teaminfo">
<div>
<h5>ข้อมูลทีมชาติอังกฤษ</h5>
<figure>
  <img src="<?php echo base_url() ?>assets/images/demo-team.jpg" alt=""/>
</figure>
<span>ทีมชาติอังกฤษ ชนะเลิศฟุตบอลโลก 1 ครั้ง ใน ฟุตบอลโลก 1966 นับจากการแข่งทั้งหมด ทีมชาติ อังกฤษ เข้าฟุตบอลโลกรอบสุดท้ายทั้งหมด 14 ครั้ง (ฟุตบอลโลก 2014 เป็นครั้งที่ 14) ซึ่งอังกฤษ เริ่มเล่นครั้งแรกใน ฟุตบอลโลก 1950 ซึ่งในครั้งแรกนั้นแม้จะผ่านรอบคัดเลือกแต่ตกรอบแรกไป ซึ่งหลังจากนั้นทีมอังกฤษผ่านรอบคัดเลือกมาตลอดทุกปีต่อเนื่องกัน จนกระทั่งชนะเลิศในฟุตบอลโลก 1966 แต่หลังจากนั้นใน ฟุตบอลโลก 1974, 1988 และ 1994 ทีมชาติอังกฤษไม่ผ่านรอบคัดเลือก โดยใน ฟุตบอลโลก 2006 ทีมชาติอังกฤษเข้าสู่รอบก่อนรองชนะเลิศและแพ้ โปรตุเกสไปจากการยิงจุดโทษ ในฟุตบอลโลก 2010 ทีมชาติอังกฤษก็ต้องตกรอบตั้งแต่รอบ 16 ทีมสุดท้าย โดยแพ้ เยอรมนีไป 1-4 เมื่อวันอาทิตย์ที่ 27 มิถุนายน ค.ศ. 2010</span>
</div>
</section> -->