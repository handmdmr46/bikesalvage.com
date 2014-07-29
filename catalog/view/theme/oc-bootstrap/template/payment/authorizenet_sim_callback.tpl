<h1><?php echo $x_response_reason_text; ?></h1>
<?php if($x_response_code == '1') { ?>
<p>Your payment was processed successfully. Here is your receipt:</p>
<pre>
<?php echo $exact_ctr; ?></pre>
<?php if(!empty($exact_issname)) { ?>
<p>Issuer: <?php echo $exact_issname; ?><br/>
  Confirmation Number: <?php echo $exact_issconf; ?> </p>
<?php } ?>
<div class="pull-right">
	<a href="<?php echo $confirm; ?>" class="btn btn-info"><?php echo $button_confirm; ?></a>
</div>
<?php } elseif($_REQUEST['x_response_code'] == '2') { ?>
<p>Your payment failed.  Here is your receipt.</p>
<pre>
<?php echo $exact_ctr; ?></pre>
<div class="pull-left">
	<div class="wait attention" style="display: none;"><b>Please wait, returning to checkout.....</b><img src="catalog/view/theme/default/image/loading.gif" alt="" width="20" height="20" /></div>
    <a href="<?php echo $back; ?>" class="btn btn-info"><?php echo $button_back; ?></a>
</div>
<?php } else { ?>
<p>An error occurred while processing your payment. Please try again later.</p>
<div class="pull-left">
	<div class="wait attention" style="display: none;"><b>Please wait, returning to checkout.....</b><img src="catalog/view/theme/default/image/loading.gif" alt="" width="20" height="20" /></div>
    <a href="<?php echo $back; ?>" class="btn btn-info"><?php echo $button_back; ?></a>
</div>
<?php } ?>

<script type="text/javascript"><!--
  function startProgressBar(){ 
    $('.wait').show();  $('.btn').hide();
  }
//--></script>