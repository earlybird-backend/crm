<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class CompanyModel extends CI_Model {


	public function __construct()    {
        parent::__construct();
        $this->db = $this->load->database('cisco',true);
    }

    public function getAllCompanys(){

        $query = $this->db->get('Base_Companys');

        $data = $query->result_array();

        $result = array();
        foreach ( $data as $val){

            $result[$val["Id"]] = $val;
        }

        return $result;
    }

    public function getCompany($Id)
    {

        $this->db->where("Id" , $Id);
        $query = $this->db->get('Base_Companys');

        return $query->first_row('array');
    }


}
