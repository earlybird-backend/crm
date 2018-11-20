<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HistoryPlansModel extends CI_Model {

	
	var $table = "customer_early_pay_plans";
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
	
	public function getPlanList($search_data = null, $num = "", $offset = "") {

        $this->db->select('*');
		//$this->db->where('PlanStatus', 'confirm_payment_copy');
        $this->db->from('customer_early_pay_plans');

        if ($search_data != null && $search_data != "") {
            $this->db->or_like($search_data);
        }
        //$this->db->where('Role', 'supplier');
        $this->db->order_by('PlanId', 'ASC');
        if ($num != "" || $offset != "") {
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();
        $rec = $query->result_array();
        return $rec;
    }	
	
	  public function getHistoryPlanById($PlanId='') {
		
        $this->db->select('*');

        if ($PlanId > 0) {
            $this->db->where('PlanId', $PlanId);
        }

        $query = $this->db->get('customer_early_pay_plans');
		//print_r($this->db->last_query()); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {

            return false;
        }
    }
	
	 public function getHistoryPayrecordById($PlanId='') {
		
        $this->db->select('*');

        if ($PlanId > 0) {
            $this->db->where('PlanId', $PlanId);
        }

        $query = $this->db->get('winners');
		//print_r($this->db->last_query()); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {

            return false;
        }
    }
}
