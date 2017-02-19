<div style="display:none;">
		<div id="div-welcome" >
			<?php echo $message; ?>
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