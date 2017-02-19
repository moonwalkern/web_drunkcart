<div class="left">
  <h2><?php echo $text_your_details; ?></h2>
  <span class="required">*</span> <?php echo $entry_firstname; ?><br />
  <input class="textbox" type="text" name="firstname" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo $entry_lastname; ?><br />
  <input class="textbox" type="text" name="lastname" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo $entry_email; ?><br />
  <input class="textbox" type="text" name="email" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo $entry_telephone; ?><br />
  <input class="textbox" type="text" name="telephone" value="" class="large-field" />
  <br />
  <br />
  <?php echo $entry_fax; ?><br />
  <input class="textbox" type="text" name="fax" value="" class="large-field" />
  <br />
  <br />
  <h2><?php echo $text_your_password; ?></h2>
  <span class="required">*</span> <?php echo $entry_password; ?><br />
  <input class="textbox" type="password" name="password" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo $entry_confirm; ?> <br />
  <input class="textbox" type="password" name="confirm" value="" class="large-field" />
  <br />
  <br />
  <br />
</div>
<div class="right">
  <h2><?php echo $text_your_address; ?></h2>
  <?php echo $entry_company; ?><br />
  <input class="textbox" type="text" name="company" value="" class="large-field" />
  <br />
  <br />
  <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
  <?php echo $entry_customer_group; ?><br />
  <?php foreach ($customer_groups as $customer_group) { ?>
  <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } else { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } ?>
  <?php } ?>
  <br />
</div>
<div id="company-id-display"><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?><br />
  <input class="textbox" type="text" name="company_id" value="" class="large-field" />
  <br />
  <br />
</div>
<div id="tax-id-display"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?><br />
  <input class="textbox" type="text" name="tax_id" value="" class="large-field" />
  <br />
  <br />
</div>
<span class="required">*</span> <?php echo $entry_address_1; ?><br />
<input class="textbox" type="text" name="address_1" value="" class="large-field" />
<br />
<br />
<?php echo $entry_address_2; ?><br />
<input class="textbox" type="text" name="address_2" value="" class="large-field" />
<br />
<br />
<span class="required">*</span> <?php echo $entry_city; ?><br />
<input class="textbox" type="text" name="city" value="" class="large-field" />
<br />
<br />
<span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?><br />
<input class="textbox" type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />

<br />
<br />

<span class="required">*</span> <?php echo $entry_state; ?><br />     
<select class="select-style" name="states_id">
<option value=""><?php echo $text_select; ?></option>
<?php foreach ($states as $state) { ?>
<?php if ($state['zone_id'] == $states_id) { ?>
<option value="<?php echo $state['zone_id']; ?>" selected="selected"><?php echo $state['name']; ?></option>
<?php } else { ?>
<option value="<?php echo $state['zone_id']; ?>"><?php echo $state['name']; ?></option>
<?php } ?>
<?php } ?>
</select>  
<br />
<br />
<span class="required">*</span> <?php echo $entry_locality; ?><br />
<select class="select-style" name="locality_id">
<option value=""><?php echo $text_select; ?></option>
</select>
<br />
<br />
<br />
</div>
<div style="clear: both; padding-top: 15px; border-top: 1px solid #EEEEEE;">
  <input type="checkbox" name="newsletter" value="1" id="newsletter" />
  <label for="newsletter"><?php echo $entry_newsletter; ?></label>
  <br />
  <?php if ($shipping_required) { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
  <label for="shipping"><?php echo $entry_shipping; ?></label>
  <br />
  <?php } ?>
  <br />
  <br />
</div>
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="right"><?php echo $text_agree; ?>
    <input type="checkbox" name="agree" value="1" />
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$('#payment-address input[name=\'customer_group_id\']:checked').live('change', function() {
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('#company-id-display').show();
		} else {
			$('#company-id-display').hide();
		}
		
		if (customer_group[this.value]['company_id_required'] == '1') {
			$('#company-id-required').show();
		} else {
			$('#company-id-required').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('#tax-id-display').show();
		} else {
			$('#tax-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_required'] == '1') {
			$('#tax-id-required').show();
		} else {
			$('#tax-id-required').hide();
		}	
	}
});

$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#payment-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {

				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#payment-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('select[name=\'states_id\']').bind('change', function() {
    console.log(this.value);
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
                
			
			html = '<option value=""><?php echo $text_select; ?></option>';
            
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

$('select[name=\'states_id\']').trigger('change');
$('#payment-address select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	width: 640,
	height: 480
});
//--></script> 