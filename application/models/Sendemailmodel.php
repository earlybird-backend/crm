<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SendEmailModel extends CI_Model {

	public function __construct()
    {
        parent::__construct();
		$this->load->library('phpmailer');
		
    } 	
	## Send Email	
	public function sendEmail($to,$subject,$body,$name='',$cc='',$bcc='')
	{
		
	   $mail = new PHPMailer;
		$mail->AddAddress($to, $name);
		$mail->From		= $this->config->item('smtp_from');
		$mail->FromName = "EPFO";
		$mail->Subject 	=  $subject;
		if($cc!=''){
			$mail->AddCC($cc);
		}
		if($bcc!=''){
			$mail->AddBCC($cc);
		}
		$mail->IsSMTP();


		//modify by loudon 2017-09-01
		//smtp login setting 
		//=========================== begin ===========================
		$mail->Host = $this->config->item('smtp_host');
		$mail->CharSet = $this->config->item('charset');
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $this->config->item('smtp_crypto');
		$mail->Port = $this->config->item('smtp_port');
		$mail->Username = $this->config->item('smtp_from');
		$mail->Password = $this->config->item('smtp_pass');
		//===========================  end  ===========================
		$mail->WordWrap = 200; 
		$mail->IsHTML(true);   
		$mail->Body  = $body;
		if($mail->Send())
		{
			return true;
		}else{
			
			return false;
		}
	
	}
	
	## Send Email	
	public function sendEmailAll($to=array(),$subject,$body,$name='',$cc='',$bcc='')
	{
		//echo $body; die;
		
		$mail = new PHPMailer;
		if(count($to) > 0){
			foreach($to as $v){
			  if($v)
			  {
				$mail->AddAddress($v, $v);	
			  }
			}
			
		}else{
			$mail->AddAddress($to, $name);
		}			
		
		$mail->From		= $this->config->item('smtp_from');
		$mail->FromName = "EPFO";
		$mail->Subject 	=  $subject;
		if($cc!=''){
		    $mail->AddCC($cc);
		}
		if($bcc!=''){
		    $mail->AddBCC($cc);
		}
		$mail->IsSMTP();
		
		
		//modify by loudon 2017-09-01
		//smtp login setting
		//=========================== begin ===========================
		$mail->Host = $this->config->item('smtp_host');
		$mail->CharSet = $this->config->item('charset');
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $this->config->item('smtp_crypto');
		$mail->Port = $this->config->item('smtp_port');
		$mail->Username = $this->config->item('smtp_from');
		$mail->Password = $this->config->item('smtp_pass');
		//===========================  end  ===========================
		$mail->WordWrap = 200;
		$mail->IsHTML(true);
		$mail->Body  = $body;
		if($mail->Send())
		{
		    return true;
		}else{
		    	
		    return false;
		}
	
	}	



}
