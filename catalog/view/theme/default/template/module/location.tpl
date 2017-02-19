<div id="location">
  <div class="heading" id="location_name">
    <h4><?php echo $city; ?></h4><a><span id="location-total"><?php echo $location_name; ?></span></a>
    
  </div>
      
  <div class="content" id="popular_div">
    
    <div class="content"><tr><td>Popular City</td></tr>
        <table class="mini-location-info"><tr>
            <?php foreach ($popular as $key => $value) { ?>
                    <?php if (($key % 4) == 0) {?>
                        </tr><tr>
                    <?php } ?> 
                <td><a onclick="setLocation('<?php echo $value['locality_id']; ?>', '<?php echo $value['locality_name']; ?>', '<?php echo $value['state_id']; ?>', '<?php echo $value['state']; ?>');"><?php echo $value['locality_name']; ?></a></td>
            <?php } ?>                     
            
        </table>
    </div>    
    <div class="content">Top Location    
        <table class="mini-location-info"><tr>
            <?php foreach ($unpopular as $key => $value) { ?>
                <?php if (($key % 4) == 0) {?>
                    </tr><tr>
                <?php } ?> 
                <td><a onclick="setLocation('<?php echo $value['locality_id']; ?>', '<?php echo $value['locality_name']; ?>', '<?php echo $value['state_id']; ?>', '<?php echo $value['state']; ?>');"><?php echo $value['locality_name']; ?></a></td>
            <?php } ?> 
            </tr>
            <tr><td class="mini-location-more"><a href="<?php echo $locationlist_url; ?>">more...</a></td></tr>
        </table>
    </div>    
  </div>
</div>