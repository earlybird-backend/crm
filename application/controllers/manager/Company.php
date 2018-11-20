<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Company extends MY_Controller {

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
        $this->db = $this->load->database('bird',true);
        $sql = "select * from Base_Type";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->data['data'] = $rs;
        $this->data['title'] = 'Company';
        $this->load->view('manager/company', $this->data);
    }

    public function save(){
        $this->db = $this->load->database('bird',true);
        $sql = "select count(id) as rows from Base_Type";
        $query = $this->db->query($sql);
        $rs = $query->first_row('array');
        $name = $this->input->post('name');
        $id = $this->input->post('id');
        if(!isset($id)){
            $data = array(
                'sortname'=>'',
                'name'=>$name,
                'cnname'=>$name,
            );
            $this->db->insert('Base_Type',$data);
            $id = $this->db->insert_id();
        }else{
            $data=array(
                'cnname'=>$name,
                'name'=>$name,
            );
            $this->db->where('Id',$id);
            $this->db->update('Base_Type',$data);
        }

        $data['Id'] = $id;
        $this->toJson(array('code'=>0,'data'=>$data));
    }

    public function getitem($id){
        $this->db = $this->load->database('bird',true);
        $sql = "select * from Base_Type where id ={$id}";
        $query = $this->db->query($sql);
        $rs = $query->first_row('array');
        $this->toJson($rs);
    }
    
}

/* End of file welcome.php */

/* Location: ./application/controllers/register.php */
