<div class="box">
 <div class="box-heading"><?php echo $heading_title_brands; ?><span class="msg_head" style="padding-left: 60px;cursor: pointer;">  - </span></div>
    <div class="box-content-selection">
        <div class="box-selection"><?php echo $heading_title_brands; ?><p></p>
                <p>
                    <input class="textbox"type="text" name="brand">
                </p>
                
            <div class="checkbox" id="checkbox" >
                <?php foreach ($categories as $key => $category) { ?>
                    <input id="check<?php echo $key; ?>" type="checkbox" name="check<?php echo $key; ?>" value="'<?php echo $category['name']; ?>'">
    			    <label for="check<?php echo $key; ?>"><?php echo $category['name']; ?></label>
                    </br>
                    
                <?php } ?>
                         
            
            </div>
            <input name="brand_category" value="<?php echo $brands; ?>" type="hidden" />    
        
        </div>
        <?php foreach ($categories_attributes as $categories_attribute) { ?>
        <?php $attrname = str_replace(" ", "", trim($categories_attribute['attribute_name'])); ?>
        <div class="box-selection"><?php echo $categories_attribute['attribute_name']; ?><p></p>
            <?php if($categories_attribute['attribute_group_type'] != 'year') {?>
                <p>
                    <input class="textbox"type="text" id="<?php echo $attrname; ?>" name="<?php echo $attrname; ?>" onkeydown="genKeyPress(this)">
                </p>
             <?php } ?>
            <?php 
                $arrAttributes = explode(",", $categories_attribute['values']);
            ?>
            
            <div class="checkbox" id="div<?php echo $attrname; ?>">
                <?php foreach ($arrAttributes as $key => $attributes) { ?>
                    <?php if($categories_attribute['attribute_group_type'] == 'year') {?>
                        
                          <?php for($iY=date("Y");$iY>=$categories_attribute['values'];$iY--) {?>
                            
                            <input id='check<?php echo $key; ?>' type="checkbox" name="check<?php echo $key; ?>" value="'<?php echo $iY; ?>'">
    			            <label for='check<?php echo $key; ?>'><?php echo $iY; ?></label>
                            </br>
                            <?php } ?>
                    <?php } else {?>
                        <input id='<?php echo $attributes; ?>' type="checkbox" name="<?php echo $attributes; ?>" value="'<?php echo $attributes; ?>'">
        			    <label id='<?php echo $attributes; ?>' for='<?php echo $attributes; ?>'><?php echo $attributes; ?></label>
                        </br>
                    <?php }?>
                <?php } ?>
                         
            </div>
            <input name="h<?php echo $attrname; ?>" id="h<?php echo $attrname; ?>" value="<?php echo $categories_attribute['values']; ?>" type="hidden" />    
        
        </div>
        
        <div class="line-separator"></div>
        <?php } ?>
        
        <div class="box-selection">Price <p></p>
            <p>
              <input type="text" id="amount" readonly style="border:0; color:#3399FF; font-weight:bold;">
            </p>
        <div id="slider-range"></div> 
        </div>
        
      <div class="scroll-pane">
			</div>

      </div>
</div>


<script>
//This function will dynamically add check box, depending on val it will check of uncheck the checkbox
function fetchBrand(val){
    brandArr = $('input[name=\'brand_category\']').val().split(",");
    html = "";
    
    for(i=0;i<brandArr.length;i++){
        if(brandArr[i].toUpperCase().indexOf($('input[name=\'brand\']').val().toUpperCase()) >= 0){
            if(val === 'checked'){
                html += '<input checked="checked" id="check'+ i +'" type="checkbox" name="check'+ i + '" value="'+ brandArr[i] +'">';    
            }else{
                html += '<input id="check'+ i +'" type="checkbox" name="check'+ i + '" value="'+ brandArr[i] +'">';
            }
            
            html += '<label for="check'+ i +'">'+ brandArr[i] +'</label>';
            html += '<br>';
        }
    }
    console.log(html);
    //$('#checkbox').empty().prepend(html);
    divAdd('#checkbox', html);
}

//This function will dynamically add check box, depending on val it will check of uncheck the checkbox
function fetchFilter(val, filterattr, e){
    filterArr = filterattr.split(",");
    html = "";
    
    for(i=0;i<filterArr.length;i++){
        if(filterArr[i].toUpperCase().indexOf(document.getElementById(e.id).value.toUpperCase()) >= 0){
            if(val === 'checked'){
                html += '<input checked="checked"  ="'+ filterArr[i] +'" type="checkbox" name="'+ filterArr[i] + '" value="'+ filterArr[i] +'">';    
            }else{
                html += '<input id="check'+ i +'" type="checkbox" name="check'+ i + '" value="'+ filterArr[i] +'">';
            }
            
            html += '<label for="check'+ i +'">'+ filterArr[i] +'</label>';
            html += '<br>';
        }
    }
    console.log(html);
    var divId = '#div'+e.id.trim();

    divAdd(divId, html);
   
}

function divAdd(divId, html){
    var settings = {
		showArrows: true
	};
	var pane = $(divId)
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
    api.getContentPane().empty().prepend(
				html
    );
    api.reinitialise();
}




$('input[name=\'brand\']').keydown(function(e) {
    key = e.keyCode || e.charCode;  
    if (key === 8 || key === 46) {
        fetchBrand();
    }

});

function genKeyPress(e){
    var divVal = '#div'+e.id.trim();
    
    
    var filterid = "h"+e.id;
    console.log(filterid);
    console.log(document.getElementById(filterid).value);
    fetchFilter("",document.getElementById(filterid).value,e)

    
    
    
}

$(function()
{
	$('.checkbox').jScrollPane(
    {
			autoReinitialise: true
	}
    );
});
 brandArr = $('input[name=\'brand_category\']').val().split(",");
$('input[name=\'brand\']').autocomplete({
	delay: 500,
	source: brandArr,
	select: function(event, ui) {
	   fetchBrand("checked");
		
	   return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

  $(function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 100,
      max: 250000,
      values: [ 0, 20000 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "<?php echo $currency; ?>"  + ui.values[ 0 ] + " - " + "<?php echo $currency; ?>" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "<?php echo $currency; ?>" + $( "#slider-range" ).slider( "values", 0 ) +
      " - " + "<?php echo $currency; ?>" + $( "#slider-range" ).slider( "values", 1 ) );
  });
  </script>
