<?php if($use_box){ ?>
	<div class="box">
	  <div class="box-heading"><?php echo $heading_title; ?></div>
	  <div class="box-content">
	  
		<div style="margin-bottom: 10px;"><?php echo $script; ?></div>
		
	  </div>
	</div>
<?php } else { ?>
	<div style="margin-bottom: 10px;">
		<?php echo $script; ?>
	</div>
<?php } ?>