<?php echo $header; ?>

<div id="content">

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <?php if ($error) { ?>
    <div class="warning"><?php echo $error; ?></div>
  <?php } ?>

  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <div class="box">

    <div class="heading">
      <h1><img src="view/image/log.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>

    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form" style="border-collapse:separate;">
                <tr>
                  <td><?php echo $entry_international_shipping_methods; ?></td>
                  <td>
                    <select name="international_shipping_default_id">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if ($shipping_method['zone'] == 'international') { ?>
                          <?php if ($international_shipping_default_id == $shipping_method['key_id']) { ?>
                            <option value="<?php echo $shipping_method['key_id']; ?>" selected="selected"><?php echo $shipping_method['method_name']; ?> (<?php echo $shipping_method['zone']; ?>)</option>
                          <?php } else { ?>
                            <option value="<?php echo $shipping_method['key_id']; ?>"><?php echo $shipping_method['method_name']; ?> (<?php echo $shipping_method['zone']; ?>)</option>
                          <?php } ?>                          
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_domestic_shipping_methods; ?></td>
                  <td>
                    <select name="domestic_shipping_default_id">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if ($shipping_method['zone'] == 'domestic') { ?>
                          <?php if ($domestic_shipping_default_id == $shipping_method['key_id']) { ?>
                            <option value="<?php echo $shipping_method['key_id']; ?>" selected="selected"><?php echo $shipping_method['method_name']; ?> (<?php echo $shipping_method['zone']; ?>)</option>
                          <?php } else { ?>
                            <option value="<?php echo $shipping_method['key_id']; ?>"><?php echo $shipping_method['method_name']; ?> (<?php echo $shipping_method['zone']; ?>)</option>
                          <?php } ?>                    
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_affiliate_order_complete_status; ?></td>
                  <td>
                    <select name="config_affiliate_order_complete_status_id">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($transaction_statuses as $transaction_status) { ?>
                        <?php if ($transaction_status['transaction_status_id'] == $config_affiliate_order_complete_status_id) { ?>
                          <option value="<?php echo $transaction_status['transaction_status_id']; ?>" selected="selected"><?php echo $transaction_status['name']; ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $transaction_status['transaction_status_id']; ?>"><?php echo $transaction_status['name']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_category_count_minimum_sidebar; ?></td>
                  <td><input type="text" name="category_count_minimum_sidebar" value="<?php echo $category_count_minimum_sidebar; ?>" /></td>
                </tr>
                <tr>
                  <td><?php echo $entry_category_count_minimum_menu; ?></td>
                  <td><input type="text" name="category_count_minimum_menu" value="<?php echo $category_count_minimum_menu; ?>" /></td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_user_token; ?></td>
                  <td>
                    <input name="user_token" value="<?php echo $user_token; ?>" type="text" size="100" required>                   
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_developer_id; ?></td>
                  <td>
                    <input name="developer_id" value="<?php echo $developer_id; ?>" type="text" size="100" required>                    
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_application_id; ?></td>
                  <td>
                    <input name="application_id" value="<?php echo $application_id; ?>" type="text" size="100" required>                    
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_certification_id; ?></td>
                  <td>
                    <input name="certification_id" value="<?php echo $certification_id; ?>" type="text" size="100" required>                    
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_site_id; ?></td>
                  <td>
                    <select name="site_id">
                      <option value="999"><?php echo $text_select; ?></option>
                      <?php foreach($ebay_sites as $site) { ?>
                        <?php if (isset($site_id) && $site_id == $site['site_id'] ) { ?>
                          <option value="<?php echo $site['site_id']; ?>" selected><?php echo $site['site_name']; ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $site['site_id']; ?>"><?php echo $site['site_name']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>                
                  </td>
                </tr>
                <tr>
                  <td><span class="required">* </span><?php echo $text_compat_level; ?><span class="help"><?php echo $text_compat_help; ?></span></td>
                  <td>
                    <select name="compatability_level">
                      <option value="999"><?php echo $text_select; ?></option>
                      <?php foreach ($compat_levels as $level) { ?>
                        <?php if (isset($compat) && $compat == $level['level'] ) { ?>
                          <option value="<?php echo $level['level']; ?>" selected><?php echo $level['level']; ?></option>               
                        <?php } else { ?>
                          <option value="<?php echo $level['level']; ?>"><?php echo $level['level']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>                    
                  </td>
                </tr>
            </table>
        </form>
    </div><!-- .content -->
  </div><!-- .box -->
</div><!-- #content -->

<?php echo $footer; ?>