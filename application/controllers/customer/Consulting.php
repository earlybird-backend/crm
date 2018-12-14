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
        $this->data["notRegister"] = "active";
        self::ConsultingListBase(array(0,1,2));
    }
    private function ConsultingListBase($activateStatus)
    {
        $activateStatusStr = implode(",", $activateStatus);
        $where = count($activateStatusStr)>=0?" and ActivateStatus in ($activateStatusStr)":"";
        $sql = "select ue.Id,ue.ActivateStatus,ue.CreateTime,ue.FirstName,ue.LastName,ue.CompanyName,
                bpr.name as RoleName,ue.ContactEmail,ue.ContactPhone,br.name as RegionName,
                bi.name as InterestName,ue.RequestComment 
                from User_Enquiry as ue 
                inner join Base_PositionRole as bpr on bpr.Id = ue.PositionRoleId 
                inner join Base_Region as br on br.Id = ue.RegionId
                inner join Base_Interest as bi on bi.Id = ue.InterestId  where CheckStatus > 0
                $where
                ";

        $query = $this->db->query($sql);
        $rs = $query->result_array($query);

        $this->data['rs'] = $rs;
        $this->data['title'] = 'Consulting';
        $this->load->view('customer/consulting', $this->data);
    }
    public function notRegister()
    {
        self::index();
    }
    public function alreadyRegister()
    {
        $this->data["alreadyRegister"] = "active";
        self::ConsultingListBase(array(3));
    }
    private function consultingDetailBase($id)
    {
        $this->data["id"] = $id;
        $this->data['pre_nav'] = array('title' => 'Consulting', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'Consulting Detail';
        $this->load->view('customer/consulting_detail', $this->data);
    }
    //详细页基本信息
    public function consultingDetail($id)
    {
        $this->data["pageshow"] = "pageshow";
        $this->data["information"] = "active";
        $where = " and ue.Id=$id";
        $sql = "select ue.Id,ue.ActivateStatus,ue.CreateTime,ue.FirstName,ue.LastName,ue.CompanyName,
                bpr.name as RoleName,ue.ContactEmail,ue.ContactPhone,br.name as RegionName,
                bi.name as InterestName,ue.RequestComment 
                from User_Enquiry as ue 
                inner join Base_PositionRole as bpr on bpr.Id = ue.PositionRoleId 
                inner join Base_Region as br on br.Id = ue.RegionId
                inner join Base_Interest as bi on bi.Id = ue.InterestId  where CheckStatus > 0
                $where
                ";
        $query = $this->db->query($sql);
        $rs = $query->row_array($query);
        $this->data['rs'] = $rs;
        self::consultingDetailBase($id);
    }
    //跟踪日志
    public function consultingDetailNotes($id)
    {
        $this->data["notes"] = "active";
        $this->data["pageshowNotes"] = "pageshow";
        $contactEmail =  $this->db->query("select ContactEmail from User_Enquiry where id=$id")->row_array()["ContactEmail"];
        if(!empty($contactEmail)) {
            $sql = "select * from User_Enquiry_Notes where ContactEmail='$contactEmail' order by id desc";
            $query = $this->db->query($sql);
            $rs = $query->result_array($query);
            $this->data['rs'] = $rs;
        }
        self::consultingDetailBase($id);
    }
    //提交跟踪记录
    public function consultingNotesSubmitDo()
    {
        $id = $this->input->post('id');
        $notesContent = $this->input->post('notesContent');

        if(intval($id)<=0)
        {
            echo responseErrStr("没有正确的ID");
            return;
        }
        $contactEmail =  $this->db->query("select ContactEmail from User_Enquiry where id=$id")->row_array()["ContactEmail"];
        if(!empty($contactEmail) && !empty($notesContent))
        {
            $data = array(
                "ContactEmail"=>$contactEmail,
                "NotesContent"=>$notesContent
            );
            $re = $this->db->insert('User_Enquiry_Notes', $data);
            echo  responseTrueStr("提交成功");
        }
        else
        {
            echo responseErrStr("请填写内容");
        }
    }
    //删除跟踪记录
    public function consultingNotesDeleteDo()
    {
        $id = $this->input->post('id');
        if(intval($id)<=0)
        {
            echo responseErrStr("没有正确的ID");
            return;
        }
        $this->db->where('id', $id);
        $re = $this->db->delete('User_Enquiry_Notes');
        echo  responseTrueStr("删除成功");
    }
    //使用者填写资料历史
    public function consultingApplyHistory($id)
    {
        $this->data["applyHistoryShow"] = "pageshow";
        $this->data["history"] = "active";
        $contactEmail =  $this->db->query("select ContactEmail from User_Enquiry where id=$id")->row_array()["ContactEmail"];
        $sql = "select ue.*,bi.name as InterestName from User_Enquiry ue
                inner join Base_Interest as bi on bi.Id = ue.InterestId
                where ContactEmail='$contactEmail'
                order by id desc";
        $query = $this->db->query($sql);
        $rs = $query->result_array($query);
        $this->data['rs'] = $rs;
        self::consultingDetailBase($id);
    }
    public function consultingselectCheckInfoDo($id)
    {
        $contactEmail =  $this->db->query("select ContactEmail from User_Enquiry where id=$id")->row_array()["ContactEmail"];
        if(!empty($contactEmail))
        {
            $sql = "update User_Enquiry set CheckStatus=
                ( CASE WHEN id = $id THEN 1 
                    ELSE 0 END) where ContactEmail='$contactEmail'";
            $re = $this->db->query($sql);
            echo responseTrueStr("选择成功!");
            return;
        }
        echo responseTrueStr("选择失败!");
    }
    public function consultingSendEmail($id)
    {
        $this->data["sendEmail"] = "active";
        $this->data["pageshowEmail"] = "pageshow";
        self::consultingDetailBase($id);
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
