<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SendInvitionModel extends CI_Model {

	
	var $table = "invite_users_by_admin";
	var $datestring = "%Y-%m-%d";
	var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
	var $currentDate = '';
    var $currentDateTime = '';
	
	
	public function __construct()
    {
        parent::__construct();
		
		$this->currentDate = mdate($this->datestring, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());
		
    } 
	
	   public function getemailList($search_data = null, $num = "", $offset = "") {

        $this->db->select('*');
        $this->db->from('invite_users_by_admin');

        if ($search_data != null && $search_data != "") {
            $this->db->or_like($search_data);
        }
        //$this->db->where('Role', 'supplier');
        $this->db->order_by('id', 'ASC');
        if ($num != "" || $offset != "") {
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();
        $rec = $query->result_array();
        return $rec;
    }
	 public function addinviteemail() {

        $data = array(
						'invite_email' => $this->input->post('Email'),
						'added_by_user' => 'admin_bak',
						'invite_status' => 1,
						'register_status' => 0,
						'added_date' => $this->currentDateTime
        );
        $this->db->insert($this->table, $data);
    }
	
	}
