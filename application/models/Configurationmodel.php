<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigurationModel extends CI_Model {

	
	var $table = "plan_time_settings";
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
	//
	public function getConfiguration($search_data = null,$num="", $offset="")
	{ 
		$this->db->select('*');
		
		if($search_data != null && $search_data != "")
		{
		  $this->db->or_like($search_data);
		}
		
		$this->db->order_by('SettingId', 'ASC');
		
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
	
	
   public function editConfiguration($Id='')
	{ 
		$data = array('PlanStartTime' => $this->input->post('PlanStartTime'),
		'PlanEndTime' => $this->input->post('PlanEndTime'),
		'ProposalTime' => $this->input->post('ProposalTime'),
		'ApproveProposalTime' => $this->input->post('ApproveProposalTime'),
		'UpdateTime' => $this->currentDateTime
		);
		
		$this->db->update($this->table, $data);
		
		//print_r($this->db->last_query()); die; 
	}
	
	
}
