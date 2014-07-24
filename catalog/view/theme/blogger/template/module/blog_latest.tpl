<?php if($display == 'link'){ ?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="blogLatestLink">
      <ul>
        <?php foreach ($blogs as $blog) { ?>
        <li>
          <?php if ($blog['blog_id'] == $blog_id) { ?>
          <a href="<?php echo $blog['href']; ?>" class="active"><?php echo $blog['title']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $blog['href']; ?>"><?php echo $blog['title']; ?></a>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
<?php } else { ?>
  <?php if($blogs){ ?>
  	<div id="magicBlogger">
  	<?php foreach($blogs as $blog){ ?>
  	  <div id="post">
        <div class="date"><span><?php echo $text_posted_on; ?></span> <?php echo $blog['date_added']; ?></div>
        <div class="display" style="min-height: <?php echo $min_height; ?>px">
          <div class="image"><img src="<?php echo $blog['image']; ?>" /></div>
          <div class="text">
            <div class="title"><a href="<?php echo $blog['href']; ?>"><?php echo $blog['title']; ?></a></div>
            <div class="description"><?php echo $blog['description']; ?></div>
          </div>
        </div>
        <div class="bottom">&nbsp;</div>
        <div class="tags">
          <div class="left">
          <span><?php echo $text_tags; ?></span>
          <?php foreach($blog['tags'] as $tag){ ?>
            <a href="<?php echo $blog['tag_href']; ?>&filter_tag=<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></a>,
          <?php } ?>
          </div>
          <div class="right"><a href="<?php echo $blog['href']; ?>"><?php echo $text_read_more; ?></a></div>
        </div>
        <div class="bottom">&nbsp;</div>
  	  </div>
    <?php } ?>
    </div>
  <?php } else { ?>
  	<?php echo $text_not_found; ?>
  <?php } ?>
<?php } ?>