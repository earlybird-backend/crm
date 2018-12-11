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


        $this->data['link'] = $this->current_controller;

        $this->load->library('phpmailer');

        $this->db = $this->load->database('cisco',true);

					
    }

    public function checkLogin() {
        if ($this->session->userdata("Role") != 'customer') {

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
    //咨询页
    public function index() {
        $this->data["newRegister"] = "active";
        self::ConsultingList(0);
    }
    private function ConsultingList($activateStatus)
    {
        $where = $activateStatus>=0?" where ActivateStatus=$activateStatus":"";
        $sql = "select ue.Id,ue.ActivateStatus,ue.CreateTime,ue.FirstName,ue.LastName,ue.CompanyName,
                bpr.name as RoleName,ue.ContactEmail,ue.ContactPhone,br.name as RegionName,
                bi.name as InterestName,ue.RequestComment 
                from User_Enquiry as ue 
                inner join Base_PositionRole as bpr on bpr.Id = ue.PositionRoleId 
                inner join Base_Region as br on br.Id = ue.RegionId
                inner join Base_Interest as bi on bi.Id = ue.InterestId  
                $where
                ";

        $query = $this->db->query($sql);
        $rs = $query->result_array($query);

        $this->data['rs'] = $rs;
        $this->data['title'] = 'Consulting';
        $this->load->view('customer/consulting', $this->data);
    }
    public function allConsulting()
    {
        $this->data["allConsulting"] = "active";
        self::ConsultingList(-1);
    }
    public function newRegister()
    {
        self::index();
    }
    public function alreadyCommunicated()
    {
        $this->data["alreadyCommunicated"] = "active";
        self::ConsultingList(1);
    }
    public function alreadyRegister()
    {
        $this->data["alreadyRegister"] = "active";
        self::ConsultingList(2);
    }
    public function alreadyInviteRegister()
    {
        $this->data["alreadyInviteRegister"] = "active";
        self::ConsultingList(3);
    }
    //处理咨询
    public function process(){

        $ids = $this->input->post('ids');
        $status = $this->input->post('status');
        $this->db = $this->load->database('cisco',true);


        $id_ar = explode(',',$ids);
        foreach($id_ar as $id){
            $tmp = array(
                'ActivateStatus'=>$status
            );
            $this->db->where('Id',$id);
            $this->db->update('User_Enquiry',$tmp);
        }
        echo 'ok';
    }
}

/* End of file welcome.php */

/* Location: ./application/controllers/register.php */
