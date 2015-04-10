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
    <style type="text/css">
    /* Bikesalvage Custom menu CSS */
    body{
        background-image: url("admin/view/image/patterns/14.png");
        background-position: top left;
        background-repeat: repeat;
    }
    #main-content-area {
        background-color: #ffffff;
    }
    nav.topbar {
        background-color: #333333;
    }
    nav.topbar ul.nav li a {
        color: #ffffff;
        font-size: 16px;
    }
    nav.topbar ul.nav li a:hover {
        color: #ffffff;
    }
    #currency a {
        background: none repeat scroll 0 0 #FFFFFF;
        color: #000000;
    }
    #currency a:hover {
        color: #ffffff;
    }
    .main-navbar{
        background-color: #333333;
    }
    nav.main-navbar ul.nav li a{
        font-size: 16px;
        color: #ffffff;
    }
    nav.main-navbar ul.nav li ul li a {
        color: #999;
    }
    nav.main-navbar ul.nav li ul li a.manufacturer {
        color: #999;
        font-size: 12px;
    }
    nav.main-navbar ul.nav li ul .menu-manufacturer {
        color: #999;
        font-weight: bold;
        padding-left: 10px;
    }
    nav.main-navbar ul.nav li a.see-all {
        color: #296bbd;
    }
    nav.main-navbar ul.nav li a.see-all:hover {
        color: #ffffff;
    }
    </style>

    <?php $topbarposition = 'navbar-fixed-top'; $status = '0'; ?>


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
        	<?php /*echo $language;*/ ?>
            <?php /*if ($status == '0' || $status == '1' && $this->config->get('themeoptions_currency') == 0) {
            	echo $currency;
            } */?>
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
    <div class="container" id="main-content-area">
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

                <!-- Shopping Cart Button -->
                <?php if ($status == '0' || $this->config->get('themeoptions_cart') == 0) { ?>
                    <div class="col-md-3">

                        <?php echo $cart; ?>

                    </div>
                <?php } ?>
            <?php } ?>
		</header>

            <nav class="main-navbar navbar navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <span class="visible-xs navbar-brand"><?php echo $this->language->get('text_menu'); ?></span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <!-- Home -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="<?php echo $parts_search; ?>"><?php echo $text_parts_search; ?></a>
                        </li>
                    </ul>
                    <!-- Information -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $text_information; ?></a>
                            <div class="dropdown-menu">
                                <ul class="list-unstyled">
                                    <?php foreach ($informations as $information) { ?>
                                        <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                                    <?php } ?>
                                    <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                                    <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!-- Blog -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="<?php echo $blog; ?>"><?php echo $text_blog; ?></a>
                        </li>
                    </ul>
                    <!-- Affiliates -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="<?php echo $affiliates; ?>"><?php echo $text_affiliates; ?></a>
                        </li>
                    </ul>
                    <!-- Manufacturers -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $text_manufacturers; ?></a>
                            <div class="dropdown-menu">
                                <ul class="list-unstyled">
                                    <?php foreach ($manufacturers as $manufacturer) { ?>
                                        <li><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <a href="<?php echo $all_manufacturers; ?>" class="see-all">See All Manufacturers</a>
                            </div>
                        </li>
                    </ul>
                    <!-- Models -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Models</a>
                            <div class="dropdown-menu">

                                    <?php foreach ($models as $model) { ?>
                                        <?php if ($model['model_data']) { ?>
                                            <ul class="list-unstyled">
                                            <p class="menu-manufacturer"><?php echo $model['manufacturer']; ?></p>
                                            <?php foreach ($model['model_data'] as $cat) { ?>
                                                <li><a class="manufacturer" href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a></li>
                                            <?php } ?>
                                             </ul>
                                        <?php } ?>
                                    <?php } ?>

                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- <br>
            shipping_methods_0 quote<br>
            <?php var_dump($_SESSION['shipping_methods_0']['quote']); ?><br>
            shipping_methods_0 info<br>
            <?php print_r($_SESSION['shipping_methods_0']['info']); ?><br>
            shipping_methods_0 error<br>
            <?php print_r($_SESSION['shipping_methods_0']['error']); ?><br> -->

		<div id="notification"></div>