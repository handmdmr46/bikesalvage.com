<?php echo $header; ?>

<div id="content">

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>



  <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <?php if ($error) { ?>
    <div class="warning"><?php print_r($error); ?></div>
  <?php } ?>

 <div class="box">
    <div class="heading">
      <h1><img src="view/image/download.png" alt="" /> <?php echo $heading_title; ?></h1>
      <h1 class="wait" style="margin-left:50px; display: none;">Please Wait, this may take awhile..... &nbsp;<img src="view/image/loading.gif" alt="" width="20" height="20" /></h1>
      <div class="buttons">
        <a onclick="$('#form').attr('action', '<?php echo $build_list; ?>'); $('#form').submit(); start_import();" class="button">Get SellerList</a>
        <a onclick="$('#form').attr('action', '<?php echo $truncate_list; ?>'); $('#form').submit();" class="button">Truncate SellerList</a>
        <a onclick="$('#form').attr('action', '<?php echo $synchronize; ?>'); $('#form').submit();" class="button">Synchronize Products</a>
      </div>
    </div><!-- .heading -->

    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td width="10%"><span class="required">* </span>Start From<span class="help">Specifies the earliest (oldest) date to use in a date range filter based on item start time</span></td>
            <td>
              <input name="start_date" id="start-date" type="text" value="<?php if(!empty($start_date)) { echo $start_date; } ?>" required>
            </td>
            <td></td>
          </tr>
          <tr>
            <td><span class="required">* </span>Start To<br><span class="help"><span class="help">Specifies the latest (most recent) date to use in a date range filter based on item start time</span></td>
            <td width="10%">
              <input name="end_date" id="end-date" type="text" value="<?php if(!empty($end_date)) { echo $end_date; } ?>" required>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Total Item Count:</td>
            <td width="10%"><?php echo $item_count; ?></td>
            <td></td>
          </tr>
        </table>
      </form>

      <table class="list" cellpadding="2">
        <thead>
            <tr>
              <td class="left width200">ItemID</td>
              <td class="left width75">Title</td>
              <td class="left width75">Listing Status</td>
              <td class="left width75">End Time</td>
            </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td><input type="text" name="filter_id" value="<?php echo $filter_id; ?>" /></td>
            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
            <td>
              <select name="filter_status">
                <option value=''>Select All</option>
                <?php if($filter_status == 'Active') { ?>
                  <option selected value='Active'>Active</option>
                <?php } else { ?>
                  <option value='Active'>Active</option>
                <?php } ?>
                <?php if($filter_status == 'Completed') { ?>
                  <option selected value='Completed'>Completed</option>
                <?php } else { ?>
                  <option value='Completed'>Completed</option>
                <?php } ?>
              </select>
            </td>
            <td><input type="text" name="filter_date" value="<?php echo $filter_date; ?>" /><a onclick="filter();" class="button" style="float:right;">Filter Results</a></td>
          </tr>
          <?php if($seller_list) { ?>
            <?php foreach($seller_list as $product) { ?>
              <tr>
                <td><?php echo $product['ebay_item_id']; ?></td>
                <td><?php echo $product['ebay_title']; ?></td>
                <td><?php echo $product['listing_status']; ?></td>
                <td><?php echo $product['end_time']; ?></td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>

      <div class="pagination"><?php echo $pagination; ?></div>

    </div><!-- .content -->

  </div><!-- .box -->
</div><!-- #content -->

<script type="text/javascript"><!--
  $(document).ready(function() {
    $('#start-date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#end-date').datepicker({dateFormat: 'yy-mm-dd'});
  });

  function start_import() { $('.wait').show(); }

  function filter() {
    url = 'index.php?route=inventory/ebay_seller_list&token=<?php echo $token; ?>';

    var filter_name   = $('input[name=\'filter_name\']').attr('value');
    var filter_status = $('select[name=\'filter_status\']').attr('value');
    var filter_date   = $('input[name=\'filter_date\']').attr('value');
    var filter_id     = $('input[name=\'filter_id\']').attr('value');

    if (filter_name) {
      url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    if (filter_status) {
      url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    if (filter_date) {
      url += '&filter_date=' + encodeURIComponent(filter_date);
    }

    if (filter_id) {
      url += '&filter_id=' + encodeURIComponent(filter_id);
    }

    location = url;
  }
//--></script>

<?php echo $footer; ?>