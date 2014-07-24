<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div id="blogModule">
    <div id="search">
      <div class="button-blog-search"></div>
      <?php if ($filter_title) { ?>
      <input type="text" name="filter_title" value="<?php echo $filter_title; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_title" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
      <?php } ?>
    </div>
    </div>
  </div>
</div>
