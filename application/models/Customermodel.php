<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends CI_Model {

	public function __construct()    {
        parent::__construct();		


        $this->db = $this->load->database('cisco',true);
		
    } 	
	// Get category
	public function getUserById($UserId='')
	{ 
		$this->db->select('*');
		
		if($UserId > 0)
		{
		  $this->db->where('UserId', $UserId);
		}
                
		$query = $this->db->get($this->table);
		if($query->num_rows() >0 )
		{ 
			return $query->result_array();
			
		}else{ 
		
			return false;
		}
	}
	
	// Get category
	//
	public function getUser($search_data = null,$num="", $offset="")
	{ 
		$this->db->select('*');
		
		if($search_data != null && $search_data != "")
		{
		  $this->db->or_like($search_data);
		}
		
		$this->db->order_by('UserId', 'ASC');
		
		if($num!="" || $offset!="")
		{
			$this->db->limit($num, $offset);
		}
		
		$query = $this->db->get($this->table);
		
		//print_r($this->db->last_query()); die;
		
		if($query->num_rows() >0 )
		{ 
			return $query->result_array();
			
		}else{ 
		
			return false;
		}
	}
	
	
	public function getUsersList($search_data = null,  $num="", $offset=""){
		
		$this->db->select('*');
		$this->db->from('site_users');
		
		if($search_data != null && $search_data != ""){
		  $this->db->or_like($search_data);
		}
		$this->db->where('Role', 'customer');
		$this->db->order_by('UserId', 'ASC');
		if($num!="" || $offset!=""){
			$this->db->limit($num, $offset);
		}
		$query = $this->db->get();
		$rec = $query->result_array();
		return $rec;
		
	}
	
	public function getUsersListCount($search_data = null,$num="", $offset=""){
	
		$this->db->select('*');
	        $this->db->from('site_users as o');		
		if($search_data != null && $search_data != ""){
		  $this->db->or_like($search_data);
		}
		$this->db->where('Role', 'customer');
		$this->db->order_by('UserId', 'ASC');
		if($num!="" || $offset!=""){
			$this->db->limit($num, $offset);
		}
		$query = $this->db->get();
		//print_r($this->db->last_query()); die;	
		$rec = $query->result_array();
		$recount = $query->num_rows();
		return $recount;
	}
        
     public function getUsers(){
        $this->db->select('*');
        $this->db->from('site_users');
        $query = $this->db->get();
        $rec = $query->result();  
        return $rec;
    }
    public function viewUser($id='')
	{ 		
		 $this->db->select('*');
                $this->db->from('site_users');
		$this->db->where('UserId', $id);
                  $query = $this->db->get();
                $rec = $query->result();
                //print_r($rec); die;
        return $rec;
		   
	}
	
	 public function getCompanyprofileById($id='')
	{ 		
		 $this->db->select('*');
                $this->db->from('site_users');
		$this->db->where('UserId', $id);
                  $query = $this->db->get();
                $rec = $query->result();
                //print_r($rec); die;
        return $rec;
		   
	}
         public function getcontactById($id='')
	{ 		
		 $this->db->select('*');
                $this->db->from('site_users');
		$this->db->where('UserId', $id);
                  $query = $this->db->get();
                $rec = $query->result();
                //print_r($rec); die;
        return $rec;
		   
	}
          public function getsuppliersById($id='',$search_data = null,  $num="", $offset="")
	{ 		
		 $this->db->select('*');
                $this->db->from('supplier_by_customer');
                if($search_data != null && $search_data != ""){
		  $this->db->or_like($search_data);
		}
		$this->db->where('CustomerId', $id);
                if($num!="" || $offset!=""){
			$this->db->limit($num, $offset);
		}
                  $query = $this->db->get();
                $rec = $query->result();
                //print_r($rec); die;
        return $rec;
		   
	}
         public function resetrecord($id='')
	{ 		
		 $this->db->select('*');
                $this->db->from('site_updaterecords');
		$this->db->where('UserId', $id);
                  $query = $this->db->get();
                $rec = $query->result();
                //print_r($rec); die;
        return $rec;
		   
	}
         
      
		
}
