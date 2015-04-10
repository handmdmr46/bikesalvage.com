<?php echo $header; ?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb hidden-xs">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ol>
    </div>
    <?php echo $column_left; ?>
    <?php echo $column_right; ?>
    <div id="content" class="col-md-8">
        <?php echo $content_top; ?>

        <section>
            <h1><?php echo $heading_title; ?></h1>
        </section>
        <h2><?php echo $text_advanced_search_title; ?></h2>
        <p><?php echo $text_search_by; ?></p>
        <div class="row">
            <div class="col-sm-10">
                <label class="control-label" for="input-search"><?php echo $entry_keyword; ?></label>
                <input class="form-control" type="text" name="search" id="keyword" value="<?php echo $search; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <label class="control-label" for="manufacturer"><?php echo $entry_manufacturer; ?></label>
                <select class="form-control" name="manufacturer_id" id="manufacturer_id">
                    <option value="0"><?php echo $text_select_manufacturer; ?></option>
                    <?php foreach($manufacturers as $manufacturer) { ?>
                        <?php if ($manufacturer['manufacturer_id'] == $man_id) { ?>
                            <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <label class="control-label" for="model"><?php echo $entry_model; ?></label>
                <select  class="form-control" name="category_id" id="category_id">
                    <option value="0"><?php echo $text_select_model; ?></option>
                    <?php foreach ($categories as $category) {
                        if ($category['category_id'] == $category_id) { ?>
                            <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                &nbsp;
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <input type="button" value="Search Parts" id="button-search" class="btn btn-info" />
                <label class="checkbox-inline"><input type="checkbox" name="description" value="1" id="description" />Keyword search in product description</label>
            </div>
        </div>

        <br />
        <?php if ($products) { ?>
            <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare_total"><?php echo $text_compare; ?></a></div>
            <div class="product-filter">
                <div class="display">
                    <div class="btn-group">
                        <a id="list-view" class="btn btn-default tooltip-item" data-toggle="tooltip" title="<?php echo $this->language->get('button_list'); ?>" onclick="showDescription()"><span class="glyphicon glyphicon-th-list"></span></a>
                        <a id="grid-view" class="btn btn-default tooltip-item" data-toggle="tooltip" title="<?php echo $this->language->get('button_grid'); ?>" onclick="hideDescription()"><span class="glyphicon glyphicon-th"></span></a>
                    </div>
                </div>
                <div class="limit">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_limit; ?></span>
                        <select class="form-control" onchange="location = this.value;">
                            <?php foreach ($limits as $limits) {
                                if ($limits['value'] == $limit) { ?>
                                    <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="sort">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_sort; ?></span>
                        <select class="form-control" onchange="location = this.value;">
                            <?php foreach ($sorts as $sorts) {
                                if ($sorts['value'] == $sort . '-' . $order) { ?>
                                    <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="product-grid clearfix">
                    <?php foreach ($products as $product) { ?>
                        <article class="col-md-3">
                            <div class="product-inner">
                                <?php if ($product['thumb']) { ?>
                                    <div class="image">
                                        <a href="<?php echo $product['href']; ?>">
                                            <img src="<?php echo $product['thumb']; ?>" title="Click to see more details on <?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="image">
                                        <a href="<?php echo $product['href']; ?>">
                                            <img src="catalog/view/theme/<?php echo $this->config->get('config_template');?>/image/no-image.jpg" title="Click to see more details on <?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="description">
                                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                </div>
                                <?php if ($product['price']) { ?>
                                    <div class="price">
                                        <?php if (!$product['special']) {
                                            echo $product['price'];
                                        } else { ?>
                                            <span class="price-old"><?php echo $product['price']; ?></span>
                                            <span class="price-new"><?php echo $product['special']; ?></span>
                                        <?php }
                                        if ($product['tax']) { ?>
                                            <br />
                                            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="rating">
                                    <?php if ($product['rating']) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template');?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
                                    <?php } ?>
                                </div>
                                <div class="clearfix">
                                    <a class="btn btn-default tooltip-item" title="Add to Cart" data-toggle="tooltip" onclick="addToCart('<?php echo $product['product_id']; ?>');"><span class="glyphicon glyphicon-shopping-cart"></span></a>
                                    <a class="btn btn-default tooltip-item" title="Add to Wishlist" data-toggle="tooltip" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><span class="glyphicon glyphicon-heart"></span></a>
                                    <a class="btn btn-default tooltip-item" title="Add to Compare" data-toggle="tooltip" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><span class="glyphicon glyphicon-refresh"></span></a>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
            <div class="pagination"><?php echo $pagination; ?></div>
        <?php } ?>
        <?php echo $content_bottom; ?>
    </div>
</div>

<script type="text/javascript"><!--
    $('#keyword').focus();

    $('#button-search').on('click', function() {
        url = 'index.php?route=product/search';

        var search = $('#content input[name=\'search\']').prop('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        var category_id = $('#content select[name=\'category_id\']').prop('value');
        if (category_id > 0) {
            url += '&category_id=' + encodeURIComponent(category_id);
        }

        var manufacturer_id = $('#content select[name=\'manufacturer_id\']').prop('value');
        if (manufacturer_id > 0) {
            url += '&manufacturer_id=' + encodeURIComponent(manufacturer_id);
        }

        var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');
        if (sub_category) {
            url += '&sub_category=true';
        }

        var filter_description = $('#content input[name=\'description\']:checked').prop('value');
        if (filter_description) {
            url += '&description=true';
        }

        location = url;
    });

    $('#content input[name=\'search\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            $('#button-search').trigger('click');
        }
    });

    $('select[name=\'manufacturer_id\']').bind('change', function() {
        $.ajax({
            url: 'index.php?route=product/search/manufacturerToCategory&manufacturer_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('.loader').after('<span class="wait">&nbsp;<img src="catalog/view/theme/oc-bootstrap/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                html = '<option value="0"><?php echo $text_select_model; ?></option>';
                if (json['categories'] != '') {
                    for (i = 0; i < json['categories'].length; i++) {
                        // with pre-selected categories
                        var category_id = $('#content select[name=\'category_id\']').prop('value');
                        if (json['categories'][i]['category_id'] == category_id ) {
                            html += '<option value="' + json['categories'][i]['category_id'] + '" selected="selected">' + json['categories'][i]['name'] + '</option>';
                        } else {
                            html += '<option value="' + json['categories'][i]['category_id'] + '">' + json['categories'][i]['name'] + '</option>';
                        }
                    }
                } else {
                    html = '<option value="0"><?php echo $text_select_model; ?></option>';
                }

                $('select[name=\'category_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'manufacturer_id\']').trigger('change');

    $('select[name=\'category_id\']').on('change', function() {
        if (this.value == '0') {
            $('input[name=\'sub_category\']').prop('disabled', true);
        } else {
            $('input[name=\'sub_category\']').prop('disabled', false);
        }
    });

    $('select[name=\'category_id\']').trigger('change');
--></script>

<?php echo $footer; ?>