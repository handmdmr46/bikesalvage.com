<!DOCTYPE html>

<!--[if IE 7]>                  <html class="ie7 no-js" lang="<?php echo $lang; ?>" dir="<?php echo $direction; ?>">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="<?php echo $lang; ?>" dir="<?php echo $direction; ?>">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="<?php echo $lang; ?>" dir="<?php echo $direction; ?>">  <!--<![endif]-->

<head>
	
	<!-- Charset
    ================================================== -->
	<meta charset="UTF-8">
    
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame. 
    Remove meta X-UA-Compatible if you use the .htaccess
    ================================================== -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <!-- Mobile Specific Metas
    ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- Basic Page Needs
    ================================================== -->
	<title><?php echo $title; ?></title>
	
	<base href="<?php echo $base; ?>" >
	
	<?php if ($description) { ?>
		<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
		<meta name="keywords" content="<?php echo $keywords; ?>" />
	<?php } ?>
	<meta name="author" content="<?php echo $name; ?>">
    <meta name="DC.creator" content="Neil Smart - http://the1path.com" />
	
    <!-- Favicons
	================================================== -->
	<?php if ($icon) { ?>
		<link href="<?php echo $icon; ?>" rel="icon" />
		<link rel="shortcut icon" href="<?php echo $icon; ?>">
		<link rel="apple-touch-icon" href="<?php echo $icon; ?>">
	<?php } ?>
	
	<?php foreach ($links as $link) { ?>
		<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>
    
    <!-- Stylesheets
	================================================== -->
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template');?>/bootstrap/css/bootstrap.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template');?>/stylesheet/stylesheet.css" media="screen, projection" />
	<?php foreach ($styles as $style) { ?>
		<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	
    <!-- Wheres all the JS? Check out the footer :)
	================================================== -->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template');?>/js/modernizr.js">
		if (!Modernizr.inputtypes['date']) {
			$('input[type=date]').datepicker();   
		}​
	</script>
    <?php foreach ($scripts as $script) { ?>
		<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php } ?>
	
	<?php if ($stores) { ?>
		<script type="text/javascript">
            $(document).ready(function() {
                <?php foreach ($stores as $store) { ?>
                    $('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
                <?php } ?>
            });
        </script>
    <?php } ?>
	<?php echo $google_analytics; ?>
    <?php
        // If Theme Options module is enabled
        if($this->config->get('themeoptions_status')== 1) {
        		
            $regfonts = array('Arial', 'Verdana', 'Helvetica', 'Lucida Grande', 'Trebuchet MS', 'Times New Roman', 'Tahoma', 'Georgia');	
            
            // Titles font
            if (($this->config->get('themeoptions_title_font')!='') && (in_array($this->config->get('themeoptions_title_font'), $regfonts)==false)) { ?>
                <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('themeoptions_title_font') ?>&amp;v1' rel='stylesheet' type='text/css'>
            <?php } 
            // Body font
            if (($this->config->get('themeoptions_body_font')!='') && (in_array($this->config->get('themeoptions_body_font'), $regfonts)==false)) { ?>
                <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('themeoptions_body_font') ?>&amp;v1' rel='stylesheet' type='text/css'>
            <?php }
            // Small text font
            if (($this->config->get('themeoptions_small_font')!='') && (in_array($this->config->get('themeoptions_small_font'), $regfonts)==false)) { ?>
                <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('themeoptions_small_font') ?>&amp;v1' rel='stylesheet' type='text/css'>
            <?php } ?>
        
			<style type="text/css">
            body {
                <?php
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                    $path_image = $this->config->get('config_ssl') . 'image/';
                } else {
                    $path_image = $this->config->get('config_url') . 'image/';
                }
				
				if ($this->config->get('themeoptions_no_bg')== 1) { ?>
					background-image: none;
                <?php } else if ($this->config->get('themeoptions_custom_image')!='') { ?>
                    background-image: url("<?php echo $path_image . $this->config->get('themeoptions_custom_image') ?>"); ?>;
                    background-position: top center;
                    background-repeat: no-repeat;
                <?php } else if ($this->config->get('themeoptions_custom_pattern')!='') { ?>
                    background-image: url("<?php echo $path_image . $this->config->get('themeoptions_custom_pattern') ?>"); ?>;
					background-position: top left;
                    background-repeat: repeat;
                <?php } else if ($this->config->get('themeoptions_pattern_overlay')!='default') { ?>
                    background-image: url("admin/view/image/patterns/<?php echo $this->config->get('themeoptions_pattern_overlay'); ?>.png");
					background-position: top left;
                    background-repeat: repeat;
                <?php } ?>
            }
			
            <?php if ($this->config->get('themeoptions_custom_colours') == 1) { ?>
                body {
                    background-color: <?php echo $this->config->get('themeoptions_background_colour'); ?>;
                }
                h1 {
                    color: <?php echo $this->config->get('themeoptions_h1_title_colour'); ?>;
                }
                h2 {
                    color: <?php echo $this->config->get('themeoptions_h2_title_colour'); ?>;
                }
                h3 {
                    color: <?php echo $this->config->get('themeoptions_h3_title_colour'); ?>;
                }
                h4 {
                    color: <?php echo $this->config->get('themeoptions_h4_title_colour'); ?>;
                }
                h5 {
                    color: <?php echo $this->config->get('themeoptions_h5_title_colour'); ?>;
                }
                h6 {
                    color: <?php echo $this->config->get('themeoptions_h6_title_colour'); ?>;
                }
                p {
                    color: <?php echo $this->config->get('themeoptions_bodytext_colour'); ?>;
                }
                a {
                    color: <?php echo $this->config->get('themeoptions_content_links_colour'); ?>;
                }
                a:hover {
                    color: <?php echo $this->config->get('themeoptions_content_links_hover_colour'); ?>;
                }
                
				.main-navbar {
                    background: <?php echo $this->config->get('themeoptions_menu_background'); ?>;
					border-color: <?php echo $this->config->get('themeoptions_menu_border'); ?>;
                }
                .main-navbar .nav > li > a {
                    color: <?php echo $this->config->get('themeoptions_menu_colour'); ?>;
                }
                .main-navbar .nav > li > a:hover {
                    color: <?php echo $this->config->get('themeoptions_menu_hover'); ?>;
                }
				.main-navbar .navbar-nav > .dropdown:active > a, .main-navbar .navbar-nav > .dropdown:hover > a, .main-navbar .navbar-nav > .dropdown:focus > a {
					color: <?php echo $this->config->get('themeoptions_menu_hover'); ?>;
					background: <?php echo $this->config->get('themeoptions_menu_hover_background'); ?>;
				}
                .main-navbar .dropdown-menu li a, .see-all {
                    color: <?php echo $this->config->get('themeoptions_dropdown_colour'); ?>;
                }
                .main-navbar .dropdown-menu li a:hover {
					color: <?php echo $this->config->get('themeoptions_dropdown_hover'); ?>;
                }
				.see-all:hover {
					color: <?php echo $this->config->get('themeoptions_dropdown_hover'); ?>;
					background: <?php echo $this->config->get('themeoptions_menu_hover_background'); ?>;
				}
				.see-all {
					background: <?php echo $this->config->get('themeoptions_dropdown_hover_bg'); ?>;
				}
                .main-navbar li > .dropdown-menu {
                    background: <?php echo $this->config->get('themeoptions_dropdown_background'); ?>;
                }
                
				.topbar {
                    background: <?php echo $this->config->get('themeoptions_topmenu_background'); ?>;
                }
                .navbar-default .navbar-nav > li > a {
                    color: <?php echo $this->config->get('themeoptions_topmenu_colour'); ?>;
                }
                .navbar-default .navbar-nav > li > a:hover {
                    color: <?php echo $this->config->get('themeoptions_topmenu_hover_colour'); ?>;
                }
				#currency a {
					color: <?php echo $this->config->get('themeoptions_currency_colour'); ?>;
					background: <?php echo $this->config->get('themeoptions_currency_background'); ?>;
				}
				#currency a:hover {
					color: <?php echo $this->config->get('themeoptions_currency_hover_colour'); ?>;
					background: <?php echo $this->config->get('themeoptions_currency_hover_background'); ?>;
				}
				
				.breadcrumb {
					background: <?php echo $this->config->get('themeoptions_breadcrumb_background'); ?>;
				}
                .breadcrumb a {
                    color: <?php echo $this->config->get('themeoptions_breadcrumb_links_colour'); ?>;
                }
                .breadcrumb a:hover {
                    color: <?php echo $this->config->get('themeoptions_breadcrumb_links_hover_colour'); ?>;
                }
                
                .product-info .price-tax, .product-info .price .reward, .product-info .cart .minimum {
                    color: <?php echo $this->config->get('themeoptions_lighttext_colour'); ?>;
                }
                
                #footer h3 {
                    color: <?php echo $this->config->get('themeoptions_footer_header_colour'); ?>;
                }
                #powered, #powered p, #the1path, #the1path a, #the1path p {
                    color: <?php echo $this->config->get('themeoptions_footer_text_colour'); ?>!important;
                }
                #footer a {
                    color: <?php echo $this->config->get('themeoptions_footer_links_colour'); ?>;
                }
                #footer a:hover {
                    color: <?php echo $this->config->get('themeoptions_footer_links_hover_colour'); ?>;
                }
				
                #button-cart {
                    background: <?php echo $this->config->get('themeoptions_button_background_colour'); ?>;
                    color: <?php echo $this->config->get('themeoptions_button_text_colour'); ?>;
                }
				#cart .btn {
					background: <?php echo $this->config->get('themeoptions_checkout_colour'); ?>;
				}
				#cart .btn:hover {
					background: <?php echo $this->config->get('themeoptions_checkout_hover'); ?>;
				}
				#cart .btn h4 {
					color: <?php echo $this->config->get('themeoptions_checkout_link'); ?>;
				}
				#cart .btn:hover h4 {
					color: <?php echo $this->config->get('themeoptions_checkoutlink_hover'); ?>;
				}
				#cart .btn-info {
					border-color: <?php echo $this->config->get('themeoptions_cart_border'); ?>;
				}
                .product-list .name a, .product-grid .name a {
                    color: <?php echo $this->config->get('themeoptions_product_name_colour'); ?>;
                }
                .product-list .name a:hover, .product-grid .name a:hover {
                    color: <?php echo $this->config->get('themeoptions_product_name_hover_colour'); ?>;
                }
                .price {
                    color: <?php echo $this->config->get('themeoptions_normal_price_colour'); ?>;
                }
                .price-old {
                    color: <?php echo $this->config->get('themeoptions_old_price_colour'); ?>;
                }
                .price-new {
                    color: <?php echo $this->config->get('themeoptions_new_price_colour'); ?>;
                }
                #column-left .cat-menu .nav a, #column-right .cat-menu .nav a {
                    color: <?php echo $this->config->get('themeoptions_categories_menu_colour'); ?>;
                }
                #column-left .cat-menu .nav a:hover, #column-right .cat-menu .nav a:hover {
                    color: <?php echo $this->config->get('themeoptions_categories_menu_hover_colour'); ?>;
                }
				#column-left .cat-menu .nav a:hover {
					border-right-color: <?php echo $this->config->get('themeoptions_categories_menu_hover_colour'); ?>;
                }
				#column-right .cat-menu .nav a:hover {
					border-left-color: <?php echo $this->config->get('themeoptions_categories_menu_hover_colour'); ?>;
                }
                #column-left .cat-menu .nav .nav > li > a, #column-right .cat-menu .nav .nav > li > a {
                    color: <?php echo $this->config->get('themeoptions_categories_sub_colour'); ?>;
                }
                #column-left .cat-menu .nav .nav > li > a:hover, #column-right .cat-menu .nav .nav > li > a:hover {
                    color: <?php echo $this->config->get('themeoptions_categories_sub_hover_colour'); ?>;
                }
                #column-left .cat-menu .nav .nav > li > a:hover {
                    border-right-color: <?php echo $this->config->get('themeoptions_categories_sub_hover_colour'); ?>;
                }
				#column-right .cat-menu .nav .nav > li > a:hover {
                    border-left-color: <?php echo $this->config->get('themeoptions_categories_sub_hover_colour'); ?>;
                }
				#column-left .cat-menu .nav > .active > a, #column-right .cat-menu .nav > .active > a {
                    color: <?php echo $this->config->get('themeoptions_categories_active_colour'); ?>;
                }
				#column-left .cat-menu .nav > .active > a {
                    border-right-color: <?php echo $this->config->get('themeoptions_categories_active_colour'); ?>;
                }
				#column-right .cat-menu .nav > .active > a {
                    border-left-color: <?php echo $this->config->get('themeoptions_categories_active_colour'); ?>;
                }
				
				.topbar + .container {
					background: <?php echo $this->config->get('themeoptions_container_bg'); ?>;
				}
				#footer {
					background: <?php echo $this->config->get('themeoptions_footer_bg'); ?>;
				}
				.cat-menu > .nav {
					background: <?php echo $this->config->get('themeoptions_module_bg'); ?>;
				}
            <?php } //end Theme Options custom colours
                
                if ($this->config->get('themeoptions_body_font') != '' ) {
                    $fontpre =  $this->config->get('themeoptions_body_font');
                    $font = str_replace("+", " ", $fontpre);
                ?>
                body, p { 
                    font-family: <?php echo $font ?>; 
                    font-size: <?php echo $this->config->get('themeoptions_body_font_size'); ?>px;
                }
                <?php } 
                
                if ($this->config->get('themeoptions_title_font')!='') {
                    $fontpre =  $this->config->get('themeoptions_title_font');
                    $font = str_replace("+", " ", $fontpre);
                ?>
                h1 {
                    font-family:<?php echo $font ?>;
                    font-size: <?php echo $this->config->get('themeoptions_title_font_size'); ?>px;
                }
                <?php }
    
                if ($this->config->get('themeoptions_small_font') != '') {
                    $fontpre =  $this->config->get('themeoptions_small_font');
                    $font = str_replace("+", " ", $fontpre);
                ?>
                small, .product-compare, .dropd, .product-filter .display li, .product-list .price-tax, .product-info .price-tax, .product-info .price .reward, span.error, #copy, .breadcrumb a, .pagination .results, .help {
                    font-family:<?php echo $font ?>;
                    font-size: <?php echo $this->config->get('themeoptions_small_font_size'); ?>px;
                }
                <?php } // end Theme Options custom fonts
				
				if ($this->config->get('themeoptions_topbarscroll') == 1) { ?>
					body { padding: 0; }
					.navbar-fixed-top, .navbar-fixed-bottom { position: relative; }
				<?php } 
				if ($this->config->get('themeoptions_main_nav') == 1) { ?>
					.main-navbar { display: none; }
				<?php } 
				if ($this->config->get('themeoptions_breadcrumb') == 1) { ?>
					.breadcrumb { display: none!important; }
				<?php } 
				if ($this->config->get('themeoptions_search') == 1) { ?>
					#search { display: none; }
				<?php } 
				if ($this->config->get('themeoptions_center_logo') == 1) { ?>
					#header #logo { text-align: center; }
				<?php } 
				if ($this->config->get('themeoptions_cart') == 1) { ?>
					#header #cart { display: none; }
				<?php } 
				if ($this->config->get('themeoptions_center_heading') == 1) { ?>
					#content h1, #content h2, #content h3, #content h4, #content h5 { text-align: center; }
				<?php } ?>
            </style>
			<?php if ($this->config->get('themeoptions_topbar') == 0) { 
				$topbarposition = 'navbar-fixed-top';
			} else {
				$topbarposition = 'navbar-fixed-bottom'; ?>
                <style>
					body { padding: 0 0 50px; }
				</style>
            <?php }
            
            $status = '1'; //Theme Options is on
            
    } else { 
    	$topbarposition = 'navbar-fixed-top';
        $status = '0'; //Theme Options is off
    } 
    
    if ($this->config->get('themeoptions_custom_css') != '') { 
        echo htmlspecialchars_decode( $this->config->get('themeoptions_custom_css'), ENT_QUOTES );
    } ?>
    <!-- Theme Options End -->
    <div id="fb-root"></div>
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=316227541829170";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</head>

