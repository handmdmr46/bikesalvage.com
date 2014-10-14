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

  <div class="wait success" style="display:none; background-image:none;"><img src="view/image/loading.gif" alt="" width="20" height="20" />&nbsp;  <?php echo $text_please_wait; ?></div>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/download.png" alt="" /> <?php echo $heading_title; ?></h1>
      
      <div class="buttons">
        <a onclick="start_import(); $('#form').attr('action', '<?php echo $import_ids; ?>'); $('#form').submit();" class="button" title="start the eBay ItemID import"><?php echo $button_import; ?></a>
        <a href="<?php echo $clear_dates; ?>" class="button" title="clear all eBay Import start to and start from dates"><?php echo $button_clear_dates; ?></a>
        <a href="<?php echo $cancel; ?>" class="button" title="return to admin home"><?php echo $button_cancel; ?></a>
      </div>
    </div><!-- .heading -->

    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td width="10%"><span class="required">* </span><?php echo $text_ebay_start_from; ?><span class="help"><?php echo $text_ebay_start_from_help; ?></span></td>
            <td>
              <input name="start_date" id="start-date" type="text" value="<?php if(!empty($start_date)) { echo $start_date; } ?>" required>
            </td>
            <td></td>
          </tr>
          <tr>
            <td><span class="required">* </span><?php echo $text_ebay_start_to; ?><br><span class="help"><span class="help"><?php echo $text_ebay_start_to_help; ?></span></td>
            <td width="10%">
              <input name="end_date" id="end-date" type="text" value="<?php if(!empty($end_date)) { echo $end_date; } ?>" required>
            </td>
            <td></td>
          </tr>
        </table>
              
          <div id="scheduler_here" class="dhx_cal_container" style='width:90%;height:400px; padding: 35px; margin: 20px;'>
            <div class="dhx_cal_navline" style="padding-left:5px;">
              <div class="dhx_cal_prev_button">&nbsp;</div>
              <div class="dhx_cal_next_button">&nbsp;</div>
              <div class="dhx_cal_date"></div>
            </div>
            <div class="dhx_cal_header"></div>
            <div class="dhx_cal_data"></div>
          </div>
       
      </form>
    </div><!-- .content -->
  </div><!-- .box -->
</div><!-- #content -->

<script type="text/javascript"><!--
  $(document).ready(function() {
    $('#start-date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#end-date').datepicker({dateFormat: 'yy-mm-dd'});
  });

  function start_import(){ $('.wait').show(); }
//--></script>

<script type="text/javascript" charset="utf-8">
  window.onload=function(){
    scheduler.config.xml_date="%Y-%m-%d";
    scheduler.config.year_x = 6; //2 months in a row
    scheduler.config.year_y = 2; //3 months in a column
    scheduler.init('scheduler_here', new Date(), 'year');
    scheduler.parse('<?php echo $dates; ?>', 'json');
  };
</script>

<?php echo $footer; ?>