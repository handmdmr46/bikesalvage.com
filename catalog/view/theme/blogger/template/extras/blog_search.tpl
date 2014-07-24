<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
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
    <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  	<?php echo $text_not_found; ?>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>