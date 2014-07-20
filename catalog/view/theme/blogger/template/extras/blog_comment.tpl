<?php if ($comments) { ?>
<h2 class="comment-responses"><?php echo $responses; ?></h2>
<?php foreach ($comments as $comment) { ?>
<div class="comment-list">
  <div class="author"><?php if($comment['website']) { ?><a href="<?php echo $comment['website']; ?>" target="_blank"><?php echo $comment['author']; ?></a><?php } else { ?><b><?php echo $comment['author']; ?></b><?php } ?> <?php echo $text_on; ?> <?php echo $comment['date_added']; ?></div>
  <div class="rating"><img src="catalog/view/theme/blogger/image/stars-<?php echo $comment['rating'] . '.png'; ?>" alt="<?php echo $comment['comments']; ?>" /></div>
  <div class="text"><?php echo $comment['text']; ?></div>
</div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_comments; ?></div>
<?php } ?>
