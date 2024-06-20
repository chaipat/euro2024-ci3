    <div class="warpper bg-blue billboard">
        <a href="https://www.ballnaja.com/" target="_blank">
            <img src="<?php echo base_url() ?>assets/images/banner/2024-970x250_64283598.png" alt="euro2024" />
        </a>
    </div>
    <?php echo $running_text ?>
    <section class="warpper bg-blue highlight">
        <div class="flexslider">
        <?php echo $hightlight ?>
        </div>
        <div class="rectangle rect-highlight">
            <a href="https://www.winbigslot.com/?utm_source=euro2024&utm_medium=rectangle1" target="_blank" rel="nofollow">
                <img src="<?php echo base_url() ?>assets/images/banner/winbig-banner-300x250.webp" alt="สล็อต"/>
            </a>
        </div>
        <div class="highlight-5news"> <?php echo $hightlight_5news ?>
            <a href="<?php echo base_url('news') ?>" class="more-link">ข่าวทั้งหมด</a> 
        </div>
        <div class="program-small">
            <?php echo $program_today ?>
            <a href="<?php echo base_url('fixtures') ?>">โปรแกรมทั้งหมด</a> <a href="<?php echo base_url('analyze') ?>">วิเคราะห์บอล</a> 
        </div>
    </section>
<?php 
if(isset($clip_video)) echo $clip_video;
if(isset($block_standing)) echo $block_standing;
if(isset($block_column)) echo $block_column;
if(isset($block_wallpaper)) echo $block_wallpaper;
?>