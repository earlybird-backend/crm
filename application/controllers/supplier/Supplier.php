<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Supplier extends MY_Controller {

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

        $this->load->model('CurrencylistModel');
		$this->load->model('ConfigurationModel');


        $this->data['link'] = $this->current_controller;

					
    }

    public function checkLogin() {

        if ($this->session->userdata("Role") != 'supplier') {

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
        $sql = "SELECT 
                p.CashpoolCode as cashpoolcode,				#市场编号Code 
                p.createtime,								#市场创建时间
                c.companyname as buyer,						#市场买家公司名称
                p.CompanyDivision as division,				#市场子公司 DIVISION
                p.currencyname as currency, 				#市场的使用的币别
                p.currencysign as currencysign,				#市场的币别符号 
                IFNULL(x.Cnt, 0) as SupplierCount			#市场供应商数
                FROM   `Customer_Cashpool` p 																	#数据表为记录 买家 市场的设置
                INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId		
                LEFT JOIN (
                    SELECT CashpoolCode, COUNT(Id) Cnt FROM  `Customer_Suppliers` GROUP BY CashpoolCode
                ) x ON x.CashpoolCode = p.CashpoolCode
                WHERE p.MarketStatus >= 0
                ORDER BY p.CreateTime";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->data['markets'] = $rs;
        $this->data['title'] = 'Supplier Manager';
        $this->load->view('supplier/supplier_market_list', $this->data);
    }

    public function supplier_list(){
        $CashpoolCode = $this->input->get('code');
        $key = $this->input->get('key');
        $this->db = $this->load->database('bird',true);
        $suppier_sql = "SELECT 
                        v.Id,								#供应商的 ID值，不显示
                        c.CompanyName, 						#供应商企业名称
                        v.VendorDivision,					#供应商Division名称	等同于 企业的合法 开票名称
                        v.RelevancyEmail					#关联负责人Email
                        FROM `Base_Vendors` v
                        INNER JOIN `Base_Vendors_Items` i ON i.VendorId = v.Id
                        INNER JOIN `Customer_Suppliers` s ON s.Id = i.SupplierId
                        INNER JOIN `Base_Companys` c ON c.Id = v.CompanyId
                        WHERE s.CashpoolCode = '{$CashpoolCode}'";
        if(isset($key)){
            $suppier_sql .= " and (VendorDivision like '%{$key}%' or  v.RelevancyEmail='{$key}')";
        }
        $query = $this->db->query($suppier_sql);
        $rs = $query->result_array();
        $this->data['suppliers'] = $rs;
        $this->data['code'] = $CashpoolCode;
        $this->data['key'] = $key;
        $this->data['title'] = 'Supplier List';
        $this->data['pre_nav'] = array('title' => 'Market List', 'uri'=> $this->current_controller) ;
        $this->load->view('supplier/supplier_list', $this->data);
    }

    public function detail_new(){
        $vendorid = $this->input->get('id');
        $code = $this->input->get('code');
        $this->db = $this->load->database('bird',true);
        $vendor_sql = "SELECT 
                    UserEmail,				# 用户			每个 Vendor 中， email 都是唯一的
                    UserStatus,				# 状态  		用户状态 是以 UserStatus = 1 表示 “正常” 状态, UserStatus = -1 表示 “注销\" 状态  只有”正常“状态 的用户才能查看市场
                    UserContact, 			# 联系人			
                    UserPosition,			# 职位
                    UserPhone				# 联系电话
                    FROM `Base_Vendors_Users` u  
                    WHERE u.VendorId = {$vendorid}";
        $vendor_query = $this->db->query($vendor_sql);
        $vendor_rs = $vendor_query->first_row('array');
        $this->data['vendor'] = $vendor_rs;

        $deal_sql = "select sum(PayAmount) as TotalAmount, sum(PayDiscount) as TotalDiscount,
                      round(AVG(PayDpe),1) as AvgDpe
                     FROM `Customer_PayAwards` a
                     WHERE a.CashpoolCode = '{$code}' AND AwardStatus = 1 AND
                     a.Vendorcode = (
                        SELECT s.Vendorcode
                        FROM Customer_Suppliers s
                        INNER JOIN Base_Vendors_Items i ON i.SupplierId = s.Id AND i.VendorId = {$vendorid}
                        WHERE s.CashpoolCode = '{$code}'
                        )";
        $deal_query = $this->db->query($deal_sql);
        $deal_rs = $deal_query->result_array();
        $tmp = array();
        foreach($deal_rs as $item ){
            $tmp1 =array(
                'TotalAmount'=>isset($item['TotalAmount'])?$item['TotalAmount']:'-',
                'AvgDpe'=>isset($item['AvgDpe'])?$item['AvgDpe']:'-',
                'TotalDiscount'=>isset($item['TotalDiscount'])?$item['TotalDiscount']:'-',
                'AvgDiscount'=>(isset($item['TotalAmount'])&&isset($item['TotalDiscount']))?isset($item['TotalDiscount'])/isset($item['TotalAmount']):'-'
            );
            array_push($tmp,$tmp1);
        }
        $this->data['deal'] = $tmp;

        $market_sql = "select c.CashpoolCode, c.CompanyDivision, c.CurrencyName, c.CurrencySign
			from Base_Vendors v 
            inner join Base_Vendors_Items i on i.VendorId = v.Id
            inner join Customer_Suppliers s ON s.Id = i.SupplierId
			inner join Customer_Cashpool c ON c.CashpoolCode = s.CashpoolCode
			where v.Id = {$vendorid}";
        $market_query = $this->db->query($market_sql);
        $market_rs = $market_query->result_array();
        $this->data['market'] = $market_rs;
        $this->data['pre_nav'] = array('title' => 'Supplier List', 'uri'=> $this->current_controller.'/supplier_list?code='.$code) ;
        $this->data['title'] = 'Market Detail';
        $this->load->view('supplier/supplier_detail_new', $this->data);
    }

    //老的详细信息方法
    private function detail($vendorid){
        $this->db = $this->load->database('bird',true);
        $this->data['vendorid'] = $vendorid;
        $info_sql = "SELECT 
                    UserEmail,				
                    UserStatus,				
                    UserContact, 			
                    UserPosition,			
                    UserPhone				
                    FROM `Base_Vendors_Users` u  
                    WHERE u.VendorId = {$vendorid}";
        $info_query = $this->db->query($info_sql);
        $info_rs = $info_query->result_array();
        $this->data['info'] = $info_rs;
        $this->data['pre_nav'] = array('title' => 'Supplier Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'Supplier Detail';
        $this->load->view('supplier/supplier_detail', $this->data);
    }


    public function markets(){
        $this->db = $this->load->database('bird',true);
        $email = $this->input->post('email');
        $vid = $this->input->post('vid');
        $market_sql = "select 
                        s.CashpoolCode,										
                        c.CompanyDivision,									
                        IFNULL(s.OriginalSupplier,s.Supplier) as Supplier,	
                        s.Vendorcode   										
                        from `Base_Vendors_Items`  i
                        INNER JOIN `Customer_Suppliers` s ON s.Id = i.SupplierId
                        INNER JOIN `Customer_Suppliers_Users` u ON u.SupplierId = i.SupplierId   AND u.UserEmail = '{$email}' 
                        INNER JOIN `Customer_CashPool_Setting` c ON c.CashpoolCode = s.CashpoolCode
			            WHERE i.VendorId = {$vid}";
        $market_query = $this->db->query($market_sql);
        $market_rs = $market_query->result_array();
        $this->toJson($market_rs);
    }


    public function suppliers(){

        $this->data['pre_nav'] = array('title' => 'Supplier Manager', 'uri'=> $this->link) ;
        $this->data['title'] = 'Supplier List';

        $this->load->view('supplier/supplier_list', $this->data);
    }

    public function market(){

        $this->data['pre_nav'] = array('title' => 'Supplier Manager', 'uri'=> $this->link) ;
        $this->data['title'] = 'Market Setting';

        $this->load->view('supplier/market_edit', $this->data);
    }

    public function company(){

        $this->data['pre_nav'] = array('title' => 'Supplier Manager', 'uri'=> $this->link) ;
        $this->data['title'] = 'Company Setting';

        $this->load->view('supplier/company_edit', $this->data);
    }

    public function history(){
        $id = $this->input->get('vid');
        $cashpoolcode = $this->input->get('code');
        $this->data['cashpoolcode'] = $cashpoolcode;
        $cashpoolcode = (!isset($cashpoolcode) || $cashpoolcode=='all')?'':" and CashpoolCode = '".$cashpoolcode."'";
        $this->data['vid'] = $id;
        $this->db = $this->load->database('bird',true);
        $supplier_sql = "select c.CashpoolCode, c.CompanyDivision, c.CurrencyName, c.CurrencySign
                        from Base_Vendors v 
                        inner join Base_Vendors_Items i on i.VendorId = v.Id
                        inner join Customer_Suppliers s ON s.Id = i.SupplierId
                        inner join Customer_Cashpool c ON c.CashpoolCode = s.CashpoolCode
                        where v.Id = {$id}";
        $supplier_query = $this->db->query($supplier_sql);
        $supplier_rs = $supplier_query->result_array();
        $this->data['supplier'] = $supplier_rs;

        $history_sql = "select v.VendorDivision,IFNULL(s.OriginalSupplier,s.Supplier) as Supplier, s.Vendorcode , x.PayDate, x.TotalAmount, x.TotalDiscount, x.AvgDpe
                        from Base_Vendors v 
                        inner join Base_Vendors_Items i on i.VendorId = v.Id
                        inner join Customer_Suppliers s ON s.Id = i.SupplierId
                        inner join (
                             select CashpoolCode,Vendorcode,PayDate,sum(PayAmount) as TotalAmount, sum(PayDiscount) as TotalDiscount, round(AVG(PayDpe),1) as AvgDpe
                             FROM `Customer_PayAwards` a
                             where AwardStatus = 1
                             AND PayDate < DATE_FORMAT(NOW() ,\"%Y-%m-%d\")
                             {$cashpoolcode}
                             group by CashpoolCode,Vendorcode,PayDate
                         ) x ON x.CashpoolCode = s.CashpoolCode  AND x.Vendorcode = s.Vendorcode
                        where v.Id = {$id}";

        $history_query = $this->db->query($history_sql);
        $rs = $history_query->result_array();
        $this->data['histories']=$rs;
        $this->data['pre_nav'] = array('title' => 'Supplier Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'History';

        $this->load->view('supplier/award_history', $this->data);
    }

    
    //查询买家的支付计划信息
    private function get_current_cashpool($CustomerId,$CurrencyId)
    {        
        $sql = "SELECT C.Id,C.CustomerId,C.CurrencyId,C.CurrencySign,C.CurrencyName,C.MiniAPR,C.DesiredAPR,C.CashAmount,C.ReservePercent,C.MarketStatus,P.Id as PayId ,P.PayAmount as SetAmount,P.AvaAmount as PayAmount,P.PayDate,C.ReconciliationDate
                FROM `Customer_CashPool_Setting` C
                LEFT JOIN `Customer_CashPool_PaySchedule` P ON C.Id = P.CashPoolId AND P.PaymentStatus = 1 AND P.PayDate > '".date('Y-m-d',time())."'
                WHERE C.CustomerId = '{$CustomerId}' AND C.CurrencyId = '{$CurrencyId}'                
                ORDER BY P.PayDate LIMIT 1 ;";
            
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
        
        return $data[0];       
    }
    
    //查询买家的支付计划信息
    private function get_current_cashpool_byid($cashpoolId)
    {
        $sql = "SELECT C.Id,C.CustomerId,C.CurrencyId,C.CurrencySign,C.CurrencyName,C.MiniAPR,C.DesiredAPR,C.CashAmount,C.ReservePercent,C.MarketStatus,P.Id as PayId ,P.PayAmount as SetAmount,P.AvaAmount as PayAmount,P.PayDate
                FROM `Customer_CashPool_Setting` C
                LEFT JOIN `Customer_CashPool_PaySchedule` P ON C.Id = P.CashPoolId AND P.PayDate > '".date('Y-m-d',time())."'
                WHERE C.Id= '{$cashpoolId}' 
                ORDER BY P.PayDate LIMIT 1 ;";

        $handler = $this->db->query($sql);
        $data = $handler->result_array();
    
        return $data[0];
    }
    
    //查询供应商的开价信息
    private function get_supplier_bid($CashPoolId,$Vendorcode)
    {
        $sql = "SELECT c.supplier,b.*
            FROM `Supplier_Bids` b    
            INNER JOIN `Customer_Suppliers` c ON c.CustomerId = b.CustomerId AND c.Vendorcode = '{$Vendorcode}'     
            WHERE b.CashPoolId = '{$CashPoolId}' AND b.Vendorcode = '{$Vendorcode}'
            AND b.BidStatus = 1
            ORDER BY b.CreateTime DESC LIMIT 1 ;";
        
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
                
        return $data[0];
    }
    
     //查询供应商的应付发票
    private function get_supplier_payment($CustomerId,$CurrencyId,$Vendorcode)
    {
        $sql = "SELECT *
            FROM `Customer_Payments` 
            WHERE CustomerId = '{$CustomerId}' AND CurrencyId = '{$CurrencyId}'  AND Vendorcode = '{$Vendorcode}' 
            AND InvoiceStatus  = 1                 
            ORDER BY CreateTime DESC;";
        
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
                
        return $data;
    }
    
    //查询供应商的应付发票
    private function get_supplier_payment_bywhere($where)
    {
        $sql = "SELECT *
        FROM `Customer_Payments`
        WHERE Id in ({$where})        
        ORDER BY CreateTime DESC;";

        $handler = $this->db->query($sql);
        $data = $handler->result_array();
    
        return $data;
    }



}

/* End of file welcome.php */

/* Location: ./application/controllers/register.php */
