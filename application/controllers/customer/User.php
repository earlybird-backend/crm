<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class User extends MY_Controller {

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

        $this->db = $this->load->database('cisco',true);
        $this->load->model('UserModel');
					
    }

    public function checkLogin() {
        if ($this->session->userdata("Role") != 'customer') {

            $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));

            redirect('auth', 'refresh');
            
        }
    }
    

    //用户管理
    public function index() {
        self::userBuyerList();
    }
    public function userBuyerList()
    {
        $this->data['buyer'] = 'active';
        self::userListBase('buyer');
    }
    public function userVendorList()
    {
        $this->data['vendor'] = 'active';
        self::userListBase('vendor');
    }
    private function userListBase($UserRole)
    {
        $sql = "SELECT
                    a.UserId, # 用户ID
                    a.FirstName LastName, # 姓名 
                    a.Position, # 职位
                    a.Telephone, # 手机电话
                    a.Cellphone, # 座机电话
                    a.EmailAddress, # 电子邮件
                    b.`name` as CountryName, # 国家
                    a.CompanyName, # 公司名称
                    a.RegisterDate # 注册时间
                FROM
                    Users_Profile a left join Base_Country b on a.CountryId=b.id
                WHERE UserRole='$UserRole'
              ORDER by RegisterDate DESC";
        $query = $this->db->query($sql);
        $rs = $query->result_array($query);
        $this->data['rs']=$rs;
        $comm = '';
        $userIdStr = '';
        foreach ($rs as $item)
        {
            $userIdStr = $userIdStr.$comm.$item['UserId'];
            $comm = ',';
        }
        if(!empty($userIdStr))
        {
            $sql = "SELECT
                        a.UserId,
                        count(1) AS num
                    FROM
                        Users_Profile a
                    INNER JOIN Customer_User b ON a.EmailAddress = b.UserEmail
                    INNER JOIN Customer_Cashpool c ON b.CompanyId = c.CompanyId
                    WHERE
                        a.UserId in ($userIdStr)
                    GROUP BY
                        UserId
                    ";
            $query = $this->db->query($sql);
            $rsMarketCount = $query->result_array($query);
            $this->data['rsMarketCount']=$rsMarketCount;
        }
        $this->data['title'] = 'User';
        $this->load->view('customer/user_list', $this->data);
    }
    //基本
    private function userDetailBase($id)
    {
        $this->data['id'] = $id;
        $this->data['pre_nav'] = array('title' => 'User Manage', 'uri'=> $this->current_controller);
        $this->data['title'] = 'User Detail';
        $this->load->view('customer/user_list_detail', $this->data);
    }
    //用户信息
    public function userListDetail($id)
    {
        $this->data["pageshow"] = "pageshow";
        $this->data["information"] = "active";
        $sql = "SELECT *
                FROM
                   Users_Profile
                WHERE
                UserId = $id";
        $query = $this->db->query($sql);
        $rs = $query->row_array($query);
        $this->data['info']=$rs;
        $userEmail = $rs["EmailAddress"];
        $sql = "SELECT
                    *
                FROM
                    Customer_User
                WHERE
                    UserEmail='$userEmail'  LIMIT 1";
        $query = $this->db->query($sql);
        $rs = $query->row_array($query);
        $this->data['item']=$rs;
        self::userDetailBase($id);
    }
    //公司列表
    public function userCompanyList($id)
    {
        $this->data["CompanyPageshow"] = "pageshow";
        $this->data["company"] = "active";
        $sql = "select 
                    c.CompanyName, -- 公司名称
                    c.CompanyStatus, -- 公司状态
                    c.CompanyWebsite, -- 公司网站
                    c.CompanyAddress, -- 公司地址
                    b1.`name` as countryName,-- 所属国家
                    d1.`name` as typeName,-- 企业类型
                    c1.`name` as industryName, -- 所属行业
                    c.ContactPerson, -- 对外联系人
                    c.ContactEmail, -- 公司邮箱
                    c.ContactPhone, -- 公司电话
                    c.CompanyInfo, -- 备注
                    c.CreateTime -- 创建时间
                    from Users_Profile a 
                    INNER JOIN Customer_User b
                    ON a.EmailAddress=b.UserEmail
                    INNER JOIN Base_Companys c
                    ON b.CompanyId=c.Id 
                    LEFT JOIN Base_Country b1 on c.CountryId=b1.id
                    LEFT JOIN Base_Industry c1 on c.IndustryId = c1.id
                    LEFT JOIN Base_Type d1 on c.TypeId=d1.Id
                    where a.UserId=$id";
        $query =$this->db->query($sql);
        $rs = $query->result_array($query);
        $this->data["rsCompany"] = $rs;
        self::userDetailBase($id);
    }
    //跟踪日志
    public function userListDetailTrace($id)
    {
        $this->data["tracePageshow"] = "pageshow";
        $this->data["trace"] = "active";
        /*$sEcho = intval($this->input->post('sEcho'));
        $sEcho = $sEcho == 0?1:$sEcho;
        $pagenum = 10;
        $up = ($sEcho-1)*$pagenum;
        $sql = "select * from User_Active_Log WHERE user_id=$id LIMIT $up,$pagenum";
        $db1 = $this->load->database("activity",true);
        $query =$db1->query($sql);
        $rs = $query->result_array($query);
        $this->data["rs"] = $rs;*/
        self::userDetailBase($id);
    }
    public function userListDetailTraceSearch($id)
    {
        $data = array();
        $this->data["tracePageshow"] = "pageshow";
        $this->data["trace"] = "active";
        $user_email = $this->input->post('user_email');
        $apply_api = $this->input->post('apply_api');
        $user_ip = $this->input->post('user_ip');
        $market_id = $this->input->post('market_id');
        $app_key = $this->input->post('app_key');
        $createTime = $this->input->post('createTime');
        $createTime1 = $this->input->post('createTime1');


        $iDisplayStart = intval($this->input->post('iDisplayStart'));//开始记录
        $iDisplayLength = intval($this->input->post('iDisplayLength'));//当前一页现实

        $and = 'and';
        $where = '';
        if($user_email)
        {
            $data["user_email"] = $user_email;
            $where = " $where $and user_email like '%$user_email%' ";
            $and = 'and';
        }
        if($apply_api)
        {
            $data["apply_api"] = $apply_api;
            $where = " $where $and apply_api like '%$apply_api%' ";
            $and = 'and';
        }
        if($user_ip)
        {
            $data["user_ip"] = $user_ip;
            $where = " $where $and user_ip like '%$user_ip%' ";
            $and = 'and';
        }
        if($market_id)
        {
            $data["market_id"] = $market_id;
            $where = " $where $and market_id like '%$market_id%' ";
            $and = 'and';
        }
        if($app_key)
        {
            $data["app_key"] = $app_key;
            $where = " $where $and app_key like '%$app_key%' ";
            $and = 'and';
        }
        if($createTime)
        {
            $data["createTime"] = $createTime;
        }
        if($createTime1)
        {
            $data["createTime1"] = $createTime1;
        }

        $sql = "select * from User_Active_Log WHERE user_id=$id$where LIMIT $iDisplayStart,$iDisplayLength";
        $db1 = $this->load->database("activity",true);
        $query =$db1->query($sql);
        $rs = $query->result_array($query);
        $rsCount = count($rs);
        $data["iTotalRecords"] = $rsCount;
        $sql = "select count(1) as num from User_Active_Log where user_id=$id$where";
        $query =$db1->query($sql);
        $data["iTotalDisplayRecords"] = intval($query->row_array()["num"]);
        $data["aaData"] = $rs;
        //$this->data["rs"] = $rs;
        echo responseTrueStr(
            '',$data
        );
    }
    //改变用户状态
    public function userListForChangeStatusDo($id)
    {
        $value = $this->input->post('value');
        $data = array(
            'UserStatus'=>$value
        );
        $this->db->where('Uid',$id);
        $re = $this->db->update('Customer_User',$data);
        if(!$re)
        {
            echo responseErrStr("更新失败!");
            return;
        }
        echo responseTrueStr("更新成功!");
    }
    //处理咨询
    public function process(){
        $ids = $this->input->post('ids');
        $status = $this->input->post('status');

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
