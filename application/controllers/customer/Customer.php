<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Customer extends MY_Controller {

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

        if ($this->session->userdata("Role") != 'customer') {

            $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));

            redirect('auth', 'refresh');
            
        }
    }

    public function index() {

        $this->db = $this->load->database('bird',true);

        $sql = "select c.CompanyName,a.CompanyDivision,a.CurrencyName,  
                	IFNULL(u.UserName,'') as UserName, 
					IFNULL(u.UserEmail,'') as UserEmail ,
					a.CompanyId,a.Id as marketId,a.CashpoolCode                      
                  from Customer_Cashpool a
                  inner join Base_Companys c ON c.Id = a.CompanyId
                  left join Customer_User u  ON u.Uid = a.UserId AND u.UserStatus = 1
                  where a.MarketStatus >= 0;
                  ";

        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->data['rs'] = $rs;

        $this->data['title'] = 'Customer Manager';

        $this->load->view('customer/customer_list', $this->data);

    }

    public function suppliers(){
        $this->db = $this->load->database('cisco',true);
        $id = $this->input->get('marketid');
        $code = $this->input->get('code');
        //数据板信息
        $panel_sql = "SELECT                 
					COUNT(s.Id) as TotalVendor,														#共有供应商
					SUM( CASE WHEN i.Id IS NOT NULL THEN 1 ELSE 0 END) as TotalRegistered,			#已注册供应商
					SUM( CASE WHEN q.Vendorcode IS NOT NULL THEN 1 ELSE 0 END) as TotalBid,		#参与开价供应商
					SUM( CASE WHEN a.Vendorcode IS NOT NULL THEN 1 ELSE 0 END) as TotalAward		#清算供应商
					FROM `Customer_Suppliers` s                
					LEFT JOIN `Base_Vendors_Items` i ON i.SupplierId = s.Id
					LEFT JOIN (
						SELECT DISTINCT Vendorcode FROM `Supplier_Bid_Queue` WHERE CashpoolId = {$id}
					) q ON q.Vendorcode = s.Vendorcode
					LEFT JOIN (
						SELECT DISTINCT Vendorcode  FROM  `Customer_Payments`  WHERE InvoiceStatus = 2 AND CashpoolCode = '{$code}'
					) a ON a.Vendorcode = s.Vendorcode							
					WHERE s.CashpoolCode = '{$code}'";


        $panel_query = $this->db->query($panel_sql);
        $panel_rs = $panel_query->first_row('array');
        $this->data['panel'] =$panel_rs;

        //供应商信息
        $vendor_sql = "SELECT s.Supplier, 												#供应商名
					s.Vendorcode, 														#Vendor Code
					u.UserPhone, 														#联系电话
					u.UserEmail, 														#邮箱
					CASE WHEN i.Id IS NOT NULL THEN 1 ELSE 0 END IsRegistered,  	#是否已注册
					IFNULL(q.LastBidDate,'-') as LastBidDate,							#最后开价日期
					IFNULL(a.LastAwardDate,'-') as LastAwardDate						#最后清算日期
					FROM `Customer_Suppliers` s
					LEFT JOIN `Customer_Suppliers_Users` u ON u.SupplierId = s.Id AND s.RelevancyEmail = u.UserEmail AND u.UserStatus = 1
					LEFT JOIN `Base_Vendors_Items` i ON i.SupplierId = s.Id
					LEFT JOIN (
						SELECT Vendorcode, DATE_FORMAT(MAX(CreateTime) ,\"%Y-%m-%d\")   as LastBidDate FROM `Supplier_Bid_Queue` 
						WHERE CashpoolId = {$id}  GROUP BY Vendorcode
						) q ON q.Vendorcode = s.Vendorcode
					LEFT JOIN (
						SELECT Vendorcode, MAX(AwardDate) as LastAwardDate 
						FROM  `Customer_Payments` 
						WHERE InvoiceStatus = 2 AND CashpoolCode = '{$code}'
						GROUP BY Vendorcode
					) a ON a.Vendorcode = s.Vendorcode							
					WHERE s.CashpoolCode = '{$code}'";
        $vendor_query = $this->db->query($vendor_sql);
        $vendor_rs = $vendor_query->result_array();
        $this->data['vendors'] = $vendor_rs;

        $this->data['pre_nav'] = array('title' => 'Buyer Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'Supplier List';

        $this->load->view('customer/supplier_list', $this->data);
    }

    public function market(){

        $this->db = $this->load->database('cisco',true);
        //行业信息
        $this->data['industry'] = $this->getIndustry();
        //国家信息
        $this->data['Region'] = $this->getCountry();
        //公司类型信息
        $this->data['type'] = $this->getType();
        //额外信息范围
        $this->data['ext'] = $this->getExt();
        //币种信息
        $this->data['cur'] = $this->getCurrency();

        $this->data['pre_nav'] = array('title' => 'Buyer Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'Add Buyer';

        $this->load->view('customer/market_add', $this->data);
    }

    public function savecompany(){

        $company_info = $this->input->post('company');
        $company = json_decode($company_info);


        $this->db = $this->load->database('cisco',true);
        if(!isset($company->id)){
            $data = $this->addCompany($company);
        }else{
            $data = $this->updateCompany($company);
        }
        $this->toJson($data);
    }

    private function addCompany($company){
        $create_user = $this->getCreateUser();
        $id = $this->buildUUID();
        $insert_sql = "INSERT INTO `Base_Companys` 
                      (`Id`, `CreateUser`, `CompanyName`, `CompanyEnName`, `CompanyWebsite`, `CompanyAddress`, `IndustryId`, `CountryId`, `TypeId`, `FiscalMonth`, `ContactPerson`, `ContactEmail`, `ContactPhone`, `CompanyInfo`) 
                      VALUES ({$id}, '".$create_user
            ."', '".$company->name
            ."', '".$company->name
            ."', '".$company->site
            ."', '".$company->address
            ."', '".$company->trade
            ."', '".$company->country
            ."', '".$company->type
            ."', '".$company->endtime
            ."', '暂无', '暂无', ".$company->phone
            .", '暂无')";
        $this->db->query($insert_sql);

        $contact1 = $company->contact->box1;
        $company->contact->box1 = $this->saveCustomerUser($contact1,$id,'Enterprise');

        $contact2 = $company->contact->box2;
        $company->contact->box2 = $this->saveCustomerUser($contact2,$id,'Financial');

        $contact3 = $company->contact->box3;
        $company->contact->box3 = $this->saveCustomerUser($contact3,$id,'Charge');


        $ext_id = $this->buildUUID();
        $ext_sql = "insert into Customer_Extra (Id,CreateUser,CompanyId,SaleVolumeId,SaleVolume,PurhchaseVolumeId,PurhchaseVolume,CashflowVolumeId,CashflowVolume) 
                    values ({$ext_id},'{$create_user}',{$id},{$company->scope1},'{$company->other1}',{$company->scope2},'{$company->other2}',{$company->scope3},'{$company->other3}')";
        $this->db->query($ext_sql);

        return array("id"=>$id,'ext_id'=>$ext_id,'contact'=>$company->contact);
    }

    private function buildUUID(){
        $sql = "SELECT UUID_SHORT() as id";
        $id_query = $this->db->query($sql);
        $id_rs = $id_query->first_row('array');
        $id = $id_rs['id'];
        return $id;
    }

    private function updateCompany($company){
        $create_user = $this->getCreateUser();
        $company_data = array(
            'CreateUser'=>$create_user,
            'CompanyName'=>$company->name,
            'CompanyEnName'=>$company->name,
            'CompanyWebsite'=>$company->site,
            'CompanyAddress'=>$company->address,
            'IndustryId'=>$company->trade,
            'CountryId'=>$company->country,
            'TypeId'=>$company->type,
            'FiscalMonth'=>$company->endtime,
            'ContactPerson'=>'暂无',
            'ContactEmail'=>'暂无',
            'ContactPhone'=>$company->phone,
            'CompanyInfo'=>'暂无'
        );
        $this->db->where('Id', $company->id);
        $this->db->update('Base_Companys', $company_data);

        //更新联系信息
        $contact1 = $company->contact->box1;
        $company->contact->box1 = $this->updateCustomerUser($contact1,$company->id,'Enterprise');

        $contact2 = $company->contact->box2;
        $company->contact->box2 = $this->updateCustomerUser($contact2,$company->id,'Financial');

        $contact3 = $company->contact->box3;
        $company->contact->box3 = $this->updateCustomerUser($contact3,$company->id,'Charge');

        //更新额外信息
        $ext_data = array(
            'CreateUser'=>$create_user,
            'SaleVolumeId'=>$company->scope1,
            'SaleVolume'=>$company->other1,
            'PurhchaseVolumeId'=>$company->scope2,
            'PurhchaseVolume'=>$company->other2,
            'CashflowVolumeId'=>$company->scope3,
            'CashflowVolume'=>$company->other3
        );
        $this->db->where('Id', $company->ext_id);
        $this->db->update('Customer_Extra', $ext_data);
        return array('id'=>$company->id,'ext_id'=>$company->ext_id,'contact'=>$company->contact);
    }

    //获取行业信息
    private function getIndustry(){
        $industry_sql = "select id,name from Base_Industry";
        $idt_query = $this->db->query($industry_sql);
        $idt_rs = $idt_query->result_array();
        return $idt_rs;
    }
    //获取国家信息
    private function getCountry(){
        $country_sql = "select id,name from Base_Country";
        $country_query = $this->db->query($country_sql);
        $country_rs = $country_query->result_array();
        return $country_rs;
    }
    //获取公司类型信息
    private function getType(){
        $type_sql = "select Id,name from Base_Type";
        $type_query = $this->db->query($type_sql);
        $type_rs = $type_query->result_array();
        return $type_rs;
    }
    //获取公司额外信息的范围
    private function getExt(){
        $ext_sql = "select Id,name from Base_Volume where volumetype='cash'";
        $ext_query = $this->db->query($ext_sql);
        $ext_rs = $ext_query->result_array();
        return $ext_rs;
    }
    //获取货币信息
    private function getCurrency(){
        $currency_sql = "select CurrencySign as sign,CurrencyName as name from Base_Currency";
        $currency_query = $this->db->query($currency_sql);
        $currency_rs = $currency_query->result_array();
        return $currency_rs;
    }

    private function saveCustomerUser($contact,$id,$role){
        $create_user = $this->getCreateUser();
        $return_array = array();
        foreach($contact as $item){
            $uid = $this->buildUUID();
            $username = $item->firstname.' '.$item->lastname;
            $user_sql = "insert into Customer_User (Uid,CreateUser,CompanyId,UserName,FirstName,LastName,UserRole,UserEmail,UserContact,UserPosition) 
        values( {$uid},'{$create_user}',{$id},'{$username}','{$item->firstname}','{$item->lastname}'
        ,'{$role}','{$item->email}','{$item->phone}','{$item->title}');";
            $item->dbid = $uid;
            array_push($return_array,$item);
            $this->db->query($user_sql);
        }
        return $return_array;
    }

    private function updateCustomerUser($contact,$companyid,$role){
        $return_array = array();
            foreach($contact as $item){
                $username = $item->firstname.' '.$item->lastname;
                if(property_exists($item,'dbid')){ //有数据库ID，进行更新
                    $data = array(
                        "UserStatus"=>$item->status,
                        "UserName"=>$username,
                        "FirstName"=>$item->firstname,
                        "LastName"=>$item->lastname,
                        "UserEmail"=>$item->email,
                        "UserContact"=>$item->phone,
                        "UserPosition"=>$item->title,
                    );
                    $this->db->where('Uid',$item->dbid);
                    $this->db->update('Customer_User',$data);
                    if($item->status!=-1) { //不是前端删除的信息
                        array_push($return_array,$item);
                    }
                }else{//没有的，新增
                    $create_user = $this->getCreateUser();
                    $user_sql = "";
                    $uid = $this->buildUUID();

                    $user_sql .= "insert into Customer_User (Uid,CreateUser,CompanyId,UserName,FirstName,LastName,UserRole,UserEmail,UserContact,UserPosition) 
                    values( {$uid},'{$create_user}',{$companyid},'{$username}','{$item->firstname}','{$item->lastname}'
                    ,'{$role}','{$item->email}','{$item->phone}','{$item->title}');";
                    $this->db->query($user_sql);
                    $item->dbid = $uid;
                    array_push($return_array,$item);
                }


            }

        return $return_array;
    }

    //获取市场的清算负责人
    public function getCharge($companyid){
        $this->db = $this->load->database('cisco',true);
        $sql = "select Uid,UserName from Customer_User where UserRole='Charge' and UserStatus =1 and CompanyId={$companyid}";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->toJson($rs);
    }
    //保存或更新市场信息
    public function savemarket(){
        $create_user = $this->getCreateUser();
        $market_json = $this->input->post('market');
        $data = json_decode($market_json);
        $h5id= $data->h5id;
        unset($data->h5id);
        $this->db = $this->load->database('cisco',true);
        $data->CreateUser = $create_user;
        if(property_exists($data,'Id')){
            $id = $data->Id;
            unset($data->Id);
            $data->LastUpdateTime = date('Y-m-d H:i:s',time());
            $data->LastUpdateUser = $create_user;
            $this->db->where('Id',$id);
            $this->db->update('Customer_Cashpool',$data);
            $data->Id = $id;
        }else{
            $data->Id = $this->buildUUID();
            $count_sql = "select count(id) as length from Customer_Cashpool";
            $count_query = $this->db->query($count_sql);
            $count_rs = $count_query->first_row('array');
            $count = $count_rs['length'];
            $code = 'xxxx-'.($count+1).'-xxx';
            $data->CashpoolCode = $code;
            $data->LastUpdateTime = date('Y-m-d H:i:s',time());
            $data->LastUpdateUser = $create_user;
            $this->db->insert('Customer_Cashpool',$data);
        }
        $data->h5id = $h5id;
        $this->toJson($data);
    }

    public function closemarket($id){
        $create_user = $this->getCreateUser();
        $data=array(
            'MarketStatus'=>1,
            'LastUpdateTime' => date('Y-m-d H:i:s',time()),
            'LastUpdateUser' => $create_user
        );
        $this->db = $this->load->database('cisco',true);
        $this->db->where('Id',$id);
        $this->db->update('Customer_Cashpool',$data);
        $this->toJson($data);
    }

    public function saveother(){
        $other_info = $this->input->post('other_info');
        $other_info_json = json_decode($other_info);
        $f = $_FILES['logo']; //把文件信息赋给一个变量，方便调用
        $f_name = $f['name'];
        $ext_arry = explode('.',$f_name);
        $s="";
        if(count($ext_arry)>1){
            $s = '.'.$ext_arry[1];
        }
        $t = time(); // 时间戳
        move_uploaded_file($f['tmp_name'], './uploads/'.$t.$s);
        $other_info_json->logo = './uploads/'.$t.$s;
        $this->toJson($other_info_json);
    }

    private function getCreateUser(){
        return $this->session->userdata['Role'];
    }

    public function editmarket($companyid){

        $this->db = $this->load->database('cisco',true);

        //行业信息
        $this->data['industry'] = $this->getIndustry();
        //国家信息
        $this->data['country'] = $this->getCountry();
        //公司类型信息
        $this->data['type'] = $this->getType();
        //额外信息范围
        $this->data['ext'] = $this->getExt();
        //币种信息
        $this->data['cur'] = $this->getCurrency();

        //获取公司信息
        $company_sql = 'select * from Base_Companys where Id='.$companyid;
        $company_query = $this->db->query($company_sql);
        $company_rs = $company_query->first_row('array');
        $this->data['company'] = $company_rs;

        //获取公司额外信息
        $ext_sql = "select * from Customer_Extra where CompanyId=".$companyid;
        $ext_query = $this->db->query($ext_sql);
        $ext_rs = $ext_query->first_row('array');
        $this->data['ext_value'] = $ext_rs;

        //获取公司联系信息
        $contact_sql = "select * from Customer_User where CompanyId = ".$companyid;
        $contact_query = $this->db->query($contact_sql);
        $contact_rs = $contact_query->result_array();
        $contact= array(
            'box1'=>array(),
            'box2'=>array(),
            'box3'=>array(),
        );
        foreach($contact_rs as $item){
            switch ($item['UserRole']){
                case 'Enterprise':
                    array_push($contact['box1'],$item);
                    break;
                case 'Financial':
                    array_push($contact['box2'],$item);
                    break;
                case 'Charge':
                    array_push($contact['box3'],$item);
                    break;
            }
        }
        $this->data['contact'] = $contact;

        //获取市场信息
        $market_sql = "select a.*,u.Uid,u.UserName from Customer_Cashpool as a,Customer_User as u where a.UserId=u.Uid and a.CompanyId=".$companyid;
        $market_query = $this->db->query($market_sql);
        $market_rs = $market_query->result_array();
        $this->data['market'] = $market_rs;

        //获取清算负责人
        $sql = "select Uid,UserName from Customer_User where UserRole='Charge' and UserStatus =1 and CompanyId={$companyid}";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->data['charge'] = $rs;

        $this->data['pre_nav'] = array('title' => 'Buyer Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'Edit Buyer';

        $this->load->view('customer/market_edit', $this->data);
    }


    public function company(){

        $this->data['pre_nav'] = array('title' => 'Customer Manager', 'uri'=> $this->link) ;
        $this->data['title'] = 'Company Setting';

        $this->load->view('customer/company_edit', $this->data);
    }

    public function history(){


        $cashpoolCode = $this->input->get('cashpoolCode');

        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $isManual = $this->input->get('type');
        $clearday = $this->input->get('clearday');


        if( !isset($start) || $start!="")
        {
            $start =  date('Y-m-d',strtotime('-7 days'));
        }

        if( !isset($end) || $end!=""  )
        {
            $end = date('Y-m-d',time());
        }

        if( isset($isManual) && !empty($isManual) && $isManual == 0)
        {
            $isManual = 1;
        }else{
            $isManual = 0;
        }

        $this->load->model('HistoryModel');


        $result = $this->HistoryModel->getAwardInvoiceList($cashpoolCode, $start, $end, $isManual);

        $amount = 0 ;
        $discount = 0;
        $supplier = array();

        $avg_apr = 0 ;			//平均年化收益率%
        $avg_discount = 0 ;
        $avg_dpe = 0 ;

        $amount_a = 0 ;         //平台清算
        $discount_a = 0;
        $avg_apr_a = 0 ;		//平台计算 平均年化收益率%
        $avg_discount_a = 0 ;	//平台计算 平均折扣率%

        $amount_m = 0 ;         //手工清算
        $discount_m = 0;
        $avg_apr_m = 0 ;		//手工 平均年化收益率%
        $avg_discount_m = 0 ;	//手工 平均折扣率%

        $max_dpe = 0;
        $min_dpe = 0;

        foreach($result as $val){

            if( !array_key_exists($val['Vendorcode'], $supplier)){
                $supplier[$val['Vendorcode']] = "";
            }

               if ( $val['PayDpe'] >  $max_dpe)
               {
                   $max_dpe = $val['PayDpe'];
               }

               if( $val['PayDpe'] < $min_dpe || $min_dpe = 0 )
               {
                   $min_dpe = $val['PayDpe'];
               }

                $amount += $val['PayAmount'];
                $discount += $val['PayDiscount'];

                $avg_dpe += $val['PayDpe'];

            if( $val['IsManual'] == 1){
                $amount_m  += $val['PayAmount'];
                $discount_m += $val['PayDiscount'];

            }else{
                $amount_a  += $val['PayAmount'];
                $discount_a += $val['PayDiscount'];

            }

        }

        foreach ( $result as $val){
            $avg_discount += round( $val["PayDiscount"]/ $amount , 4);
            $avg_apr += round($val['PayDiscount']/$val['PayDpe']*365*100/$amount, 2);

            if( $val['IsManual'] == 1){
                $avg_discount_m  += round($val['PayAmount']/ $amount_m , 4);
                $avg_apr_m += round($val['PayDiscount']/$val['PayDpe']*365*100/$amount_m, 2);
            }else{
                $avg_discount_a  += round($val['PayAmount']/ $amount_a , 4);
                $avg_apr_a += round($val['PayDiscount']/$val['PayDpe']*365*100/$amount_a, 2);
            }
        }

        $stat['TotalAmount'] =  $amount;
        $stat['TotalAmount_A'] =  $amount_a;
        $stat['TotalAmount_M'] =  $amount_m;

        $stat['TotalDiscount_M']= $discount_m ;
        $stat['TotalDiscount_A']= $discount_a ;

        $stat['InvoiceCount'] = count( $result);
        $stat['VendorCount'] = count($supplier);

        $stat['AvgPayDPE']  = count($result) > 0 ? round($avg_dpe/count($result),1) : 0 ;
        $stat['AvgApr'] = $avg_apr;
        $stat['AvgDiscount'] = $avg_discount;

        $stat['AvgApr_A'] = $avg_apr_a;
        $stat['AvgDiscount_A'] = $avg_discount_a;


        $stat['AvgApr_M'] = $avg_apr_m;
        $stat['AvgDiscount_M'] = $avg_discount_m;


        $stat['MinPayDPE'] = $min_dpe;
        $stat['MaxPayDPE'] = $max_dpe;
        $stat['FirstAwardDate'] =  $result[0]["AwardDate"];
        $stat['LastAwardDate'] =  $result[count($result)-1]["AwardDate"];

        $this->data['panel'] = $stat;

        $this->data['history'] = $this->HistoryModel->getDailyAwardList($cashpoolCode, $start, $end,  $isManual);

        $this->data['cashpoolCode'] = $cashpoolCode;
        $this->data['cleartype'] = isset($clear_type)?$clear_type:-1;
        $this->data['clearday'] = isset($clearday)?$clearday:7;
        $this->data['start'] = isset($start)?$start:"";
        $this->data['end'] = isset($end)?$end:"";
        $this->data['pre_nav'] = array('title' => 'Buyer Manager', 'uri'=> $this->current_controller) ;
        $this->data['title'] = 'History';

        $this->load->view('customer/award_history', $this->data);
    }

    public function invoice($awarddate,$marketid){
        $this->db = $this->load->database('cisco',true);
        $sql = "SELECT 
					IFNULL(s.OriginalSupplier,s.Supplier) as supplier, 		# 供应商
					s.vendorcode,											# 供应商 Code
					p.InvoiceNo,											# 发票编号
					p.InvoiceDate,											# 发票开票日期
					p.EstPaydate,											# 原支付日期
					p.InvoiceAmount 										# 发票金额
					FROM `Customer_PayAwards` a
					INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId and p.InvoiceStatus = 2					# 发票只有 InvoiceStatus = 2 才为真正的成交结果
					LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = p.Vendorcode
					WHERE a.CashpoolId = {$marketid} AND a.AwardDate = '{$awarddate}'";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $this->toJson($rs);
    }

    public function download_invoice($awarddate,$marketid){
        $this->load->helper('download');
        $this->db = $this->load->database('cisco',true);
        $sql = "SELECT 
					IFNULL(s.OriginalSupplier,s.Supplier) as supplier, 		# 供应商
					s.vendorcode,											# 供应商 Code
					p.InvoiceNo,											# 发票编号
					p.InvoiceDate,											# 发票开票日期
					p.EstPaydate,											# 原支付日期
					p.InvoiceAmount 										# 发票金额
					FROM `Customer_PayAwards` a
					INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId and p.InvoiceStatus = 2					# 发票只有 InvoiceStatus = 2 才为真正的成交结果
					LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = p.Vendorcode
					WHERE a.CashpoolId = {$marketid} AND a.AwardDate = '{$awarddate}'";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        $data = "supplier,vendorcode,InvoiceNo,InvoiceDate,EstPaydate,InvoiceAmount\r\n";
        $filename='';
        foreach($rs as $item){
            $filename = $item['supplier'];
            $data.=$item['supplier'].','
                .$item['vendorcode'].','
                .$item['InvoiceNo'].','
                .$item['InvoiceDate'].','
                .$item['EstPaydate'].','
                .$item['InvoiceAmount']."\r\n";
        }
        $name = $filename.'.csv';
        force_download($name, $data);
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
