<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/setting.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-social"><?php echo $tab_social; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_list_image; ?></td>
              <td><input type="text" name="blog_list_image_width" value="<?php echo $blog_list_image_width; ?>" size="3" />
                x
                <input type="text" name="blog_list_image_height" value="<?php echo $blog_list_image_height; ?>" size="3" />
                <?php if ($error_blog_list_image) { ?>
                <span class="error"><?php echo $error_blog_list_image; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_blog_list_max_chars; ?></td>
              <td><input type="text" name="blog_list_max_chars" value="<?php echo $blog_list_max_chars; ?>" size="5" />
                <?php if ($error_blog_list_max_chars) { ?>
                <span class="error"><?php echo $error_blog_list_max_chars; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab-social">
          <table class="form">
            <tr>
              <td><?php echo $entry_twitter_id; ?></td>
              <td><input type="text" name="blog_twitter_id" value="<?php echo $blog_twitter_id; ?>" size="32" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_facebook_page_id; ?></td>
              <td><input type="text" name="blog_facebook_page_id" value="<?php echo $blog_facebook_page_id; ?>" size="32" /></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#template').load('index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent($('select[name=\'template\']').attr('value')));
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?>