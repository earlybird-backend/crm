<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminModel extends CI_Model {

	var $table = "admin_bak";
	var $datestring = "%Y-%m-%d";
	var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
	var $currentDate = '';
    var $currentDateTime = '';
	
	
	public function __construct()
    {
        parent::__construct();
		
		$this->currentDate = mdate($this->datestring, time()) ;
        $this->currentDateTime = mdate($this->dateStringWithTime, time()) ;
		
    }
	
	public function validateUser($email,$pass)
	{
		$conditions = array('Email' => $email, 'Password' => $pass );
		$this->db->select('*');
		$this->db->where($conditions);
		$query = $this->db->get($this->table);
		$count = $query->num_rows();
		if($count){ 
			return true ;
		}else{ 
			return false;
		}
	}
	
	
	public function validateUserEmail($email)
	{
		$conditions = array('Email' => $email);
		$this->db->select('*');
		$this->db->where($conditions);
		$query = $this->db->get($this->table);
		$count = $query->num_rows();
		if($count){ 
			return true ;
		}else{ 
			return false;
		}
	}
	
	public function getAdminDetails($email='')
    {
	   $this->db->select('*');
		
		if($email > 0)
		{
		  $this->db->where('Email', $email);
		}
		
		$query = $this->db->get($this->table);

		if($query->num_rows() >0 )
		{ 
			return $query->result_array();
			
		}else{ 
		
			return false;
		}
		
    }
	
	public function getAdminDetailsById($AdminId='')
    {
	   $this->db->select('*');
		
		if($AdminId > 0)
		{
		  $this->db->where('AdminId', $AdminId);
		}
		
		$query = $this->db->get($this->table);

		if($query->num_rows() >0 )
		{ 
			return $query->result_array();
			
		}else{ 
		
			return false;
		}
		
    }
	
	public function validatePassword($oldpassword,$AdminId)
	{
		$result = $this->getAdminDetailsById($AdminId);
				
		if($result[0]['Password'] == md5($oldpassword))
		{ 
			return true;
		}else{
		
			return false;
		
		}
	
	}
	
	public function changepassword($AdminId,$password)
    {
       
	   $data = array('Password' => md5($password),'UpdatedDate' => $this->currentDateTime);
		$this->db->where('AdminId', $AdminId);
        $this->db->update($this->table, $data);
		
    } 
	
	public function editprofile($AdminId)
    {
	   $data = array('Email' => $this->input->post('Email'),
	   'Name' => $this->input->post('Name'),
	   'UpdatedDate' => $this->currentDateTime);
		$this->db->where('AdminId', $AdminId);
        $this->db->update($this->table, $data);
		
    } 
	
	public function generateRandomPassword($length){
		$this->load->helper('string');
		return random_string('alnum',$length);
	}
	
	public function monthCombo($sel=''){
		$combo = '<select name="smonth">';
		$combo.= '<option value="">--Month--</option>';
		for($i=1;$i<=12;$i++){
			$selected = ($sel==$i)?'selected="selected"':($i==date('m'))?'selected="selected"':'';
			$combo.= '<option value="'.$i.'" '.$selected.'>'.date("M",mktime(0, 0, 0, $i, 1, date("Y"))).'</option>';
		}
		$combo .= '</select>'; 
		return $combo;
	}
	public function yearCombo($sel=''){
		$combo = '<select name="syear">';
		$combo.= '<option value="">--Year--</option>';
		$startedYear = 2012;
		for($i=$startedYear;$i<=date("Y")+5;$i++){
			$selected = ($sel==$i)?'selected="selected"':($i==date('Y'))?'selected="selected"':'';
			$combo.= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
		}
		$combo .= '</select>'; 
		return $combo;
	
	}
	public function monthCombo1($sel=''){
		$combo = '<select name="smonth">';
		$combo.= '<option value="">--Month--</option>';
		for($i=1;$i<=12;$i++){
			$selected = 'selected="selected"';
			if($sel==$i){
				$combo.= '<option value="'.$i.'" '.$selected.'>'.date("M",mktime(0, 0, 0, $i, 1, date("Y"))).'</option>';
			}else{
				$combo.= '<option value="'.$i.'" >'.date("M",mktime(0, 0, 0, $i, 1, date("Y"))).'</option>';
			}
		}
		$combo .= '</select>'; 
		return $combo;
	}
	public function yearCombo1($sel=''){
		$combo = '<select name="syear">';
		$combo.= '<option value="">--Year--</option>';
		$startedYear = 2012;
		for($i=$startedYear;$i<=date("Y")+5;$i++){
			$selected = 'selected="selected"';
			if($sel==$i){
				$combo.= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
			}else{
				$combo.= '<option value="'.$i.'">'.$i.'</option>';
			}
		}
		$combo .= '</select>'; 
		return $combo;
	
	}
	
}