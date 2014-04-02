<?php
global $data;

if(function_exists('icl_object_id')) {
  $slider_page_id = icl_object_id($slider_page_id, 'slide', true);
}

if($data[get_post_meta($slider_page_id, 'pyre_flexslider', true)]):
?>
<div class="tfs-slider flexslider main-flex" style="max-width:<?php echo $data['flexslider_width']; ?>;">
  <ul class="slides" style="width:<?php echo $data['flexslider_width']; ?>;">
    <?php foreach($data[get_post_meta($slider_page_id, 'pyre_flexslider', true)] as $slide): ?>
    <?php if($slide['title'] || ($slide['url'] || $slide['description'])): ?>
    <li style="position:relative;">
      <?php if($slide['link']): ?>
      <a href="<?php echo $slide['link']; ?>">
      <?php endif; ?>
      <?php if($slide['url']): ?>
      <img src="<?php echo $slide['url']; ?>" alt="" />
      <?php elseif($slide['description']): ?>
      <div class="full-video">
      <?php echo $slide['description']; ?>
      </div>
      <?php endif; ?>
      <?php if($slide['title']): ?>
      <p class="flex-caption"><?php echo $slide['title']; ?></p>
      <?php endif; ?>
      <?php if($slide['link']): ?>
  	  </a>
  	  <?php endif; ?>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>