<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemplateModel extends CI_Model {

	
	var $table = "emailtemplates";	
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
	
	// Get category
	public function getTemplateById($Id='',$Status='')
	{ 
		$this->db->select('*');
		
		if($Id >0)
		{
		  $this->db->where('Id', $Id);
		}
		
		if($Status >0)
		{
		 $this->db->where('Status', $Status);
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
	
	// Get category
	//
	public function getTemplate($search_data = null,$num="", $offset="")
	{ 
		$this->db->select('*');
		
		if($search_data != null && $search_data != "")
		{
		  $this->db->or_like($search_data);
		}
		
		$this->db->order_by('Id', 'ASC');
		
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
	
	
	// Delete category
	public function deleteTemplate($Id='')
	{ 
		
		$this->db->where('Id', $Id);
		$this->db->delete($this->table); 
		
	}
	
	public function addTemplate()
	{ 
		
		$data = array('Name' => $this->input->post('Name'),
		'Subject' => $this->input->post('Subject'),
		'Body' => $this->input->post('Body'),
		'Status' => $this->input->post('Status'),
		'Modified' => $this->currentDateTime);		
        $this->db->insert($this->table, $data); 
		
	}
	
	public function editTemplate($Id='')
	{ 
		
		$data = array('Name' => $this->input->post('Name'),
		'Subject' => $this->input->post('Subject'),
		'Body' => $this->input->post('Body'),		
		'Status' => $this->input->post('Status'),
		'Modified' => $this->currentDateTime);
						
		$this->db->where('Id', $Id);
		
        $this->db->update($this->table, $data); 
	}
	
	
}
