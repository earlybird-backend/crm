<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//Created by loudon

class CurrencylistModel extends CI_Model {

    var $table = "apr_currency_by_admin";
    var $datestring = "%Y-%m-%d";
    var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
    var $currentDate = '';
    var $currentDateTime = '';

    public function __construct() {
        parent::__construct();
        $this->currentDate = mdate($this->datestring, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());
    }

    // Get category
    public function getCurrencyById($CurrencyId = '') {
        $this->db->select('*');

        if ($CurrencyId > 0) {
            $this->db->where('CurrencyId', $CurrencyId);
        }

        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {

            return false;
        }
    }

    // Get category
    //
	

    public function getCurrencyList($search_data = null, $num = "", $offset = "") {

        $this->db->select('*');
        $this->db->from('apr_currency_by_admin');

        if ($search_data != null && $search_data != "") {
            $this->db->or_like($search_data);
        }
        //$this->db->where('Role', 'supplier');
        $this->db->order_by('CurrencyId', 'ASC');
        if ($num != "" || $offset != "") {
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();
        $rec = $query->result_array();
        return $rec;
    }

    
     public function getCurrency() {
         
        $this->db->select('*');
        $this->db->from('apr_currency_by_admin');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
   
    
    public function addCurrency() {

        $data = array(
						'CurencyName' => $this->input->post('CurrencyName'),
						'CurrencySign' => $this->input->post('CurrencySign'),
						'AddedDate' => $this->currentDateTime
        );
        $this->db->insert($this->table, $data);
    }

    public function editCurrency($Id = '') {
        $data = array(
						'CurencyName' => $this->input->post('CurrencyName'),
						'CurrencySign' => $this->input->post('CurrencySign'),
						'AddedDate' => $this->currentDateTime
        );
        $this->db->where('CurrencyId', $Id);
        $this->db->update('apr_currency_by_admin', $data);
    }

    public function deleteCurrency($Id = '') {

        $this->db->where('CurrencyId', $Id);
        $this->db->delete($this->table);
    }

}