<body id="the1path-com">
	<?php $this->language->load('the1path/ocbootstrap'); ?>
	<nav class="topbar navbar navbar-default <?php echo $topbarposition; ?>" role="navigation">
    	<div class="container">
        	<?php echo $language; ?>
            <?php if ($status == '0' || $status == '1' && $this->config->get('themeoptions_currency') == 0) { 
            	echo $currency; 
            } ?>
        	<ul class="nav navbar-nav topbar-nav">
                <li>
                	<a href="<?php echo $wishlist; ?>" id="wishlist-total">
                    	<span class="glyphicon glyphicon-heart pull-left"></span>
                        <span class="hidden-xs hidden-sm pull-right"><?php echo $text_wishlist; ?></span>
                    </a>
                </li>
                <li>
                	<a href="<?php echo $account; ?>">
                    	<span class="glyphicon glyphicon-user pull-left"></span>
                        <span class="hidden-xs hidden-sm pull-right"><?php echo $text_account; ?></span>
                    </a>
                </li>
                <li>
                	<a href="<?php echo $shopping_cart; ?>">
                    	<span class="glyphicon glyphicon-shopping-cart pull-left"></span>
                        <span class="hidden-xs hidden-sm pull-right"><?php echo $text_shopping_cart; ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $checkout; ?>">
                        <span class="glyphicon glyphicon-share-alt pull-left"></span>
                        <span class="hidden-xs hidden-sm pull-right"><?php echo $text_checkout; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
		<header id="header" class="row clearfix">
        	<div class="<?php if ($status == '0' || $status == '1' && $this->config->get('themeoptions_center_logo') == 0) { ?>col-xs-12 col-sm-9 col-md-6 <?php } else { ?>col-md-12<?php } ?>">
                <?php if ($logo) { ?>
                    <div id="logo">
                        <a href="<?php echo $home; ?>">
                            <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
                        </a>
                    </div>
                <?php } ?>
            </div>
            <?php if ($status == '0' || $status == '1' && $this->config->get('themeoptions_center_logo') == 0) { ?>
                <div class="<?php if ($status == '0' || $this->config->get('themeoptions_cart') == 0) { ?>col-xs-12 col-sm-3 col-md-3<?php } else { ?>col-xs-12 col-sm-3 col-md-6<?php } ?>">
                    <div id="search" class="input-group">
                        <span class="input-group-addon button-search">
                            <span class="glyphicon glyphicon-search"></span>
                        </span>
                        <input name="search" type="text" class="form-control" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>">
                    </div>
                </div>
                <?php if ($status == '0' || $this->config->get('themeoptions_cart') == 0) { ?>
                    <div class="col-md-3">
                        <?php echo $cart; ?>
                    </div>
                <?php } ?>
            <?php } ?>
		</header>
        <?php if ($categories) { ?>
            <nav class="main-navbar navbar navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <span class="visible-xs navbar-brand"><?php echo $this->language->get('text_category'); ?></span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <!-- Categories -->
                   <!--  <ul class="nav navbar-nav">
                        <?php foreach ($categories as $category) { ?>
                            <?php if ($category['children']) { ?>
                                <li class="dropdown">
                                    <a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                                    <div class="dropdown-menu">
                                        <?php for ($i = 0; $i < count($category['children']);) { ?>
                                            <ul class="list-unstyled">
                                                <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                                                <?php for (; $i < $j; $i++) { ?>
                                                    <?php if (isset($category['children'][$i])) { ?>
                                                    <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                             </ul>
                                        <?php } ?>
                                        <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $this->language->get('text_all'); ?> <?php echo $category['name']; ?></a>
                                    </div>
                                </li>
                            <?php } else { ?>
                                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul> -->
                    <!-- Manufacturers -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                        </li>                         
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Search Parts</a>
                        </li>                         
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">About Us</a>
                        </li>                         
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                        </li>                         
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Affiliates</a>
                        </li>                         
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manufacturers-testing</a>
                            <div class="dropdown-menu">
                                <ul class="list-unstyled">
                                    <li><a>manufacturer-test1</a></li>
                                    <li><a>manufacturer-test2</a></li>
                                    <li><a>manufacturer-test3</a></li>
                                    <li><a>manufacturer-test4</a></li>
                                    <li><a>manufacturer-test5</a></li>
                                </ul>
                                <a href="#" class="see-all">See All Manufacturers</a>
                            </div>
                        </li>                         
                    </ul>
                </div>
            </nav>
        <?php } ?>
		<div id="notification"></div>