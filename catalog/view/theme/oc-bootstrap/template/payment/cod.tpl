<div class="pull-right">
	<!-- <div class="wait attention" style="display: none;"><b>Please wait, your order is being processed.....</b><img src="catalog/view/theme/default/image/loading.gif" alt="" width="20" height="20" /></div> -->
	<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-info" />
</div>

<script type="text/javascript"><!--

	$('#button-confirm').bind('click', function() {
		$.ajax({ 
			type: 'get',
			url: 'index.php?route=payment/cod/confirm',
			beforeSend: function() {
  			  $('#button-confirm').attr('disabled', true);
  			  $('#button-confirm').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
  		    },
  		    complete: function() {
  			  $('#button-confirm').attr('disabled', false);
  			  $('.attention').remove();
  		    },
			success: function() {
				location = '<?php echo $continue; ?>';
			}		
		});

		// $('.wait').show();  $('.btn').hide();
	});

//--></script> 