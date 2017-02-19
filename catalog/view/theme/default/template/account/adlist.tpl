<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($products) { ?>
  <div id="dialog" style="padding: 3px 0px 0px 0px;">
    
  </div>
  <div id="ad-info" class="wishlist-info">
    <table>
      <thead>
        <tr>
          <td class="image"><?php echo $column_image; ?></td>
          <td class="name"><?php echo $column_name; ?></td>
          <td class="model"><?php echo $column_brand; ?></td>
          <td class="stock"><?php echo $column_date; ?></td>
          <td class="price"><?php echo $column_price; ?></td>
          <td class="action"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <?php $count=0; ?>
      <?php foreach ($products as $product) { ?>
      <?php $count = $count+1; ?>
      <tbody id="wishlist-row<?php echo $product['product_id']; ?>">
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
          <td class="model"><?php echo $product['manufacturer']; ?></td>
          <td class="stock"><?php echo $product['date']; ?></td>
          <td class="price"><?php if ($product['price']) { ?>
            <div class="price">
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
              <?php } ?>
            </div>
            <?php } ?></td>
          <td class="action"><a href="<?php echo $product['edit']; ?>"><img src="catalog/view/theme/default/image/edit.png" alt="<?php echo $button_edit; ?>" title="<?php echo $button_edit; ?>" /></a>&nbsp;&nbsp;<img onclick="confirm('<?php echo $product['product_id']; ?>');" src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></td>
        </tr>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  
  <?php } ?>
  <input name="pcount" id="pcount" type="hidden" value="<?php echo $count; ?>" />
  <?php echo $content_bottom; ?></div>
  <script type="text/javascript">
  function confirm(field) {
		
     
     $('#dialog').html('<p> <p>You wish to remove the AD?</p></p>');
     $( "#dialog" ).dialog({
        resizable: false,
        height:160,
        modal: true,
        buttons: {
        "Ok": function() {
            $.ajax({
					url: 'index.php?route=product/postad/remove&id=' + field,
					dataType: 'text',
					success: function(text) {
						$( "#wishlist-row" +field ).remove();
                        document.getElementById('pcount').value = document.getElementById('pcount').value -1;
                        if(document.getElementById('pcount').value == 0){
                            $('#ad-info').html("<div class='content'><?php echo $text_empty; ?></div>");
                        } 
					}
		     });
            
            
            
            $( this ).dialog( "close" );
        },
        Cancel: function() {
            $( this ).dialog( "close" );
        }
        }
        });
        $(".ui-dialog-titlebar").hide();
   };
  
  </script>
<?php echo $footer; ?>