<?php echo $header; ?>

<div id="content">

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div><!-- .breadcrumb -->
  
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
      	<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
      	<a href="<?php echo $product_list; ?>" class="button"><?php echo $button_product_list; ?></a>
      	<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
    </div>
  </div><!-- .box -->
    
    <div class="content">
   
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
        <div id="languages">
          <?php foreach ($languages as $language) { ?>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
          <?php } ?>
        </div>
        
        <?php foreach ($languages as $language) { ?>
          
            <table class="form" style="border-collapse:separate;">
              <!-- NAME -->
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td>
                  <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <!-- MODEL -->
              <tr>
                <td><span class="required">*</span> <?php echo $entry_model; ?></td>
                <td>
                  <input type="text" name="model" value="<?php echo $model; ?>" />
                  <?php if ($error_model) { ?>
                  <span class="error"><?php echo $error_model; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <!-- DESCRIPTION -->
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>
              <!-- LENGHT - WIDTH - HEIGHT -->
              <tr>
                <td><?php echo $entry_dimension; ?></td>
                <td>
                  <input type="text" name="length" value="<?php echo $length; ?>" size="4" />
                  <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
                  <input type="text" name="height" value="<?php echo $height; ?>" size="4" />
                </td>
              </tr>
              <!-- WEIGHT -->
              <tr>
                <td><?php echo $entry_weight; ?></td>
                <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
              </tr>
              <!-- MANUFACTURER -->
              <tr>
                <td><?php echo $entry_manufacturer; ?></td>
                <td><input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" /><input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" /></td>
              </tr>
              <!-- CATEGORY -->
              <tr>
                <td><?php echo $entry_category; ?></td>
                <td><input type="text" name="category" value="" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
                  <div id="product-category" class="scrollbox">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($product_categories as $product_category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                      <div id="product-category<?php echo $product_category['category_id']; ?>" class="<?php echo $class; ?>">
                        <?php echo $product_category['name']; ?>
                        <img src="view/image/delete.png" alt="" />
                        <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                      </div>
                    <?php } ?>
                  </div>
                </td>
              </tr> 
              <!-- SHIPPING METHODS -->
              <tr>
              	  <td><?php echo $entry_shipping_methods; ?></td>
                <td>
                  <div id="shipping-methods" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach($shipping_method as $shipping_methods) {  ?>
                  	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    	<div class="<?php echo $class; ?>">
                        <?php if (in_array($shipping_methods['shipping_id'], $shipping_type)) { ?><!-- in_array($search,$array,$type) $search=Specifies the what to search for $array=Specifies the array to search $type=boolean, specifies type -->
                        <input type="checkbox" name="shipping_type[]" value="<?php echo $shipping_methods['shipping_id']; ?>" checked="checked" />
                        <?php echo $shipping_methods['method_name']; ?>
                        <?php echo "({$shipping_methods['zone']})"; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="shipping_type[]" value="<?php echo $shipping_methods['shipping_id']; ?>" />
                        <?php echo $shipping_methods['method_name']; ?>
                        <?php echo "({$shipping_methods['zone']})"; ?>
                        <?php } ?>
                  	</div><!-- $class = even ? odd : even -->
                  <?php } ?>
                  </div>
                </td>
              </tr>
              <!-- PRICE -->
              <tr>
                <td><?php echo $entry_price; ?></td>
                <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
              </tr>
              <!-- QUANTITY -->
              <tr>
                <td><?php echo $entry_quantity; ?></td>
                <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
              </tr>
              <!-- FEATURED IMAGE -->
              <tr>
                <td><?php echo $entry_featured_image; ?></td>         
                <td>
              	  <div class="image">
              	    <img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />                                 
                    <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
                  </div>
                  &nbsp;&nbsp;  &nbsp;&nbsp;<input type="text" name="image" value="<?php echo $image; ?>" id="image" size="50" />
                </td>
              </tr>
            </table>
            <!-- GALLERY IMAGES -->
            <table id="images" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_gallery_image_link; ?></td>
                  <td class="right"><?php echo $entry_sort_order; ?></td>
                  <td></td>
                </tr>
              </thead>
              <?php $image_row = 0; ?>
              <?php foreach ($product_images as $product_image) { ?>
              <tbody id="image-row<?php echo $image_row; ?>">
                <tr>                  
	                <td class="left">
	                	<div class="image">
	                		<img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" /><br />
	                		<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>
	                		&nbsp;&nbsp;|&nbsp;&nbsp;
	                		<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
	                	</div>
	                    &nbsp;&nbsp;  &nbsp;&nbsp;<input type="text" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" size="50" />                                        
	                </td>
	                <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
	                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                </tr>
              </tbody>
              <?php $image_row++; ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image_link; ?></a></td>
                </tr>
              </tfoot>
            </table>
            
          <?php } ?>
      </form> 
    
    </div><!-- .content -->

    
</div><!-- #content -->       
        
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 

<script type="text/javascript"><!--
	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
	<?php } ?>
//--></script> 

<script type="text/javascript"><!--
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';
			
			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
					
					currentCategory = item.category;
				}
				
				self._renderItem(ul, item);
			});
		}
	});

	// Manufacturer
	$('input[name=\'manufacturer\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.manufacturer_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'manufacturer\']').attr('value', ui.item.label);
			$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
		
			return false;
		},
		focus: function(event, ui) {
	      return false;
	   }
	});

	// Category
	$('input[name=\'category\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.category_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-category' + ui.item.value).remove();
			
			$('#product-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_category[]" value="' + ui.item.value + '" /></div>');

			$('#product-category div:odd').attr('class', 'odd');

			$('#product-category div:even').attr('class', 'even');
					
			return false;
		},
		focus: function(event, ui) {
	      return false;
	   }
	});

	$('#product-category div img').live('click', function() {
		$(this).parent().remove();

		$('#product-category div:odd').attr('class', 'odd');

		$('#product-category div:even').attr('class', 'even');	
	});

	// Filter
	$('input[name=\'filter\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.filter_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-filter' + ui.item.value).remove();
			
			$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');

			$('#product-filter div:odd').attr('class', 'odd');
			$('#product-filter div:even').attr('class', 'even');
					
			return false;
		},
		focus: function(event, ui) {
	      return false;
	   }
	});

	$('#product-filter div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');	
	});

	$('#product-download div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-download div:odd').attr('class', 'odd');
		$('#product-download div:even').attr('class', 'even');	
	});

	// Related -- DONT NEED? -- in links tab
	$('input[name=\'related\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
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
			$('#product-related' + ui.item.value).remove();
			
			$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

			$('#product-related div:odd').attr('class', 'odd');
			$('#product-related div:even').attr('class', 'even');
					
			return false;
		},
		focus: function(event, ui) {
	      return false;
	   }
	});

	$('#product-related div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');	
	}); 
