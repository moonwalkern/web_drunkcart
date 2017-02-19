<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content-subcategory"><?php echo $content_top; ?>

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php 
    	$x=0; 
    	$y=0;
    ?>
    <div class="box-subcategorybox">
        <?php $findcategory = array(); ?>
        <?php foreach ($categories_all as $key => $category) { ?>
            <?php if ($category['category_id'] == $product_id) {?>
                <?php $findcategory =  $category; ?>
                <?php break; ?>
            <?php } ?>    
        <?php } ?>
       <div class="box-subcategory-heading" id="subcategory_heading"><?php echo $findcategory['name']; ?><a id="show_category_list"><span></span></a></div>
       <div class="box-category-content"  id="category_list" style="display:hidden;">
        <table class="categorylist">
            <tr>
              <?php foreach ($categories_all as $key => $category) { ?>
                <td>
                    <a id="<?php echo $category['category_id']; ?>" href="#" title="" class=""><img src="<?php echo $category['thumb']; ?>" title="" alt="" id="image" /></a>
                </td>
                <?php if ((($key+1) % 4) == 0) {?>
                        </tr><tr>
                    <?php } ?> 
              <?php } ?>  
            </tr>
        </table>
       </div>
       <div class="box-subcategory-content"  id="subcategory_list">
        <span>
            <ul id="nav">
                <li><a href="#"></a>
                    <div class="subs">
                        <div class="wrp2">
                                <ul>
                                    <?php foreach ($sub_category as $key => $subcategory) { ?>
                                        <?php $x=$x+1; ?>
                                            <li><h3><a href="<?php echo $subcategory['href'];?>"><?php echo $subcategory['subcategory_name']; ?></a></h3>
                                                <?php if ($subcategory['subcategory'] != 0) {?>
                                                    <ul>
                                                        <?php foreach ($subcategory['subcategory'] as $subkey => $subsubcategory) { ?>
                                                            <?php $y=$y+1; ?>
                                                            <li><a href="<?php echo $subsubcategory['href'];?>"><?php echo $subsubcategory['name']; ?></a></li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                            <?php if (($x+$y) >= 13) {
                                            	$x = 0;
                                            	$y = 0;	
                                            ?>
                                                </ul><p class="sep"></p><ul>
                                            <?php } ?> 
                             
                                    <?php } ?>
                                </ul>
                        </div>
                    </div>
                </li>
            </ul>

        </span>
    </div>
</div>       
 

<?php echo $content_bottom; ?></div>

<script type="text/javascript">
<!--
	$(document).ready(function(){
		console.log($('#subcategory_heading').html());
    $('.box-category-content a').click(function(e) {
        e.preventDefault();
        
        $.ajax({
		url: 'index.php?route=product/subcategory/getsubcategory&category_id=' + this.id,
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
			
		},			
		success: function(json) {
		  image_html = '<img width="300" height="40" src="'+ json[0][0]['imagesmall']+'" title="" alt="" id="image" /><a><span id="show_category_list"></span></div></a>';
          //console.log(image_html);
          //$('#subcategory_image').empty().prepend(image_html);
		  $( "#subcategory_image" ).attr( "src", function() {
              return json[0][0]['imagesmall'];
           });
           image_html = json[0][0]['category_name']+' <a id="show_category_list" onclick="showCategories();"><span></span></a>';
           //console.log(image_html);
          $( "#category_list" ).slideUp();
          
          html = '<span>';
            html += '<ul id="nav">';
                html += '<li><a href="#"></a>';
                    html += '<div class="subs">';
                        html += '<div class="wrp2">';
                                html += '<ul>';
                                    var x=0;
                                    var y=0;
                                    $(jQuery.parseJSON(JSON.stringify(json))).each(function(index, subcategory) {  
                                            x++;       
                                            
                                            html += '<li><h3><a href="' + subcategory[0]['href'] +'">'+ subcategory[0]['subcategory_name'] +'</a></h3>';
                                                if(subcategory[0]['subcategory'].length >=0){ 
                                                    html += '<ul>';
                                                        var obj = {};
                                                        for (var prop in subcategory[0]['subcategory']) {
                                                            y++;
                                                            html += '<li><a href="'+ subcategory[0]['subcategory'][prop]['href'] +'">'+ subcategory[0]['subcategory'][prop]['name'] +'</a></li>';
                                                            
                                                        }
                                                    html += '</ul>';
                                                }
                                                
                                            html += '</li>';
                                            
                                            if(((x+y) >= 13)){
                                            	x = 0;
                                            	y = 0;
                                                html += '</ul><p class="sep"></p><ul>';
                                            } 
                                   });
                                html += '</ul>';
                        html += '</div>';
                    html += '</div>';
                html += '</li>';
            html += '</ul>'
        html += '</span>';
          
        //console.log(html);
        
        $('#subcategory_heading').empty().prepend(image_html);//This statement clears the existing html and prepend the new.
        $('#subcategory_list').empty().prepend(html);//This statement clears the existing html and prepend the new.
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
         
    });
 });
-->
</script>

<script type="text/javascript"><!--
function showCategories(){
	if ( $( "#category_list" ).is( ":hidden" ) ) {
    $( "#category_list" ).show( "slow" );
  } else {
    $( "#category_list" ).slideUp();
  }
}

$( "#show_category_list" ).click(function() {
  if ( $( "#category_list" ).is( ":hidden" ) ) {
    $( "#category_list" ).show( "slow" );
  } else {
    $( "#category_list" ).slideUp();
  }
});
//--></script> 
<?php echo $footer; ?>