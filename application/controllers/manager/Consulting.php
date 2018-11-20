<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Consulting extends MY_Controller {

    /**  CI_Controller

     * Index Page for this controller.

     * 

     * Maps to the following URL

     * 		http://example.com/index.php/welcome

     * 	- or -  

     * 		http://example.com/index.php/welcome/index

     * 	- or -

     * Since this controller is set as the default controller in 

     * config/routes.php, it's displayed at http://example.com/

     *

     * So any other public methods not prefixed with an underscore will

     * map to /index.php/welcome/<method_name>

     * @see http://codeigniter.com/user_guide/general/urls.html

     */
    var $data = array();
    var $error = array();
    var $template = array();
    var $middle = '';
    var $left = '';
    var $right = '';
    var $datestring = "%Y-%m-%d";
    var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";


    public function __construct() {

        parent::__construct();

        $this->checkLogin();

        $this->currentDate = mdate($this->datestring, time());

        $this->currentDateTime = mdate($this->dateStringWithTime, time());

        $this->load->library('phpmailer');

        $this->load->library('upload');        

        $this->load->model('UploadsupplierModel');

        $this->load->model('PlansModel');
        $this->load->model('CurrencylistModel');
		$this->load->model('ConfigurationModel');


        $this->data['link'] = $this->current_controller;

					
    }

    public function checkLogin() {
        if ($this->session->userdata("Role") != 'admin') {

            $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));

            redirect('auth', 'refresh');
            
        }
    }
    

    public function setUserData() {

        $columns = array('UserId' => $this->session->userdata('UserId'));

        $table = 'site_users';

        $result = $this->UniversalModel->getRecords($table, $columns);

        $this->userdata = $result[0];

        $this->userdata['Role'] = $this->session->userdata("Role");

        $this->userdata = $this->session->userdata;
    }
    
    public function index() {
        $this->data['title'] = 'Consulting';
        $this->load->view('manager/consulting', $this->data);
    }

    public function editfield(){
        $field = $this->input->get('field');
        $field_title = "";
        $this->db = $this->load->database('bird',true);
        $sql = "select * from ";
        switch($field){
            case 'role':
                $sql .= "Base_PositionRole";
                $field_title = '角色';
                break;
            case 'region':
                $sql .= "Base_Region";
                $field_title = '区域';
                break;
            case 'interest':
                $sql .= "Base_Interest";
                $field_title = '兴趣';
                break;
        }

        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->data['field'] = $field;
        $this->data['filed_title'] = $field_title;
        $this->data['data'] = $rs;
        $this->data['title'] = 'Edit Field';
        $this->data['pre_nav'] = array('title' => 'Manager', 'uri'=> $this->current_controller) ;
        $this->load->view('manager/consulting_edit', $this->data);
    }

    //保存字段多语言信息
    public function savefield(){
        $this->db = $this->load->database('bird',true);
        $cnname = $this->input->post('cnname');
        $twname = $this->input->post('twname');
        $enname = $this->input->post('enname');
        $field = $this->input->post('field');
        $id = $this->input->post('id');
        if(!isset($id)){
            $data = array(
                'cnname'=>$cnname,
                'name'=>$cnname,
                'twname'=>$twname,
                'enname'=>$enname,
                'sortname'=>''
            );
            switch ($field){
                case 'role':
                    $this->db->insert('Base_PositionRole',$data);
                    break;
                case 'region':
                    $this->db->insert('Base_Region',$data);
                    break;
                case 'interest':
                    $this->db->insert('Base_Interest',$data);
                    break;
            }

            $id = $this->db->insert_id();
        }else{
            $data=array(
                'cnname'=>$cnname,
                'name'=>$cnname,
                'twname'=>$twname,
                'enname'=>$enname,
            );
            $this->db->where('Id',$id);
            switch ($field){
                case 'role':
                    $this->db->update('Base_PositionRole',$data);
                    break;
                case 'region':
                    $this->db->update('Base_Region',$data);
                    break;
                case 'interest':
                    $this->db->update('Base_Interest',$data);
                    break;
            }
        }
        $data['Id'] = $id;
        $this->toJson(array('code'=>0,'data'=>$data));
    }

    public function getfielditem($id,$field){
        $this->db = $this->load->database('bird',true);
        $sql = "select * from # where Id ={$id}";
        switch ($field){
            case 'role':
                $sql = str_replace('#','Base_PositionRole',$sql);
                break;
            case 'region':
                $sql = str_replace('#','Base_Region',$sql);
                break;
            case 'interest':
                $sql = str_replace('#','Base_Interest',$sql);
                break;
        }

        $query = $this->db->query($sql);
        $rs = $query->first_row('array');
        $this->toJson($rs);
    }

    public function delfield(){
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $sql = "delete from # where Id=".$id;
        switch ($field){
            case 'role':
                $sql = str_replace('#','Base_PositionRole',$sql);
                break;
            case 'region':
                $sql = str_replace('#','Base_Region',$sql);
                break;
            case 'interest':
                $sql = str_replace('#','Base_Interest',$sql);
                break;
        }
        $this->db = $this->load->database('bird',true);
        $this->db->query($sql);
        $this->toJson(array('code'=>0));
    }

    
}

/* End of file welcome.php */

/* Location: ./application/controllers/register.php */
