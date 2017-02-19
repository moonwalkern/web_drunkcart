<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- If you delete this tag, the sky will fall on your head -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>dRUNKcart.com</title>

</head>

<body bgcolor="#FFFFFF">

<!-- HEADER -->
<table class="head-wrap" bgcolor="">
	<tr>
		
		<td class="header container">
			
				<div class="content">
					<table bgcolor="#999999">
					<tr>
						<td><img src="<?Php echo $data['logo']; ?>" width="70" height="70" /></td>
						<td align="right"><h6 class="collapse">dRUNKcart</h6></td>
					</tr>
				</table>
				</div>
				
		</td>
	</tr>
</table><!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap">
	<tr>

		<td class="container" bgcolor="#FFFFFF">

			<div class="content">
			<table>
				<tr>
					<td>
						
						<h5>Welcome, <?Php echo $data['username']; ?></h5>
						  <p class="lead">Welcome to dRUNKcart! We're thrilled that you've enrolled in dRUNKcart APP!</p>
						  <p class="lead">Just one step left.</p><p class="lead">Activate your account now using the OTP <?Php echo $data['otp'];  ?></p>
                        <table class="social" width="100%">
							<tr>
								<td>
									
									<!--- column 1 -->
									<table align="left" class="column">
										<tr>
											<td>				
												
												<h5 class="">Your sign in details:</h5>
												<p class="">
                                                    <a href="#" class="soc-btn fb">Your username</a> 
                                                    <a href="#" class="soc-btn tw">Your Password</a> 
                                                    <a href="#" class="soc-btn gp">Your mobile</a>
                                                </p>
						
												
											</td>
										</tr>
									</table><!-- /column 1 -->	
									<table align="left" class="column">
										<tr>
											<td>				
												
												<h5 class="">Dont share the details</h5>
												<p class="">
                                                    <a href="#" class="soc-btn tw"><?Php echo $data['email'];  ?></a> 
                                                    <a href="#" class="soc-btn tw"><?Php echo $data['password'];  ?></a> 
                                                    <a href="#" class="soc-btn tw"><?Php echo $data['mobile'];  ?></a>
                                                </p>
						
												
											</td>
										</tr>
									</table>
									
									
									<span class="clear"></span>	
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
									
		</td>
	
	</tr>
</table><!-- /BODY -->

<!-- FOOTER -->
<table class="footer-wrap">
	<tr>
		<td></td>
		<td class="container">
			
				<!-- content -->
				<div class="content">
				<table>
				<tr>
					<td align="center">
						<p>
							<a href="#">Terms</a> |
							<a href="#">Privacy</a> |
							<a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>
						</p>
					</td>
				</tr>
			</table>
				</div><!-- /content -->
				
		</td>
		<td></td>
	</tr>
</table><!-- /FOOTER -->

</body>
</html>