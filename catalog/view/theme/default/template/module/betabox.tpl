<div class="box-betabox">
  <div class="box-betabox-heading"><?php echo $heading_title; ?></div>
   <?php foreach ($subcategories_popular as $key => $subcategories) { ?>
      <div class="beta-box-square" >
            <?php echo $subcategories['subcategory']['name']; ?> 
      <span class="category">(<?php echo $subcategories['category_name']; ?>)</span> </div>
   <?php } ?>
   
</div>
