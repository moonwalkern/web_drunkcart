<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_username; ?></td>
            <td><input type="text" name="username" value="<?php echo $username; ?>" />
              <?php if ($error_username) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
              <?php if ($error_firstname) { ?>
              <span class="error"><?php echo $error_firstname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
            <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
              <?php if ($error_lastname) { ?>
              <span class="error"><?php echo $error_lastname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_mobile; ?></td>
            <td><input type="text" name="mobile" value="<?php echo $mobile; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_phone; ?></td>
            <td><input type="text" name="phone" value="<?php echo $phone; ?>" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span><?php echo $entry_state; ?></td>
            <td>
            	<select name="states_id">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($states as $state) { ?>
                  <?php if ($state['zone_id'] == $states_id) { ?>
                    <option value="<?php echo $state['zone_id']; ?>" selected="selected"><?php echo $state['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $state['zone_id']; ?>"><?php echo $state['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_state) { ?>
                <span class="error"><?php echo $error_state; ?></span>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_locality; ?></td>
                 <td>
                    <select name="locality_id">
                        <option value=""><?php echo $text_select; ?></option>
                    </select>
                    <?php if ($error_locality) { ?>
                <span class="error"><?php echo $error_locality; ?></span>
                <?php } ?>
                </td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
            <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_user_group; ?></td>
            <td><select name="user_group_id">
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_password; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"  />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_confirm; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php if ($error_confirm) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">

$('select[name=\'states_id\']').bind('change', function() {
    console.log('index.php?route=user/user/localityall&token=<?php echo $token; ?>');
    state_id = this.value;
	$.ajax({
		url: 'index.php?route=user/user/localityall&token=<?php echo $token; ?>',
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
</script> 
<?php echo $footer; ?> 