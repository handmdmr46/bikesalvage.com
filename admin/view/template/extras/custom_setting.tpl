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
                      <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $config_affiliate_order_complete_status_id) { ?>
                          <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
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