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
      <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      	<a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
      	<a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').submit();" class="button"><?php echo $button_delete; ?></a>
      	<a onclick="$('#form').attr('action', '<?php echo $approve; ?>');$('#form').submit();" class="button"><?php echo $button_approve; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'r.author') { ?>
                  <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                <?php } ?>
              </td>
              <td class="left"><?php if ($sort == 'r.email') { ?>
                  <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                <?php } ?>
             </td>               
             <td class="left"><?php if ($sort == 'r.website') { ?>
                  <a href="<?php echo $sort_website; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_website; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_website; ?>"><?php echo $column_website; ?></a>
                <?php } ?>
              </td>              
              <td class="right"><?php if ($sort == 'r.rating') { ?>
                  <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                <?php } ?>
              </td>
              <td class="left"><?php if ($sort == 'r.status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?>
              </td>
              <td class="left"><?php if ($sort == 'r.date_added') { ?>
                  <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?>
              </td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($reviews) { ?>
            <?php foreach ($reviews as $review) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($review['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
                <?php } ?></td>              
              <td class="left"><?php echo $review['author']; ?></td>
              <td class="left"><?php echo $review['email']; ?></td>
              <td class="left"><?php echo $review['website']; ?></td>
              <td class="right"><?php echo $review['rating']; ?></td>
              <td class="left"><?php echo $review['status']; ?></td>
              <td class="left"><?php echo $review['date_added']; ?></td>
              <td class="right"><?php foreach ($review['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>