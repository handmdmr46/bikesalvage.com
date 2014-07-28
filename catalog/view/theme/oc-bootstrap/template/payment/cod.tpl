<div class="pull-right">
	<div class="wait" style="display: none;"><b>Please wait, your order is being processed.....</b><img src="catalog/view/theme/childtheme/image/loading.gif" alt="" width="20" height="20" /></div>
	<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-info" onclick="startProgressBar();" />
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

	function startProgressBar(){ 
		$('.wait').show();  $('.btn').hide();
	}

//--></script> 