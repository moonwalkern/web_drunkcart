<div style="display:none;">
	<div id="div-welcome" >
			<div id="content">
      
  				<h1><?php echo $heading_title; ?></h1>
  		
		        <div id="search_location">
		    		<!-- <div class="button-search"></div> -->
		    		<input type="text" name="search_location" placeholder="<?php echo $text_search; ?>" value="" />
		  		</div>
        		</br>
        
		        <div id="location">
		             
		          <div class="content" id="popular_div_new">
		            
		            <div class="content"><tr><td><b>Popular Cities</b></td></tr>
		                <table class="location-info-popular"><tr>
		                    <?php foreach ($popular as $key => $value) { ?>
		                            <?php if (($key % 5) == 0) {?>
		                                </tr><tr>
		                            <?php } ?> 
		                        <td><a onclick="setLocationAllPop('<?php echo $value['locality_id']; ?>', '<?php echo $value['locality_name']; ?>', '<?php echo $value['state_id']; ?>', '<?php echo $value['state']; ?>');"><?php echo $value['locality_name']; ?></a></td>
		                    <?php } ?>                     
		                    
		                </table>
		            </div>
		            
		              
		          </div>
		        </div>
		</div>
	</div>
</div>

<script type="text/javascript">
			
			$(document).ready(function () {
				$("#dialog:ui-dialog").dialog("destroy");
				$("#div-welcome").dialog({
					  width: 'auto',
					  height: 'auto',
					  modal: true,
				});
				$(".ui-dialog-titlebar").hide();
			});

</script>
<script type="text/javascript"><!--
tempHtml = $('#popular_div_new').html();  
$('input[name=\'search_location\']').autocomplete({
	delay: 500,
	source: function(request, response) {		
		$.ajax({
			url: 'index.php?route=module/locationlist/autocomplete&filter_name='+  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					'category_id':  0,
					'name':  '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) { 
					return {
						label: item.locality_name,
						value: item.locality_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'search_location\']').val(ui.item.label);
		
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'search_location\']').live('keydown', function(e) {
	    // console.log(e.keyCode);
	    lsearch = $('input[name=\'search_location\']').attr('value');
	    if (e.keyCode == 8 && lsearch == '') {
	    	e.keyCode = 13;
	    }
		if (e.keyCode == 13) {
		    
            
            // if(tempHtml != null){
                // console.log("*************");
//                 
                // console.log(tempHtml);
//                   
            // }
             
			$.ajax({
    		url: 'index.php?route=module/locationlist/searchlocation',
    		type: 'post',
    		data: 'search='+lsearch,
    		dataType: 'json',
    		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
                
                if(lsearch.trim() == ''){
                    $('#popular_div_new').empty().append(tempHtml);
                    console.log("lsearch is empty " + tempHtml);
                    return;
                }
                    html = '<div class="content" >';
                    html += '<table class="mini-location-info"><tr>';
                    for (i in json) {
                        if(json[i]['locality_name'].toLowerCase().indexOf(lsearch.toLowerCase()) >= 0){
                            if ((i % 4) == 0){
                                html += '</tr><tr>';
                            }
                            html += '<td><a onclick="setLocationAllPop(\''+ json[i]['locality_id']+'\',\''+ json[i]['locality_name'].trim() +'\',\''+ json[i]['state_id'].trim() +'\',\''+ json[i]['state'].trim() +'\');">'+json[i]['locality_name']+'</a></td>';    
                        }
                        
                    }
                    html += '</table></div>';     
                        //console.log(json[i]['locality_name'].toLowerCase().indexOf(lsearch.toLowerCase()));
                        
                        //console.log(tempHtml);
                        $('#popular_div_new').empty().append(html);
                    }    
                
    		  
           
	       });
		}
	});

//--></script>