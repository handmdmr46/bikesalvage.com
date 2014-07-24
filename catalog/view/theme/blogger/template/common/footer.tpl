<div id="footer">
  <div class="column">
  <div class="inside">
    <h3><?php echo $text_latest_blog; ?></h3>
    <div class="blogLatestFooter">
      <ul>
        <?php foreach ($blogs as $blog) { ?>
        <li>
          <span class="date-added"><?php echo $blog['date_added']; ?></span><br />
          <a href="<?php echo $blog['href']; ?>" class="active"><?php echo $blog['title']; ?></a><br />
          <?php echo $blog['description']; ?>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="view-all"><a href="<?php echo $blog_all; ?>"><?php echo $text_view_all_topics; ?></a></div>
  </div>
  </div>
  <div class="column">
  <div class="inside">
    <h3><?php echo $text_contact; ?></h3>
    <div class="items">
      <div class="title"><?php echo $text_address; ?></div>
      <div class="description"><?php echo $address; ?></div>
    </div>
    <div class="items">
      <div class="title"><?php echo $text_telephone; ?></div>
      <div class="description"><?php echo $telephone; ?></div>
    </div>
    <div class="items">
      <div class="title"><?php echo $text_fax; ?></div>
      <div class="description"><?php echo $fax; ?></div>
    </div>
    <div class="items">
      <div class="title"><?php echo $text_email; ?></div>
      <div class="description"><?php echo $email; ?></div>
    </div>
  </div>
  </div>
  <div class="column">
  <div class="inside">
    <h3><?php echo $text_twitter; ?></h3>
	<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
    <script>
    new TWTR.Widget({
      version: 2,
      type: 'profile',
      rpp: 5,
      interval: 30000,
      width: 230,
      height: 300,
      theme: {
        shell: {
          background: '#333333',
          color: '#ffffff'
        },
        tweets: {
          background: '#000000',
          color: '#b4b3b3',
          links: '#c41819'
        }
      },
      features: {
        scrollbar: false,
        loop: false,
        live: false,
		hashtags: true,
		timestamp: true,
		avatars: false,
        behavior: 'all'
      }
    }).render().setUser('<?php echo $twitter_id; ?>').start();
    </script>
  </div>
  </div>
  <div class="column">
    <h3><?php echo $text_facebook; ?></h3>
    <!--<div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
      <fb:fan profileid="<?php echo $facebook_page_id; ?>" stream="0" connections="16" logobar="0" width="240" height="400" css=""></fb:fan>
    </div>-->
<div id="fb-root"></div>
<div id="fb-root"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=130746823631846";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like-box" data-href="<?php echo $facebook_page_id; ?>" data-width="240" data-height="400" data-colorscheme="dark" data-show-faces="true" data-border-color="#666666" data-stream="false" data-header="true"></div>
  </div>
  <div class="row">
    <span class="title"><?php echo $text_account; ?></span>
    <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
    <a href="<?php echo $order; ?>"><?php echo $text_order; ?></a>
    <a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a>
    <a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a>
  </div>
  <div class="row">
    <span class="title"><?php echo $text_service; ?></span>
      <a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
      <a href="<?php echo $return; ?>"><?php echo $text_return; ?></a>
      <a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a>
  </div>
  <div class="row">
    <span class="title"><?php echo $text_extra; ?></span>
      <a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a>
      <a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a>
      <a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a>
      <a href="<?php echo $special; ?>"><?php echo $text_special; ?></a>
  </div>
  <div class="row">
    <span class="title"><?php echo $text_information; ?></span><?php foreach ($informations as $information) { ?><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a><?php } ?>
  </div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="powered"><?php echo $powered; ?></div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</div>
<div id="developer"><?php echo $developed; ?></div>
</div>
</body></html>