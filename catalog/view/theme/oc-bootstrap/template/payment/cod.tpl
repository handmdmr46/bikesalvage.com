<div class="pull-right">
<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-info" />
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=payment/cod/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 