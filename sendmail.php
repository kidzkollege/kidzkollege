<?php 
    $headers=getallheaders();
	$ip=(($headers['CF-Connecting-IP']!='')? $headers['CF-Connecting-IP']:$headers['cf-connecting-ip']);
	if($ip=='') {$ip=$headers['x-forwarded-for'];}
	if($ip=='') {$ip=$_SERVER['REMOTE_ADDR'];}
    $url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	$secret = '0x4AAAAAABPK7cADYN3ew85RLQe--_8wfzM';
	$response = $_POST['cf-turnstile-response'];
	$data = array('secret'=>$secret,'response'=>$response,'remoteip'=>$ip);
	$options = array('http'=>array('method'=>'POST','content'=>http_build_query($data)));
	$stream = stream_context_create($options);
	$result = file_get_contents($url_path, false, $stream);
	$responseKeys = json_decode($result,true);
	
	if($responseKeys["success"]==1)
	{
     $to = 'kidzkollegepreschool@gmail.com,mona@desynz.co.uk';
     $subject .= "Get In Touch from Kidz Kollege";
     $headers .= "From: Kidz Kollege <no-reply@desynz.co.uk>\r\n";
     $headers .= "MIME-Version: 1.0\r\n";
     $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
     $table='<table>
          <tr>
          <p>Dear Kidz Kollege, <p>
          <p>The following are the enquiry details. <p>
          
          </tr>
          <tr>
            <td>Name : -'." ".$_POST['fname'].' ' .$_POST['lname'].'</td></br>
          </tr>
          
          <tr>
            <td>Contact Number : -'." ".$_POST['mobile'].'</td></br>
          </tr>
          <tr>
            <td>Email : -'." ".$_POST['email_id'].'</td></br>
          </tr>
          <tr>
            <td>Message : - '." ".$_POST['message'].'</td></br>
          </tr>
		   <tr>
            <td>How did They hear : - '." ".implode(', ',$_POST['hear']).'</td></br>
          </tr>
          
          <tr>
            <p>Thanks And Regards,<br>Kidz Kollege</p>
          </tr>
        </table>';
     if (mail($to, $subject, $table, $headers)) 
	 {
       echo 1;
     }
	else
	{
	   echo 0;
	}
  }
   

?>