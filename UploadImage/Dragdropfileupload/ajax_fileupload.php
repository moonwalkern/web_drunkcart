<?php
  $str = file_get_contents('php://input');
  //echo $filename = md5(time().uniqid()).".jpg";
  
  $headers = getallheaders();
  //echo $headers['X-File-Path']; 
  $filename = $headers['X-File-Name']; 
  $path = $headers['X-File-Path'];
  //file_put_contents("uploads/".$filename,$str);
  file_put_contents($path.$filename,$str);
  //echo print_R($headers,TRUE);
  //echo $filename;
  // In demo version i delete uplaoded file immideately, Please remove it later
  //unlink("uploads/".$filename);
?>