//--></script> 

<script type="text/javascript"><!--
	function image_upload(field, thumb) {
		$('#dialog').remove();
		
		$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
		
		$('#dialog').dialog({
			title: '<?php echo $text_image_manager; ?>',
			close: function (event, ui) {
				if ($('#' + field).attr('value')) {
					$.ajax({
						url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
						dataType: 'text',
						success: function(text) {
							$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
						}
					});
				}
			},	
			bgiframe: false,
			width: 800,
			height: 400,
			resizable: false,
			modal: false
		});
	};
//--></script> 

<script type="text/javascript"><!--
	var image_row = <?php echo $image_row; ?>;

	function addImage() {
	    html  = '<tbody id="image-row' + image_row + '">';
		html += '<tr>';
		html += '<td class="left">';
		html += '<div class="image">';
		html += '<img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" />';
		html += '<br />';
		html += '<a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>';
		html += '&nbsp;&nbsp;|&nbsp;&nbsp;';
		html += '<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a>';
		html += '</div>';		
		html += '&nbsp;&nbsp;  &nbsp;&nbsp;';
		html += '<input type="text" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" size="50" />';
		html += '</td>';
		html += '<td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
		html += '<td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '</tr>';
		html += '</tbody>';
		
		$('#images tfoot').before(html);
		
		image_row++;
	}
//--></script> 

 
<?php echo $footer; ?>
