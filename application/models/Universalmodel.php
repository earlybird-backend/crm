<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class UniversalModel extends CI_Model {

	function MyModel()
	{
		
		parent::__construct();
		
	}	
	
	public function save($table, $columns = array(),$where = NULL, $id = NULL)
	{
		if($id) {
			$this->db->where($where, $id);
			$result = $this->db->update($table, $columns);
		} else{
		
			$result = $this->db->insert($table, $columns);
		}
		
		//print_r($this->db->last_query());// die;
		
		if($result) {
			if(!$id) 
				return $this->db->insert_id();
			else
				return TRUE;
		} else
			return FALSE;
	}
	
	public function getCounty()
	{
		$sql = "SELECT * FROM `county` ";
		$query = $this->db->query($sql);
	   
		$result =   $query->result_array();
	   //echo '<pre>'; print_r($result); echo '</pre>'; die;
	   
		$array = array(''=> $this->lang->line('Select Country') );
	   
		  foreach($result as $k=>$v)
		  {
			//$array[$k['m_county']][$k['m_Id']] = $k['c_county'];
			$array[$v['id']] = $v['name'];
		  }
	   //echo '<pre>'; print_r($array); echo '</pre>'; die;
		return  $array;
	 
	}
	
public function getRecords($table, $where = NULL,$order_by=NULL, $num = NULL, $offset = NULL)
	{ 
		$this->db->select('*');
		
		if($where != NULL )
		{
		 	$this->db->where($where);
		}
		
		if($num != NULL || $offset != NULL)
		{
			$this->db->limit($num, $offset);
		}
		
		if($order_by)
		{
		   $this->db->order_by($order_by[0], $order_by[1]);
		}	
		else
		{
		   //$this->db->order_by('Id', 'DESC');
		}	
			
		//$this->db->order_by('title desc, name asc'); 
		
		$query = $this->db->get($table);
		
		//print_r($this->db->last_query()); //die;	
				
		if($query->num_rows() > 0 )
		{ 
			return $query->result_array();
			
		}else{ 
		
			return array();
		}
	

	}
        
   // Delete Records
	public function deleteRecord($table, $where = array())
	{
		$this->db->where($where);
		
		$res = $this->db->delete($table); 
		
		if($res)
			return TRUE;
		else
			return FALSE;
	}
	
	// Check Records if Available in table or not
	public function checkRecord($table, $where = array())
	{ 
		$this->db->select('*');
		
		$this->db->where($where);
		
		$query = $this->db->get($table);
		
		//print_r($this->db->last_query());
		/*print_r($query->num_rows());
		echo $query->num_rows();
		var_dump($query->num_rows());
		die;*/
		
		if($query->num_rows() > 0 )
		{ 
			return TRUE ;
			
		}else{ 
		
			return FALSE ;
		}
	}	
	





public function getOpenForDays()
{
	 $data = array(
              ''   => '--select--',
              '3'  => '3 days' ,
			  '5'  => '5 days' ,
              '7'  => '7 days' ,
			  '10' => '10 days' ,
			  '15' => '15 days' ,
			  '30' => '30 days'
			  );
	return $data;
	
}

public function getYesNo($val)
{
	if($val){
		$return =  'Yes';
	}else{
	
		$return =  'No';
	}
	return $return;
}


 
 
public function isLogin()
	{ 
		if($this->session->userdata("logged") == TRUE && $this->session->userdata("UserId") !='' )
		{
		  return TRUE;
			
		}else{
		
			return FALSE;
		}
	}
	
//
// Creat Password hash
//

public function encryptDecrypt($string,$mode ='E')
{
	$return = NULL ;
	$separator = '~~' ;
	$str1 = '123456' ;
	$str2 = '123456' ;
	
	if($mode == 'E')
	{
	  $return = base64_encode($str1.$separator.$string.$separator.$str2);
	  
	}else{
	
		$result = base64_decode($string);		
		
		$returnArray = explode($separator,$result);
		//echo '<pre>'; print_r($returnArray);
		$return  = $returnArray[1];
	}
	
	return $return;
}


public function getUsernameById($table,$UserId){
		$this->db->select('Username');
		$this->db->from($table);
		$this->db->where('UserId',$UserId);
		$query = $this->db->get();
		if($query->num_rows() > 0 )
		{
			$rec = $query->result_array();
			return $rec[0]['Username'];
		}else{
			return array();
		}	
	}
	public function getUserIdByUsername($table,$Username){
		$this->db->select('UserId');
		$this->db->from($table);
		$this->db->where('Username',$Username);
		$query = $this->db->get();
		if($query->num_rows() > 0 )
		{
			$rec = $query->result_array();
			return $rec[0]['UserId'];
		}else{
			return array();
		}	
	}

	
public function generateRandomString($length){
		$this->load->helper('string');
		return random_string('alnum',$length);
	}
 public function generateRandomNumber($length){
		$this->load->helper('string');
		return random_string('numeric',$length);
	}
public function RandomString($length='') {
		$characters  = strtoupper('abcdefghijklmnopqrstuvwxyz');
		$characters1 = 'abcdefghijklmnopqrstuvwxyz';
		$characters2 = '0123456789';
		$string = '';
		$string1='';
		do{
			$string1 .= $characters[rand(0, strlen($characters))];
		}while(strlen($string1)<1);
		 $string .= $string1;
		do{
			$string2 .= $characters1[rand(0, strlen($characters1))];
		}while(strlen($string2)<5);
		 $string .= $string2;
	   do{
			$string3 .= $characters2[rand(0, strlen($characters2))];
		}while(strlen($string3)<1);
		$string .= $string3;
		return $string;
	} 





}