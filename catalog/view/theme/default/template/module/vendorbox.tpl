<div class="box-vendorbox">
  <div class="box-vendorbox-heading"><?php echo $heading_title; ?></div>
   <?php foreach ($product_vendors as $key => $product_vendor) { ?>
      <div class="vendor-box-square" >
      		<?php echo $product_vendor['vendor_name']; ?>
      		</br>
      		</br>
      		<span class="box-vendorbox-topic"><img src="catalog/view/theme/default/image/phone.png"> <?php echo $product_vendor['mobile']; ?></span>
      	<!--</br>
      	</br>
      		<span class="box-vendorbox-topic"><img src="catalog/view/theme/default/image/edit.png"><?php echo $product_vendor['address_1']; echo ", "; echo $product_vendor['address_2'];  ?></span> 
      -->
      </div>
      
   <?php } ?>
   
</div>
