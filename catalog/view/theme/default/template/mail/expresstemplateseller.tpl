<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- If you delete this tag, the sky will fall on your head -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>sWAPdEAL.in</title>

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
						<td align="right"><h6 class="collapse">sWAPdEAL</h6></td>
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
						
						<h5>Welcome Back</h5>
                        
                            <p class="lead"> We are thrilled that some one liked your product on sWAPdEAL Online portal!</p>
                        <table bgcolor="#3399FF" width="100%">
                            <tr>    
                                    <td style="font-weight:bold"><font FACE="Geneva, Arial" SIZE=2 color="white">Details of Product</font></td>
                                                                        
                            </tr>
                        </table>
                        
                        <table bgcolor="#FDFFFD" width="100%">
                        <tr>
								<td>
                                    <div class="details_big_box"><font face="verdana" size="4"></font></div>
                                    <div class="prod_box_big"><div class="top_prod_box_big"></div>
                                    <div class="center_prod_box_big"><div class="product_img_big"> 
                                    <a href="<?Php echo $data['link']; ?>"><img src="<?Php echo $data['image']; ?>" width="70" height="70" /></a></div>
                                    <div class="details_big_box"><div class="product_title_big"></div>
                                    <div class="specifications"> Location: <span class="blue"><?Php echo $data['location']; ?></span>
                                    <br>Date: <span class="blue"><?Php echo $data['date']; ?> </span>
                                    <br><?Php echo $data['type_customer']; ?> <?Php echo $data['condition']; ?> sWap(No)<br></div>
                                    <div class="specifications">Price: <span class="price"><?Php echo $data['price']; ?></span></div>
                                    </div></div>
                                    <div class="bottom_prod_box_big"></div>
                                    </div>
                                </td>
                        </tr>
                        </table>
                        <p class="callout">
							     Reply to Buyer here. <a href='<?Php echo $data['replylink'];  ?> '>Reply Now! &raquo;</a>
						</p>
                        
						<table class="social" width="100%">
                            
							<tr>
								<td>

									
									<!--- column 1 -->
									<table align="left">
										<tr>
											<td>				
                                                    <h5 class="">Buyer Details:</h5>
                                                <p class="">
                                                    <a href="#" class="soc-btn fb">Email : <?Php echo $data['email_to'];  ?> 
                                                    <a href="#" class="soc-btn tw">Mobile : <?Php echo $data['mobile_to'];  ?></a> 
                                                    <a href="#" class="soc-btn gp">Message : <?Php echo $data['message'];  ?></a>
                                                </p>
											</td>
										</tr>
									</table><!-- /column 1 -->	
									
									
									
									<span class="clear"></span>	
									
								</td>
							</tr>
						</table><!-- /social & contact -->
                        <?Php 
                            if($data['type'] == 'Buyer') { 
                        ?>
						<table class="social" width="100%">
							<tr>
								<td>
									<table align="left">
										<tr>
											<td>				
												<h5 class="">Login to your swapdeal account:</h5>
												<p class="">
                                                    <a href="#" class="soc-btn sw">Username : <?Php echo $data['username'];  ?> 
                                                    <a href="<?Php echo $data['forgotpassword'];  ?>" class="soc-btn gp">Password : <?Php echo $data['password'];  ?></a> 
                                                </p>
=											</td>
										</tr>
									</table>	
									<span class="clear"></span>	
									
								</td>
							</tr>
						</table>
                         <?Php 
                            }  
                         ?>  
						<!-- Callout Panel -->
					
						
						<h6>Login to sWAPdEAL folow this.....</h6>
						<p>Your venturing to a world of amazing buying and selling options.....</p>
						<a class="btn" href="../index-login.php">Click Me!</a>
												
						
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