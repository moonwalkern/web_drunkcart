<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>
      <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
      </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        
        <h2><?php echo $text_your_ad_details; ?></h2>
        <div class="content">
                <table class="form">
                <tr>
                  <td><?php echo $entry_type_of_ad; ?></td>
                  <td><?php if ($type_ad) { ?>
                    <input type="radio" name="type_ad" value="1"  />
                    <?php echo $text_sell; ?>
                    <input type="radio" name="type_ad" value="0" checked="checked"/>
                    <?php echo $text_buy; ?>
                    <?php } else { ?>
                    <input type="radio" name="type_ad" value="1" checked="checked"/>
                    <?php echo $text_sell; ?>
                    <input type="radio" name="type_ad" value="0"  />
                    <?php echo $text_buy; ?>
                    <?php } ?></td>
                </tr>
    
                    <tr>
                        <td><?php echo $entry_category; ?></td>
                        
                        <td><select name="category_id">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($product_categories_list as $category_list) { ?>
                          <?php if ($category_list['category_id'] == $category_id) { ?>
                            <option value="<?php echo $category_list['category_id']; ?>" selected="selected"><?php echo $category_list['name']; ?></option>
                          <?php } else { ?>
                            <option value="<?php echo $category_list['category_id']; ?>"><?php echo $category_list['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if ($error_category) { ?>
                        <span class="error"><?php echo $error_category; ?></span>
                        <?php } ?>
                        </td>
                    </tr>
                    <tbody id="subcategory_tbody" style="display: none;">
                    <tr>
                      <td><?php echo $entry_subcategory; ?>
                      </td>
                      <td><select name="subcategory_id">
                      
                        </select>
                        <?php if ($error_subcategory) { ?>
                        <span class="error"><?php echo $error_subcategory; ?></span>
                        <?php } ?>
                      </td>
                    </tr>
                    </tbody>
                    <tbody id="subsubcategory_tbody" style="display: none;">
                    <tr>
                      <td><span class="replaceme"><?php echo $entry_subcategory; ?></span>
                      </td>
                      <td><select name="subsubcategory_id">
                        </select>
                        <?php if ($error_subcategory) { ?>
                        <span class="error"><?php echo $error_subcategory; ?></span>
                        <?php } ?>
                      </td>
                    </tr>
                    </tbody>
                    <tr>
                      <td><?php echo $entry_manufacturer; ?>
                      </td>
                      <td><select name="manufacturer_id">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($manufacturers as $manufacturer) { ?>
                          <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                          <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected><?php echo $manufacturer['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if ($error_manufacturer) { ?>
                        <span class="error"><?php echo $error_manufacturer; ?></span>
                        <?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                      <td><input type="text" name="title" autocomplete="off" value="<?php echo $title; ?>" style="width: 400px;"/>
                        <?php if ($error_title) { ?>
                        <span class="error"><?php echo $error_title; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_condition; ?></td>
                        <td><?php if ($condition) { ?>
                            <input type="radio" name="condition" value="1" checked="checked" />
                            <?php echo $text_new; ?>
                            <input type="radio" name="condition" value="0" />
                            <?php echo $text_used; ?>
                            <?php } else { ?>
                            <input type="radio" name="condition" value="1" />
                            <?php echo $text_new; ?>
                            <input type="radio" name="condition" value="0" checked="checked" />
                            <?php echo $text_used; ?>
                            <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> <?php echo $entry_price; ?></td>
                      <td><span class="impact">Rs.&nbsp;&nbsp;</span><input type="text" autocomplete="off" name="price" value="<?php echo $price; ?>"/> <span class="impact">.00</span>
                        <?php if ($error_price) { ?>
                        <span class="error"><?php echo $error_price; ?></span>
                        <?php } ?></td>
                    </tr>    
                </table>
        </div>
        <h2><?php echo $text_describe_your_ad; ?></h2>
    
        <div class="content">
                <h3><?php echo $text_your_ad_pic; ?></h3>
                <table class="form">
                    <tr>
                        <div id="dropbox">
		                  <div class="text">
			                 Drop Images Here or Select image using below button
		                  </div>
                        </div>
                        <span class="upload-progress"></span>
                    </tr>
                    <tr><td align="center"><input type="file" id="imageFile" multiple="true" accept="image/*" /> </td></tr>
                </table>
                  
            <h3 id="attribute_head" style="display: none;"><?php echo $text_classify_your_ad; ?></h3>   
            <div id="attribute_div" class="content" style="display: none;">
               
        </div>
            
            <h3><?php echo $text_your_ad_pic; ?></h3>
                <table class="form">
                    <tr>
                      <td><span class="required">*</span> <?php echo $entry_state; ?></td>
                      <td><select name="states_id">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($states as $state) { ?>
                          <?php if ($state['zone_id'] == $states_id) { ?>
                            <option value="<?php echo $state['zone_id']; ?>" selected><?php echo $state['name']; ?></option>
                          <?php } else { ?>
                            <option value="<?php echo $state['zone_id']; ?>"><?php echo $state['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <?php if ($error_state) { ?>
                        <span class="error"><?php echo $error_state; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                         <td><span class="required">*</span> <?php echo $entry_locality; ?></td>
                         <td>
                            <select name="locality_id">
                                <option value=""><?php echo $text_select; ?></option>
                            </select>
                            <?php if ($error_title) { ?>
                        <span class="error"><?php echo $error_title; ?></span>
                        <?php } ?>
                         </td>
                         
                    </tr>
                    <tr>
                         <td><?php echo $entry_desc; ?></td>
                         <td>
                            <textarea name="description" cols="60" rows="10"><?php echo $description; ?></textarea>
                         </td>
                         
                    </tr>
                </table>
                
           <h3><?php echo $text_your_ad_pic; ?></h3>
                <table class="form">
                    <tr>
                      <td><?php echo $entry_type_of_seller; ?></td>
                      <td><?php if ($type_customer) { ?>
                        <input type="radio" name="whoareyou" value="0"  checked="checked"/>
                        <?php echo $text_individual; ?>
                        <input type="radio" name="whoareyou" value="1" />
                        <?php echo $text_agent; ?>
                        <?php } else { ?>
                        <input type="radio" name="whoareyou" value="0" />
                        <?php echo $text_individual; ?>
                        <input type="radio" name="whoareyou" value="1"  checked="checked"/>
                        <?php echo $text_agent; ?>
                        <?php } ?></td>
                    </tr>
                    <tr>
                         <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                         <td>
                            <input type="text" name="email" autocomplete="off" value="<?php echo $email; ?>" style="width: 200px;" />
                        <?php if ($error_email) { ?>
                        <span class="error"><?php echo $error_email; ?></span>
                        <?php } ?></td>
                         
                    </tr>
                    <tr>
                         <td><?php echo $entry_name; ?></td>
                         <td>
                            <input type="text" name="name" autocomplete="off" value="<?php echo $name; ?>" style="width: 200px;"/>
                         </td>
                         
                    </tr>
                    <tr>
                         <td><span class="required">*</span> <?php echo $entry_mobile; ?></td>
                         <td>
                            <input type="text" name="mobile" autocomplete="off" value="<?php echo $mobile; ?>" style="width: 150px;"/>
                         <?php if ($error_mobile) { ?>
                        <span class="error"><?php echo $error_mobile; ?></span>
                        <?php } ?></td>
                         
                    </tr>
                </table>     
            
        </div>
        <div class="buttons">
          <div class="left"><?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            <input type="submit" autocomplete="off" value="<?php echo $button_updatead; ?>" class="button" />
          </div>
          <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  
        </div>
        
        <!--
        <h2><?php echo $text_describe_your_ad; ?></h2>
        
        <div class="content" id="attribute_div">
               
        </div>
        -->
         <input type="hidden" name="images" id="images" size="100" value="<?php echo $imageTemp; ?>" />
         <input type="hidden" name="city" id="city" value="" />
         <input type="hidden" name="product_id" id="city" value="<?php echo $product_id; ?>" />
         <input type="hidden" name="ajax_url" id="ajax_url" value="<?php echo AJAX_PHOTO_URL; ?>" />         
  </form>

  <?php echo $content_bottom; ?>

</div>
<script type="text/javascript"><!--


$('#product-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product-category div:odd').attr('class', 'odd');
	$('#product-category div:even').attr('class', 'even');	
});

$('select[name=\'category_id\']').bind('change', function() {
    
    $('#attribute_div').hide();
    $('#attribute_head').hide();
    $('#subcategory_tbody').hide();
    $('#attribute_div').empty();
    if(this.value != 0){
        //alert('category' +this.value);
	$.ajax({
		url: 'index.php?route=product/category/categoryfilter&category_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'category_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
		      
		   if(json.length >0)
                $('#subcategory_tbody').show();
           
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json.length >0) {
			     $('#subcategory_tbody').show();
				for (i = 0; i < json.length; i++) {
        			html += '<option value="' + json[i]['subcategory_id'] + '"';
	    			
					if (json[i]['subcategory_id'] == '<?php echo $subcategory_id; ?>') {
	      				html += ' selected';
	    			}
	
	    			html += '>' + json[i]['name'] + '</option>';
				}
                
			} else {
				html += '<option value="0" selected><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'subcategory_id\']').html(html);
            $('select[name=\'subcategory_id\']').trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
    
    $.ajax({
		url: 'index.php?route=product/category/categoryattributevalues&category_id=' + this.value + '&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'category_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
            
     		
			if (json.length >0) {
			    
			    $('#attribute_div').show();
                $('#attribute_head').show();
                 
                html = '<table class="form">'
				for (i = 0; i < json.length; i++) {
				    
                    console.log(json[i]['attributes']['attribute_id']);
                    if(json[i]['attributes']['attribute_group_type'] == "select"){
                        
                        attrRealValue = json[i]['attributes']['category_attribute_value'].trim();
                        attrValue = json[i]['attributes']['values'];
                        splitAttrValue = attrValue.split(",");
                        
                        html += '<tr>';
                        html += '<td>' + json[i]['attributes']['name'].trim(); + '<br /></td>';
                        html += '<td>';
                        html += '<select name="' +json[i]['attributes']['attribute_id'] +'|' + json[i]['attributes']['name'].trim() +':attribute">';
                        html += '<option value=""><?php echo $text_select; ?></option>';
                        for(j=0;j<splitAttrValue.length;j++){
                            if(splitAttrValue[j] != ""){
                                html += '<option value="' + splitAttrValue[j].trim()+ '"';
                                if (splitAttrValue[j].indexOf(attrRealValue)>=0) {
            	      				html += ' selected="selected" ';
            	    			}
            	
            	    			html += '>' + splitAttrValue[j].trim()  + '</option>';
                            }
                        }
                        	html += '</td>';
                            html += '</tr>';
                    }
                    else if(json[i]['attributes']['attribute_group_type'] == "input"){
                        attrRealValue = json[i]['attributes']['category_attribute_value'].trim();
                        html += '<tr>';
                        html += '<td>' + json[i]['attributes']['name'] + '<br /></td>';
                        html += '<td>';
                        html += '<input type="text" name="' +json[i]['attributes']['attribute_id'] +'|' + json[i]['attributes']['name'].trim() +':attribute" value="' + attrRealValue + ' " placeholder="' +json[i]['attributes']['values'] +'" />';
                    }   
                    else if(json[i]['attributes']['attribute_group_type'] == "radio"){
                        attrRealValue = json[i]['attributes']['category_attribute_value'].trim();
                        html += '<tr>';
                        html += '<td>' + json[i]['attributes']['name'] + '<br /></td>';
                        html += '<td>';
                        attrValue = json[i]['attributes']['values'];
                        splitAttrValue = attrValue.split(",");
                        
                        for(j=0;j<splitAttrValue.length;j++){
                            if(splitAttrValue[j] != ""){
                                html += splitAttrValue[j];
                                
                                html += '<input type="radio" name="' +json[i]['attributes']['attribute_id'] +'|' + json[i]['attributes']['name'].trim() +':attribute" value="' + splitAttrValue[j] +'" ';
                                if (splitAttrValue[j].indexOf(attrRealValue)>=0) {
            	      				html += ' checked = "checked" ';
            	    			}
                                html += ' />';
                            }
                        }
                        html += '</td>';
                        html += '</tr>';
                    }
                    else if(json[i]['attributes']['attribute_group_type'] == "year"){
                        attrRealValue = json[i]['attributes']['category_attribute_value'].trim();
                        <!--Here we will specify the year range. 1900-current year -->
                        
                        attrValue = json[i]['attributes']['values'];
                        splitAttrValue = attrValue.split(",");
    
                        html += '<tr>';
                        html += '<td>' + json[i]['attributes']['name'] + '<br /></td>';
                        html += '<td>';
                        html += '<select name="' +json[i]['attributes']['attribute_id'] +'|' +json[i]['attributes']['name'].trim() +':attribute">';
                        year = splitAttrValue[0];
                        endyear = new Date().getFullYear();
                        for(j=year;j<=endyear;j++){
                            
                            html += '<option value="' + j + '"';  
                            if (j == attrRealValue) {
            	      				html += ' selected="selected" ';
 			                }  
                        	html += '>' + j  + '</option>';
                        
                        }
                        	html += '</td>';
                            html += '</tr>';
                    }
				}
                    
                    
                    html += '</table>';
                    console.log(html);
                    
                    $('#attribute_div').empty().prepend(html);//This statement clears the existing html and prepend the new.
			}
			
			
            
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
    }
});

$('select[name=\'category_id\']').trigger('change');


$('select[name=\'subcategory_id\']').bind('change', function() {
    
    if(this.value != 0){    
        subcategory = $(this).find("option:selected").text();
        //alert('subcategory' );
        
    	$.ajax({
    		url: 'index.php?route=product/category/categoryfilter&category_id=' + this.value,
    		dataType: 'json',
    		beforeSend: function() {
			$('select[name=\'subcategory_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
		  
            if(json.length >0)
                $('#subsubcategory_tbody').show();
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json.length > 0) {
				for (i = 0; i < json.length; i++) {
        			html += '<option value="' + json[i]['subcategory_id'] + '"';
	    			
					if (json[i]['subcategory_id'] == '<?php echo $subsubcategory_id; ?>') {
	      				html += ' selected';
	    			}
	
	    			html += '>' + json[i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'subsubcategory_id\']').html(html);
            $('.replaceme').html(subcategory + ' (Categories)');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
    $.ajax({
		url: 'index.php?route=product/category/categoryattribute&category_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'category_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
            
     		
			if (json.length >0) {
			    $('#attribute_div').show();
                $('#attribute_head').show();
                 
                html = '<table class="form">'
				for (i = 0; i < json.length; i++) {
				    
                    console.log(json[i]['attribute_id']);
                    
                    if(json[i]['attributes']['attribute_group_type'] == "select"){
                        
                    
                    
                        attrValue = json[i]['attributes']['values'];
                        splitAttrValue = attrValue.split(",");
    
                        html += '<tr>';
                        html += '<td>' + json[i]['attribute_name'] + '<br /></td>';
                        html += '<td>';
                        html += '<select name="' +json[i]['attribute_id'] +':attribute">';
                        html += '<option value=""><?php echo $text_select; ?></option>';
                        for(j=0;j<splitAttrValue.length;j++){
                            if(splitAttrValue[j] != ""){
                                html += '<option value="' + splitAttrValue[j]+ '"';    
                                if (json[i]['subcategory_id'] == '<?php echo $attribute_id; ?>') {
            	      				html += ' selected="selected"';
            	    			}
            	
            	    			html += '>' + splitAttrValue[j]  + '</option>';
                            }
                        }
                        	html += '</td>';
                            html += '</tr>';
                    }
                    else if(json[i]['attributes']['attribute_group_type'] == "input"){
                        html += '<tr>';
                        html += '<td>' + json[i]['attribute_name'] + '<br /></td>';
                        html += '<td>';
                        html += '<input type="text" name="' +json[i]['attribute_id'] +':attribute" value="" placeholder="' +json[i]['attributes']['values'] +'" />';
                    }   
                    else if(json[i]['attributes']['attribute_group_type'] == "radio"){
                        html += '<tr>';
                        html += '<td>' + json[i]['attribute_name'] + '<br /></td>';
                        html += '<td>';
                        attrValue = json[i]['attributes']['values'];
                        splitAttrValue = attrValue.split(",");
                        
                        for(j=0;j<splitAttrValue.length;j++){
                            if(splitAttrValue[j] != ""){
                                html += splitAttrValue[j];
                                html += '<input type="radio" name="' +json[i]['attribute_id'] +':attribute" value="' + splitAttrValue[j] +'" />';
                            }
                        }
                        html += '</td>';
                        html += '</tr>';
                    }
                    else if(json[i]['attributes']['attribute_group_type'] == "year"){
                        <!--Here we will specify the year range. 1900-current year -->
                        
                        attrValue = json[i]['attributes']['values'];
                        console.log(attrValue);
                        splitAttrValue = attrValue.split(",");
    
                        html += '<tr>';
                        html += '<td>' + json[i]['attribute_name'] + '<br /></td>';
                        html += '<td>';
                        html += '<select name="' +json[i]['attribute_id'] +':attribute">';
                        year = splitAttrValue[0];
                        endyear = new Date().getFullYear();
                        console.log(year);
                        console.log(endyear);
                        for(j=year;j<=endyear;j++){
                            
                            html += '<option value="' + j + '"';    
                        	html += '>' + j  + '</option>';
                        
                        }
                        	html += '</td>';
                            html += '</tr>';
                    }
				}
                    
                    
                    html += '</table>';
                    console.log(html);
                    
                    $('#attribute_div').empty().prepend(html);//This statement clears the existing html and prepend the new.
			}
			
			
            
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
    }    
});

$('select[name=\'states_id\']').bind('change', function() {
    state_id = this.value;
	$.ajax({
		url: 'index.php?route=product/postad/localityall',
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'states_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
            if(json.length >0)
                
			//console.log(json);
			html = '<option value=""><?php echo $text_select; ?></option>';
            //console.log(json[0]['name']);
			if (json.length > 0) {
				for (i = 0; i < json.length; i++) {
				    
                    if(json[i]['state_id'] == state_id){
                        
            			html += '<option value="' + json[i]['locality_id'] +',' + json[i]['locality_name'] + '"';
    	    			
    				    if (json[i]['locality_id'] == '<?php echo $locality_id; ?>') {
	      				 html += ' selected';
	    			    }
    	
    	    			html += '>' + json[i]['locality_name'] + '</option>';
                    }
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'locality_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'subsubcategory_id\']').trigger('change');

$('select[name=\'states_id\']').trigger('change');

$('select[name=\'locality_id\']').bind('change', function() {
    console.log(this.value);
});
</script>

<script type="text/javascript">
<!--
    function removeImage(id){
        var imageS = document.getElementById('images').value;
        var imageArray =  imageS.split(",");
        document.getElementById('images').value = "";
        for(ix=0;ix<imageArray.length;ix++){
            //alert(imageArray[ix] +'--' + id.id);
            //alert(imageArray[ix].indexOf(id.id));
            if(imageArray[ix].indexOf(id.id)<0){
                if(document.getElementById('images').value == ""){
                    document.getElementById('images').value = imageArray[ix] ;
                }
                else{
                    document.getElementById('images').value = document.getElementById('images').value + "," + imageArray[ix];
                }
            }
        }
        
       
        $("#div"+id.name).remove();
        
    }	
-->
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
    
 

    var rand = 0;
    var imageTemp = '<?php echo $imageTemp; ?>'
    var image = '<?php echo $image; ?>'
    var imageArray = image.split(',');
    var imageTempArray = imageTemp.split(",");
    var template ='';
    if(image){
        //alert(image);
        //alert(imageArray.length);
        for(ix=0;ix<imageArray.length-1;ix++){
            
            rand = Math.floor((Math.random()*100000)+3);
            template = '<div class="eachImage" id="div'+rand+'">';
        	template += '<span class="preview" id="span'+rand+'"><img width="200" height="85" src="'+imageArray[ix]+'"><span class="overlay"><span class="updone"></span></span>';
        	template += '</span>';
        	
            template += '<input class="button" type="button" id="'+imageTempArray[ix]+'" name="'+rand+'" value="remove" onClick="removeImage(this)">';
    
            if($("#dropbox .eachImage").html() == null)
        		$("#dropbox").html(template);
        	else
        		$("#dropbox").append(template);
            
    	    $(".preview[id=span'"+rand+"'] .overlay").css("display","none");
        }
    }            
           
       
});
//--></script> 
 
<?php echo $footer; ?>