<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      	<a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
      	<a onclick="$('#form').attr('action', '<?php echo $edit_list; ?>'); $('#form').submit();" class="button"><?php echo $button_edit_list; ?></a>
     </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>              
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>              
              <td class="left"><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
              <td class="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
              <td class="left"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
              <td class="center"><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $button_search; ?></a></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;">
                <?php //echo $product['product_id']; ?>
                <?php //echo $product['affiliate_id']; ?>
                <?php if ($product['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" id="<?php echo $product['product_id']; ?>_select" checked="checked" />
                <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" id="<?php echo $product['product_id']; ?>_select" />
                <?php } ?></td>
              <!-- Image -->
              <td class="center">
              	<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" />
              </td>
              <!-- Name -->
              <td class="left"><?php echo $product['name']; ?></td>
              <!-- Model -->
              <td class="left">
                <input type="text" 
                       name="<?php echo $product['product_id']; ?>_model" 
                       id="<?php echo $product['product_id']; ?>_select" 
                       value="<?php echo $product['model']; ?>" 
                       onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'/>
              </td>
              <!-- Price -->
              <td class="left">
                <input type="text" 
                	   name="<?php echo $product['product_id']; ?>_price" 
                	   id="<?php echo $product['product_id']; ?>_select" 
                	   value="<?php echo $product['price']; ?>" 
                	   onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'/>
          	  </td>
              <!-- Quantity -->
              <td class="left"><?php if ($product['quantity'] <= 0) { ?>              
                <span style="color: #FF0000;">
                  <input type="text" 
                         name="<?php echo $product['product_id']; ?>_quantity" 
                         id="<?php echo $product['product_id']; ?>_select" 
                         value="<?php echo $product['quantity']; ?>" 
                         onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'/>
            	</span>
                <?php } elseif ($product['quantity'] <= 5) { ?>                
                <span style="color: #FFA500;">
                  <input type="text" 
                  		 name="<?php echo $product['product_id']; ?>_quantity" 
                  		 id="<?php echo $product['product_id']; ?>_select" 
                  		 value="<?php echo $product['quantity']; ?>" 
                  		 onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'/>
                </span>
                <?php } else { ?>                
                <span style="color: #008000;">
                  <input type="text" 
                         name="<?php echo $product['product_id']; ?>_quantity" 
                         id="<?php echo $product['product_id']; ?>_select" 
                         value="<?php echo $product['quantity']; ?>" 
                         onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'/>
                </span>
                <?php } ?>
              </td>
              <!-- Status -->
              <td class="center">
                <select name="<?php echo $product['product_id']; ?>_status" id="<?php echo $product['product_id']; ?>_select" onclick='document.getElementById("<?php echo $product['product_id']; ?>_select").setAttribute("checked","checked");'> 
                  <?php if ($product['status'] == $text_enabled) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  function edit(id) {
  	
  	document.getElementById(id + '_select').setAttribute("checked","checked");
  }

  $(document).ready(function() {
	$('.editable').focus(function() {
		$(this).addClass("focusField");		
      if (this.value == this.defaultValue){
        this.select();
    	}
      if(this.value != this.defaultValue){
	    	this.select();
      }
    });
    $('.editable').change(function() {
    	$(this).removeClass("focusField");				
      if (this.value == ''){
        this.value = (this.defaultValue ? this.defaultValue : '');			
    	}				
   });	
//--></script>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=catalog/product_upload/getList&token=<?php echo $token; ?>';
		
		var filter_name = $('input[name=\'filter_name\']').attr('value');
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		var filter_model = $('input[name=\'filter_model\']').attr('value');
		
		if (filter_model) {
			url += '&filter_model=' + encodeURIComponent(filter_model);
		}
		
		var filter_price = $('input[name=\'filter_price\']').attr('value');
		
		if (filter_price) {
			url += '&filter_price=' + encodeURIComponent(filter_price);
		}
		
		var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
		
		if (filter_quantity) {
			url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
		}
		
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		
		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}	

		location = url;
	}
//--></script> 
<script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
//--></script> 
<script type="text/javascript"><!--
	$('input[name=\'filter_name\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product_upload/getList/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'filter_name\']').val(ui.item.label);
							
			return false;
		},
		focus: function(event, ui) {
	      	return false;
	   	}
	});

	$('input[name=\'filter_model\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product_upload/getList/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.model,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'filter_model\']').val(ui.item.label);
							
			return false;
		},
		focus: function(event, ui) {
	      	return false;
	   	}
	});
//--></script> 
<?php echo $footer; ?>