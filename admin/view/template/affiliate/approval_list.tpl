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
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      	 <a onclick="$('form').attr('action', '<?php echo $approve; ?>'); $('form').submit();" class="button"><?php echo $button_approve; ?></a>
        <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
        <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a></td>
              <td class="left"><a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a></td>
              <td class="left"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a></td>
              <td class="left"><a href="<?php echo $sort_approved; ?>"><?php echo $column_approved; ?></a></td>
              <td class="left"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($affiliates) { ?>
            <?php foreach ($affiliates as $affiliate) { ?>
            <tr>
              <td style="text-align: center;">
                <?php if ($affiliate['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" />
                <?php } ?>
              </td>
              <td class="left"><?php echo $affiliate['name']; ?></td>
              <td class="left"><?php echo $affiliate['email']; ?></td>
              <td class="left"><?php echo $affiliate['status']; ?></td>
              <td class="left"><?php echo $affiliate['approved']; ?></td>
              <td class="left"><?php echo $affiliate['date_added']; ?></td>
              <td class="right"><?php foreach ($affiliate['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
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