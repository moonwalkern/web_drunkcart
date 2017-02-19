<div class="box">
  <div class="box-category-heading"></div>
  <div class="box-category-content" style="text-align: center;">
  <?php foreach ($categories_all as $key => $category) { ?>
    <div class="box-square" >
        <?php if (($key % 4) == 0) {?>
        <?php } ?> 
        <div class="image"><a href="<?php echo $category['href'];?>&product_id=<?php echo $category['category_id']; ?>" title="" class=""><img src="<?php echo $category['thumb']; ?>" title="" alt="" id="image" /></a></div>
    </div>
  <?php } ?>  
  </div>  

</div>
