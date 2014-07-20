<?php if (count($currencies) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency_form">
  <div class="currency switcher">
    <?php foreach ($currencies as $currency) { ?>
    <?php if ($currency['code'] == $currency_code) { ?>
    <div class="selected"><a><?php echo $currency['title']; ?></a></div>
    <?php } ?>
    <?php } ?>
    <div class="option">
      <?php foreach ($currencies as $currency) { ?>
      <a onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $('#currency_form').submit();"><?php echo $currency['title']; ?></a>
      <?php } ?>
    </div>
  </div>
  <div style="display: inline;">
    <input type="hidden" name="currency_code" value="" />
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
  </div>
</form>
<?php } ?>
