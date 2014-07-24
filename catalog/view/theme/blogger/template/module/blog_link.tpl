<?php if($module_box == 'yes'){ ?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
<?php } ?>
    <div id="blog_link<?php echo $module; ?>" class="blog_link">
      <?php foreach ($blog_links as $blog_link) { ?>
      <?php if ($blog_link['link']) { ?>
        <?php if($display == 'link'){ ?>
        <div><a href="<?php echo $blog_link['link']; ?>" target="_blank"><?php echo $blog_link['title']; ?></a></div>
        <?php } else { ?>
        <div><a href="<?php echo $blog_link['link']; ?>" target="_blank"><img src="<?php echo $blog_link['image']; ?>" alt="<?php echo $blog_link['title']; ?>" title="<?php echo $blog_link['title']; ?>" /></a></div>
        <?php } ?>
      <?php } else { ?>
      <div><img src="<?php echo $blog_link['image']; ?>" alt="<?php echo $blog_link['title']; ?>" title="<?php echo $blog_link['title']; ?>" /></div>
      <?php } ?>
      <?php } ?>
    </div>
<?php if($module_box == 'yes'){ ?>
  </div>
</div>
<?php } ?>
<?php if($image_cycle == 'yes'){ ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#blog_link<?php echo $module; ?> div:first-child').css('display', 'block');
});

var blog_link = function() {
	$('#blog_link<?php echo $module; ?>').cycle({
		before: function(current, next) {
			$(next).parent().height($(next).outerHeight());
		}
	});
}

setTimeout(blog_link, 2000);
//--></script>
<?php } ?>