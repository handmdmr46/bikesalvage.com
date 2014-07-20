<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $description; ?>
  <?php if ($tags) { ?>
  <div class="blogTags"><?php echo $text_tags; ?>
    <?php foreach ($tags as $tag){ ?>
    <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>,
    <?php } ?>
  </div>
  <?php } ?>
  <?php if ($blogs) { ?>
    <div class="blogRelated">
    <h2><?php echo $text_related_blogs; ?></h2>
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
  <?php } ?>
  <?php if($images){ ?>
    <h2><?php echo $text_images; ?></h2>
    <div class="blogImage">
      <?php foreach ($images as $image) { ?>
      <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
      <?php } ?>
    </div>
  <?php } ?>
  <?php if($videos){ ?>
    <h2><?php echo $text_videos; ?></h2>
    <div class="blogVideo">
      <?php foreach ($videos as $video) { ?>
	  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" data="<?php echo $video['link']; ?>">
        <param name="quality" value="high" />
        <param name="movie" value="<?php echo $video['link']; ?>" />
        <embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="<?php echo $video['link']; ?>" type="application/x-shockwave-flash"></embed>
      </object>
      <?php } ?>
    </div>
  <?php } ?>
  <?php if ($products) { ?>
    <h2><?php echo $text_products; ?></h2>
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/blogger/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } else { ?>
        <div class="rating">&nbsp;</div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
      </div>
      <?php } ?>
    </div>
  <?php } ?>
  <div>
    <div id="comment"></div>
    <h2 id="comment-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_email; ?></b><br />
    <input type="text" name="email" value="" />
    <br />
    <br />
    <b><?php echo $entry_website; ?></b><br />
    <input type="text" name="website" value="" />
    <br />
    <br />
    <b><?php echo $entry_comment; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    <br />
    <img src="index.php?route=extras/blog/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="right"><a id="button-comment" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	overlayClose: true,
	opacity: 0.5
});
//--></script> 
<script type="text/javascript"><!--
$('#comment .pagination a').live('click', function() {
	$('#comment').fadeOut('slow');
		
	$('#comment').load(this.href);
	
	$('#comment').fadeIn('slow');
	
	return false;
});			

$('#comment').load('index.php?route=extras/blog/comment&blog_id=<?php echo $blog_id; ?>');

$('#button-comment').bind('click', function() {
	$.ajax({
		url: 'index.php?route=extras/blog/write&blog_id=<?php echo $blog_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&email=' + encodeURIComponent($('input[name=\'email\']').val()) + '&website=' + encodeURIComponent($('input[name=\'website\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-comment').attr('disabled', true);
			$('#comment-title').after('<div class="attention"><img src="catalog/view/theme/blogger/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-comment').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#comment-title').after('<div class="warning">' + data['error'] + '</div>');
			}
			
			if (data['success']) {
				$('#comment-title').after('<div class="success">' + data['success'] + '</div>');
								
				$('input[name=\'name\']').val('');
				$('input[name=\'email\']').val('');
				$('input[name=\'website\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<?php echo $footer; ?>