<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Invoice extends MY_Controller {

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

    public function __construct() {

        parent::__construct();

        $this->checkLogin();

        $this->data['link'] = $this->current_controller;
        $this->load->model('InvoiceModel');
        $this->db = $this->load->database('cisco',true);
					
    }

    public function checkLogin() {

        if ($this->session->userdata("Role") != 'customer') {

            $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));

            redirect('auth', 'refresh');
            
        }
    }



    private function saveSample($cashpoolCode, $list){

        $data = array();
        $values = "";

        foreach($list as $i=>$v){

            if ( $i === 0)
                $values .= "(UUID_SHORT(),'admin','{$cashpoolCode}','{$v['vendorcode']}','{$v['invoicedate']}',1,1,'{$v['invoiceno']}','{$v['amount']}','{$v['estpaydate']}')";
            else
                $values .= ",(UUID_SHORT(),'admin','{$cashpoolCode}','{$v['vendorcode']}','{$v['invoicedate']}',1,1,'{$v['invoiceno']}','{$v['amount']}','{$v['estpaydate']}')";
        }

        //采用 Codeigniter 事务的手动模式
        $this->db->trans_strict(FALSE);
        $this->db->trans_begin();

        try{

            //将原来的发票赋值为无效
            $this->db->query( "UPDATE Customer_Payments SET InvoiceStatus = -2 WHERE CashpoolCode='{$cashpoolCode}' AND InvoiceStatus IN (0,1) ;");

            $sql =  "INSERT INTO Customer_Payments
                (Id,CreateUser,CashpoolCode,Vendorcode,InvoiceDate,InvoiceStatus,IsIncluded,InvoiceNo,InvoiceAmount,EstPaydate)
                VALUES
                {$values};";

            $this->db->query($sql);

            $this->db->trans_commit();
            return true;

        }catch(Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->trans_rollback();
            return false;
        }finally{
            $this->db->trans_strict(TRUE);
        }

    }

    //当日结果发票明细
    public function invoice_award($cashpoolId,$awarddate=null){

        $awarddate_sql = ($awarddate==null || $awarddate=="")? ' 1=1 ':"  a.AwardDate = '{$awarddate}'";

        $invoice_sql = "SELECT 
				IFNULL(s.OriginalSupplier,s.Supplier) as supplier, 		# 供应商
				s.vendorcode,											# 供应商 Code
				p.InvoiceNo,											# 发票编号
				p.InvoiceDate,											# 发票开票日期
				p.EstPaydate,											# 原支付日期
				p.InvoiceAmount										# 发票金额
				FROM `Customer_PayAwards` a
				INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId and p.InvoiceStatus = 2					# 发票只有 InvoiceStatus = 2 才为真正的成交结果
				LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = p.Vendorcode
				WHERE a.CashpoolId = {$cashpoolId} AND {$awarddate_sql}";


        $query = $this->db->query($invoice_sql);

        $rs = $query->result_array();

        $this->toJson($rs);

    }


    public function index(){

        $next_pay = $this->input->get('nextpay');
        $cashpoolCode = $this->input->get('code');
        $status = $this->input->get('status');
        $status = isset($status) ? $status : 'All';

        switch ($status){
            case 'Eligible':
                $status_sql = " and a.InvoiceStatus=1";
                break;
            case 'Ineligible':
                $status_sql = " and a.InvoiceStatus=-1";
                break;
            case 'Awarded':
                $status_sql = " and a.InvoiceStatus=2";
                break;
            case 'Adjustment':
                $status_sql = " and a.InvoiceStatus<>1 and a.InvoiceStatus<>-1 and a.InvoiceStatus<>2";
                break;
        }


        $this->data['status'] = $status;

        $this->data['start'] = '';
        $this->data['end'] = '';


        $early = $this->input->get('early');
        $early = isset($early)?$early:'1';
        $early_sql = "";
        $end = date('Y-m-d',time());
        $start = date('Y-m-d',time());

        switch($early){
            case '2'://15至30
                $start = date('Y-m-d',strtotime('15 days',strtotime($next_pay)));
                $end = date('Y-m-d',strtotime('30 days',strtotime($next_pay)));
                break;
            case '3'://30至45
                $start = date('Y-m-d',strtotime('30 days',strtotime($next_pay)));
                $end = date('Y-m-d',strtotime('45 days',strtotime($next_pay)));
                break;
            case '4'://大于45
                $start = date('Y-m-d',strtotime('45 days',strtotime($next_pay)));
                $end = date('Y-m-d',strtotime('3650 days',strtotime($next_pay)));
                break;
            case '5':
                $start = $this->input->get('start');
                $end = $this->input->get('end');
                $this->data['start'] = $start;
                $this->data['end'] = $end;
                break;
            default://默认 小于15天
                $start = date('Y-m-d',strtotime($next_pay));
                $end = date('Y-m-d',strtotime('15 days',strtotime($next_pay)));
        }



        $this->data['early'] = $early;

        $amount = $this->input->get('amount');

        $amount = isset($amount)?$amount:'1';
        $amount_sql = "";
        $min = 0;
        $max = 0;

        switch($amount){
            case '2'://25000 - 50000
                $min = 25000;
                $max = 50000;
                break;
            case '3'://50000 - 75000
                $min = 50000;
                $max = 75000;
                break;
            case '4'://>75000
                $min = 75000;
                $max = 999999999;
                break;
            default :// 0- 25000
                $min = 0;
                $max = 25000;
        }
        $amount_sql = " and InvoiceAmount between {$min} and {$max}";
        $this->data['amount'] = $amount;


        $invoices = $this->Invoicemodel->get_market_invoices( $cashpoolCode );

        $result = array();

        if ( isset($invoices) && count($cashpoolCode) > 0 ){
            $this->load->model('SupplierModel');

            $suppliers = $this->Suppliermodel->getSuppliers($cashpoolCode);


            foreach( $invoices as $inv){


                if( $status != "all" && $inv["status"] != $status)
                    continue;

                if( $inv["amount"] <$min || $inv["amount"] > $max)
                    continue;

                if( $inv["paydate"] < $start || $inv["paydate"] > $end)
                    continue;

                $result[] = array(
                    "Vendorcode" => $inv["vendorcode"],
                    "Supplier" => $suppliers[ $inv["vendorcode"] ]["supplier_name"] ,
                    "InvoiceNo" => $inv["invoiceno"],
                    "InvoiceAmount" => $inv["amount"],
                    "EstPaydate" => $inv["paydate"],
                    "Status" => $inv["status"]
                );
            }
        }

        $this->data['rs'] = $result;
        $this->data['invStatus'] = $this->Invoicemodel->getInvoiceStatus();
        $this->data['code'] = $cashpoolCode;
        $this->data['nextpay'] = $next_pay;
        $this->data['title'] = 'Invoice detail';
        $this->data['pre_nav'] = array('title' => 'Market Manager', 'uri'=> $this->current_controller) ;

        $this->load->view('customer/invoice_detail', $this->data);
    }

    public function sync(){
        $code = $this->input->post('code');
        $f = $_FILES['xls']; //把文件信息赋给一个变量，方便调用
        $f_name = $f['name'];
        if(strpos($f_name,'xls')===false && strpos($f_name,'xlsx')===false){
            $this->toJson(array(
                'code'=>-1,
                'msg'=>'文件类型错误,请上传Excel文件.'
            ));
            return;
        }

        $ext_arry = explode('.',$f_name);
        $s="";
        if(count($ext_arry)>1){
            $s = '.'.$ext_arry[1];
        }
        $t = time(); // 时间戳
        move_uploaded_file($f['tmp_name'], './uploads/'.$t.$s);
        $filename = './uploads/'.$t.$s;
        $data = $this->paseXls($filename,$code);
        $this->savePayments($data);
        $this->saveSuppliers($data);

        $this->toJson(array(
            'code'=>0,
            'filename'=>$filename
        ));
    }

    private function paseXls($file,$code){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        // 创建对象
        $objPHPExcel = new IOFactory();
        $readerType = $objPHPExcel::identify($file);
        $objReader = $objPHPExcel::createReader($readerType);
        // 读文件
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet(0);
        // 总行数
        $highestRow = $objWorksheet->getHighestRow();
        $data = array();
        // 从第二行开始，第一行一般是表头
        for ($row = 2; $row <= $highestRow; $row++) {
            $array = array();
            $array['Supplier'] = $objWorksheet->getCell('B'.$row)->getValue();
            $array['Vendorcode'] = $objWorksheet->getCell('C'.$row)->getValue();
            $array['InvoiceNo'] = $objWorksheet->getCell('D'.$row)->getValue();
            $array['EstPaydate'] = $objWorksheet->getCell('E'.$row)->getValue();
            $array['Amount'] = $objWorksheet->getCell('F'.$row)->getValue();
            $array['Code'] = $code;
            array_push($data, $array);
        }
        return $data;
    }

    private function savePayments($data){
        $this->db = $this->load->database('cisco',true);
        foreach($data as $item){
            $this->db->where('InvoiceNo',$item['InvoiceNo']);
            $this->db->where('CashpoolCode',$item['Code']);
            $this->db->from('Customer_Payments');
            $count = $this->db->count_all_results();
            if($count>0) {
                continue;
            }else{
                $uuid = $this->buildUUID();
                $array = array(
                    'CreateTime'=>date('Y-m-d H:i:s',time()),
                    'CreateUser'=>'admin',
                    'CurrencyId'=>1,
                    'CashpoolCode'=>$item['Code'],
                    'Vendorcode'=>$item['Vendorcode'],
                    'InvoiceNo'=>$item['InvoiceNo'],
                    'InvoiceAmount'=>$item['Amount'],
                    'InvoiceDate'=>date('Y-m-d H:i:s',time()),
                    'EstPaydate'=>$item['EstPaydate'],
                    'InvoiceStatus'=>1,
                    'IsIncluded'=>1,
                    'Id'=>$uuid
                );
                $this->db->insert('Customer_Payments',$array);
            }

        }
    }


    private function buildUUID(){
        $sql = "SELECT UUID_SHORT() as id";
        $id_query = $this->db->query($sql);
        $id_rs = $id_query->first_row('array');
        $id = $id_rs['id'];
        return $id;
    }


    public function market_award(){
    
        $UserId = $this->checkLogin();
          
        $para = explode("-",$this->uri->segment(5)); 
         
        if($this->session->userdata('UserId') != $this->base64url_decode($para[0]))
            return;        
        
        $Vendorcode =  $this->base64url_decode($para[1]);
       
        $CashPool = $this->get_current_cashpool($this->data['CompanyId'],$this->data['CurrencyId']);
        $SupplierBid = $this->get_supplier_bid($CashPool['Id'],$Vendorcode);
        
        $this->data['CashPool'] = $CashPool;
        $this->data['SupplierBid'] = $SupplierBid;


        //判断当前是每月第几天
        $curDay = date('d');
        $beginDate = date('Y').'-'.date('m').'-'.$curDay ;

        if(intval($CashPool['ReconciliationDate']) > $curDay)
        {
            $endDate = $beginDate;
            $beginDate = date('Y-m-d', strtotime('-1 month',strtotime($beginDate)));
        }else{
            $endDate = date('Y-m-d', strtotime('+1 month',strtotime($beginDate)));
        }

        //计算本期已经清算的数据
        $sql =" SELECT sum(p.InvoiceAmount) as Payable , sum(p.InvoiceAmount) - sum(a.PayAmount) as Discount,COUNT(p.Id) as InvoiceCount
                FROM `Customer_PayAwards` a          
                INNER JOIN `Customer_Payments` p ON a.InvoiceId = p.Id   
                WHERE p.CustomerId = '{$CashPool['CustomerId']}' AND 
                       p.CurrencyId = '{$CashPool['CurrencyId']}' AND
                       p.Vendorcode = '{$Vendorcode}'                 
                    AND a.AwardDate  between '{$beginDate}' AND '{$endDate}';    
            ";

        $handler = $this->db->query($sql);
        $data = $handler->result_array();

        $sql =" SELECT p.*
          ,CASE WHEN a.Id IS NOT NULL THEN a.PayDpe ELSE CASE WHEN o.Id IS NOT NULL THEN o.PayDpe ELSE 0 END END as Dpe
          ,CASE WHEN a.Id IS NOT NULL THEN p.InvoiceAmount - a.PayAmount ELSE CASE WHEN o.Id IS NOT NULL THEN p.InvoiceAmount - o.PayAmount ELSE 0 END END as Discount
          ,CASE WHEN a.Id IS NOT NULL THEN 1 ELSE 0 END as IsAward
          ,CASE WHEN o.Id IS NOT NULL THEN 1 ELSE 0 END as IsOptimal            
            FROM `Customer_Payments` p
            LEFT JOIN `Customer_PayAwards` a ON a.InvoiceId = p.Id 
            LEFT JOIN `Customer_OptimalAwards` o ON o.InvoiceId = p.Id 
            WHERE p.CustomerId = '{$CashPool['CustomerId']}' AND 
                   p.CurrencyId = '{$CashPool['CurrencyId']}' AND
                   p.Vendorcode = '{$Vendorcode}' 
                AND InvoiceStatus > 0
                AND p.EstPaydate > '".date('Y-m-d',strtotime("{$CashPool['PayDate']} +2 day"))."'
            ORDER BY CASE WHEN o.Id IS NOT NULL THEN 1 ELSE 0 END DESC,p.EstPaydate DESC;    
            ";

        $handler = $this->db->query($sql);
        $data = $handler->result_array();


        $stat = array(
            'total' => array(
                'payable' => 0 ,
                'invoiceCnt' => 0
            ),
            'current' => array(
                'payable' => 0 ,
                'discount' => 0 ,
                'apr' => 0 ,
                'invoiceCnt' => 0
            ),
            'clearing' => array(
                'payable' =>  0,
                'discount' => 0,
                'dpe' => 0,
                'invoiceCnt' => 0
            ),
            'nonclear' => array(
                'payable' =>  0,
                'dpe' => 0,
                'invoiceCnt' => 0
            )
        );

        $list = array();
        
        foreach($data as &$v){

            $stat['total']['payable'] += $v['InvoiceAmount'];
            $stat['total']['invoiceCnt'] += 1;

            if($v['IsAward'] == 1) {
                $stat['current']['payable'] += $v['InvoiceAmount'];
                $stat['current']['discount'] += $v['Discount'];
                $stat['current']['invoiceCnt'] += 1;

            }elseif ($v['IsOptimal'] == 1) {

                $stat['clearing']['payable'] += $v['InvoiceAmount'];
                $stat['clearing']['discount'] += $v['Discount'];
                $stat['clearing']['dpe'] += $v['Dpe'];
                $stat['clearing']['invoiceCnt'] += 1;
                $list[] = $v;

            }else{

                if(isset($CashPool['PayDate']) && !empty($CashPool['PayDate']))
                    $dpe = (strtotime($v['EstPaydate'])  - strtotime($CashPool['PayDate'])) / 86400;
                else
                    $dpe = 0 ;

                $v['Dpe'] = $dpe;
                $v['Discount'] = round($v['InvoiceAmount']/365*$SupplierBid['BidRate']/100 * $dpe ,2);

                $stat['nonclear']['payable'] += $v['InvoiceAmount'];
                $stat['nonclear']['dpe'] += $v['Dpe'];
                $stat['nonclear']['invoiceCnt'] += 1;
                $list[] = $v;
            }
        }

        $stat['nonclear']['dpe'] = $stat['nonclear']['invoiceCnt'] > 0  ? round( $stat['nonclear']['dpe']/$stat['nonclear']['invoiceCnt'], 1) : 0;
        $stat['clearing']['dpe'] = $stat['clearing']['invoiceCnt'] > 0  ? round( $stat['clearing']['dpe']/$stat['clearing']['invoiceCnt'], 1) : 0;

        $this->data['stat'] = $stat;

        $this->data['data'] = $list;
    
        $this->data['title'] = "Award Manage";
        $this->data['pre_nav'] = array('title' => 'Open Markets', 'uri'=>'customer/markets') ;
        
        $this->data['ajax_uri_autocal'] = 'customer/ajax_autocal';
        $this->data['ajax_uri_giveaward'] = 'customer/ajax_giveaward';
        
        $this->data['uri'] = "customer/market_award/{$currency}";
        $this->data['dis_companynav'] = true;
        $this->load->view('customer/market_award', $this->data);
    
    }
    
    
    public function market_setting(){
    
        $UserId = $this->checkLogin();
        $this->data['title'] = 'Market Setting';
        $this->data['uri'] = 'customer/market_setting';
    
        $this->data['ajax_uri_set_cashpool'] = 'customer/ajax_set_cashpool';
        $this->data['ajax_uri_set_schedule'] = 'customer/ajax_set_schedule';
        
        $this->load->view($this->data['uri'], $this->data);    
    }
    
    
    
    public function ajax_set_schedule(){
        
        header('Content-type: text/json');
        $exec_sql = array();
        $result = array('ret' => 0 );
        $where = "";
        
        $cashpoolId = $this->input->post('cashpool') ;
        //指定给某供应商
        $vendorcode = $this->input->post('vendorcode') ;
        $data = $this->input->post('data');

        if(isset($data) && is_array($data) && count($data) > 0 ){
            
            
            $CashPool = $this->get_current_cashpool_byid($cashpoolId);
            
            //将数据存入清算数据表
            $sql = "";         
            $k = 0 ;            
            foreach($data as $v){
            
                if($v['op_mark'] == 'add'){
                    $sql .= "\n";
                                    
                    if($k != 0)
                        $sql .= ",";
                     
                    $sql .= "(".
                        "'".$this->session->userdata('EmailAddress')."',".
                        "'".$CashPool['CustomerId']."',".
                        "'".$CashPool['Id']."',".
                        "'".(isset($vendorcode) ? $vendorcode : '')."',".
                        "'".$v['paydate']."',".
                        "'".$v['payamount']."',".
                        "'".$v['payamount']."',".
                        "'1'".
                        ")";
                    $k++;
                }
        
             }        
             
            if(strlen($sql) > 0)
            {
                
                $sql = "INSERT INTO `Customer_CashPool_PaySchedule`\n".
                        "(`CreateUser`,`CustomerId`,`CashPoolId`,`PayToSupplier`,`PayDate`,`PayAmount`,`AvaAmount`,`PaymentStatus`)\n".
                        "VALUES".$sql.";" ;
                                                        
                $exec_sql[] = $sql ;
                  
             if($this->db_commit($exec_sql)){
                    $result = array('ret' => 1 );
            }else{
                $result = array('ret' => -1 , 'msg' => '更新数据异常');
            }
                
            }
        }
        
        echo json_encode($result);
    }
    
    public function award_history(){
    
        $UserId = $this->checkLogin();
        
        $TotalPayment = 0 ;
        $TotalDiscount = 0 ;
        $CountInvoices = 0 ;
        
        $sql = "SELECT AwardDate,PayDate,s.supplier, s.Vendorcode, b.BidRate, sum(PayAmount) as PayAmount,sum(p.InvoiceAmount) as TotalAmount,count(p.id) as InvCount ,avg(a.Paydpe) as AverageDpe 
                FROM `Customer_PayAwards` a
                INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId
                INNER JOIN `Customer_Suppliers` s ON s.CustomerId = a.CustomerId AND s.Vendorcode = p.Vendorcode
                INNER JOIN `Supplier_Bids` b ON b.Id = a.BidId
                WHERE a.CustomerId = '{$this->data['CompanyId']}' AND a.CurrencyId = '{$this->data['CurrencyId']}'
                group by AwardDate,PayDate,s.supplier,s.Vendorcode, b.BidRate
                order by AwardDate DESC;                                       
                ";               
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
        $list = array();
        
        foreach($data as $v)
        {
            $list[] = array(   
                'AwardDate' => date('Y-m-d',strtotime($v['AwardDate'])),
                'PayDate' => date('Y-m-d',strtotime($v['PayDate'])),
                'supplier' => $v['supplier'],
                'Vendorcode' => $v['Vendorcode'], 
                'InvoiceCount' => $v['InvCount'],
                'AverageDpe' => round($v['AverageDpe'],1),
                'AverageApr' => $v['BidRate'],
                'PayAmount' => $v['PayAmount'],
                'TotalAmount' => $v['TotalAmount'],
                'TotalDiscount' => $v['TotalAmount'] - $v['PayAmount']
            );
            
            $TotalDiscount += $v['TotalAmount'] - $v['PayAmount'];
            $TotalAmount += $v['TotalAmount'] ;
            $CountInvoices += $v['InvCount'];
            
        }
              
        $this->data['data'] = $list;
        
        
        $this->data['ClearAmount'] = $TotalAmount  ;
        $this->data['TotalDiscount'] = $TotalDiscount ;
        $this->data['CountInvoices'] = $CountInvoices ;
        
        $this->data['title'] = 'Award History';
        $this->data['uri'] = 'customer/award_history';
                 
        $this->load->view($this->data['uri'], $this->data);
    }
    
    public function suppliers_list(){
        
         $UserId = $this->checkLogin();
        
        
         $sql = "SELECT c.Id,c.CreateTime,c.supplier,c.Vendorcode
                FROM `Customer_Suppliers` c                
                where c.CustomerId = '".$this->data['CompanyId']."' 
                ;";
            
         $handler = $this->db->query($sql);
        
         $result = $handler->result_array();;
        
         $list= array();
        
         
         foreach($result as $s){
             
            $items = array();
                    
            $sql = "SELECT * FROM `Customer_Supplier_Users` 
                where  SupplierId = '{$s['Id']}';";
            
            $handler = $this->db->query($sql);
            
            $data = $handler->result_array();
            
            foreach($data as $i){
                $items[] = array(
                    'Id' => $i['Id'],
                    'CreateTime' => $i['CreateTime'],
                    'UserEmail'=> $i['UserEmail'],
                    'UserStatus'=> $this->convert_status('user_status',$i['UserStatus']),
                    'UserContact'=> $i['UserContact'],
                    'UserPosition'=> $i['UserPosition'],
                    'UserPhone'=> $i['UserPhone']
                );
            }
            
            $list[] = array(
                'supplier' => $s['supplier'],
                'Vendorcode' => $s['Vendorcode'],
                'UserList' => $items             
            );
            
            
         }
         
                 
        $this->data['data'] = $list;
        $this->data['title'] = 'Suppliers Manage';
        $this->data['uri'] = 'customer/suppliers_list';
       
        
        $this->load->view($this->data['uri'], $this->data);
    }
    
    private function convert_status($_type = 'user_status',$_value = 0)
    {
        $STATUS = array(
            //
            'user_status' => array(
                -2 => 'Account Closed',
                -1 => 'Pending Confirm',
                0 => 'Pending Register',
                1 => 'Registered',
            )
        );
        
        return $STATUS[$_type][$_value];
    }
    
    public function invoices_list(){
        
        $UserId = $this->checkLogin();
        
        $this->data['title'] = 'Invoices Manage';
        $this->data['uri'] = 'customer/invoices_list';
        
        $TotalPayment = 0 ;
        $NormalPayment = 0 ;
        $AdjustPayment = 0 ;
        
        //
        $this->load->model('InvoicelistModel');
                
        $data = $this->InvoicelistModel->getValidInvoices($this->data['CompanyId'],$this->data['CurrencyId']) ;
        
        /*
        $sql = "SELECT distinct p.Id,supplier,p.Vendorcode,InvoiceNo,InvoiceAmount,EstPaydate
                    from `Customer_Payments` p  
                    left join `Customer_Suppliers` s ON s.CustomerId=p.CustomerId AND s.Vendorcode=p.Vendorcode                    
                    where p.CustomerId = '{$this->data['CompanyId']}' AND p.CurrencyId = '{$this->data['CurrencyId']}'
                    AND p.InvoiceStatus = 1 
                 Order By p.Vendorcode,p.EstPaydate;
                ";
                
        $handler = $this->db->query($sql);
        $data = $handler->result_array();;
        */
        $list = array();
        
        foreach($data as $key => $v)
        {
            $list[] = array(
                'id' => $v['Id'],
                'num' => $key + 1,
                'vendorcode' => $v['Vendorcode'],
                'supplier' => $v['supplier'],
                'invoiceno' => $v['InvoiceNo'],
                'amount' => $v['InvoiceAmount'],
                'paydate' => date('Y-m-d',strtotime($v['EstPaydate']))  
            );
            
            $NormalPayment += $v['InvoiceAmount'] ;
            
        }
              
        $this->data['data'] = $list;
        
        /*
        //获取
         $sql = "SELECT distinct p.Id,supplier,p.Vendorcode,InvoiceNo,InvoiceAmount,EstPaydate
                    from `Customer_Payments` p  
                    left join `Customer_Suppliers` s ON s.CustomerId=p.CustomerId AND s.Vendorcode=p.Vendorcode                    
                    where p.CustomerId = '{$this->data['CompanyId']}' AND p.CurrencyId = '{$this->data['CurrencyId']}'
                    AND p.InvoiceStatus = 0 
                 Order By p.Vendorcode,p.EstPaydate;
                ";
        
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
        */
        $data = $this->InvoicelistModel->getInvalidInvoices($this->data['CompanyId'],$this->data['CurrencyId']) ;
        $list = array();
        
        foreach($data as $key => $v)
        {
            $list[] = array(
                    'id' => $v['Id'],
                    'num' => $key + 1,
                    'vendorcode' => $v['Vendorcode'],
                    'supplier' => $v['supplier'],
                    'invoiceno' => $v['InvoiceNo'],
                    'amount' => $v['InvoiceAmount'],
                    'status' => $v['InvoiceStatus'],
                    'paydate' => date('Y-m-d',strtotime($v['EstPaydate']))  
                );         
                                   
            $AdjustPayment += $v['InvoiceAmount'] ;
        }
        
        $this->data['undata'] = $list;
        
        
        $TotalPayment += $AdjustPayment +  $NormalPayment ;
        
        $this->data['Payment'] = array( 
            'total' => $TotalPayment,
            'normal' => $NormalPayment,
            'adjust' => $AdjustPayment
            
        );
        
        $this->data['ajax_uri'] = base_url('customer/ajax_invoices') ;                                
        $this->data['ajax_uri_invoice_status'] = 'customer/ajax_set_inv_status';
        $this->load->view($this->data['uri'], $this->data);
        
    }
    
    public function ajax_set_inv_status(){
        
        header('Content-type: text/json');
        $sql = array();
        $result = array('ret' => 0 );
        $where = "";
        
        $invStatus = $this->input->post('status') ;
        $data = $this->input->post('data');
        
        if(isset($data) && is_array($data))
            $where = implode(",",$data) ;
        
        $where = "Id in ({$where})";
        
        $sql[] = "UPDATE `Customer_Payments` SET InvoiceStatus = '{$invStatus}' \n".
            "WHERE {$where};";
        
        
        if($this->db_commit($sql)){
            $result = array('ret' => 1 );
        }else{
            $result = array('ret' => -1 , 'msg' => '更新数据异常');
        }
        
        echo json_encode($result);
    }
    
    public function ajax_autocal(){
        header('Content-type: text/json');

       //获取供应商的发票
       $sql = "";
       $result = array('ret' => 0 );
        
       $Vendorcode = $this->input->post('vendorcode') ;
       $CashPoolId = $this->input->post('cashpool') ;

       if(isset($Vendorcode) && !empty($Vendorcode) && isset($CashPoolId) && !empty($CashPoolId) )
       {           
           $CashPool = $this->get_current_cashpool_byid($CashPoolId);
           $SupplierBid = $this->get_supplier_bid($CashPoolId,$Vendorcode);
                      
           $data = $this->UniversalModel->getRecords('Customer_Payments',
                array('CustomerId' => $CashPool['CustomerId'],
                    'CurrencyId' => $CashPool['CurrencyId'],
                    'Vendorcode' =>$Vendorcode,
                    'InvoiceStatus' => 1
                )
            );
           
           $list = array();
           foreach($data as $v){
               $list[] = array(                   
                   'id'=>$v['Id'],
                   'vendorcode'=>$v['Vendorcode'],
                   'invoice'=>$v['InvoiceNo'],   
                   'invdate'=>$v['InvoiceDate'],                
                   'amount'=>$v['InvoiceAmount'],
                   'bidtype' => $SupplierBid['BidType'],
                   'bidrate' => $SupplierBid['BidRate'],
                   'estpaydate' => $v['EstPaydate'] 
               );
           }
                       
           
           $this->load->model('Calculatemodel');
           
           $source = $this->Calculatemodel->calculate($CashPool['PayDate'],$list) ;   
           
           $rst = $this->Calculatemodel->analyticalPlan($CashPool['DesiredAPR'],$CashPool['PayAmount'],$source);
           
           $result = array( 
                    'ret' => 1,
                    'data' => $rst
                );
           
        }   
        
        echo json_encode($result);
    }


    public function demo(){

        $CashPool = $this->get_current_cashpool_byid(1);

        $sql =" SELECT p.Id,p.Vendorcode,p.InvoiceNo,p.InvoiceDate,p.InvoiceAmount,p.EstPaydate,'apr' as BidType,b.BidRate 
            FROM `Customer_Payments` p
            INNER JOIN `Supplier_Bids` b ON b.CustomerId = p.CustomerId AND b.BidStatus = 1 AND b.Vendorcode = p.Vendorcode AND b.CashPoolId = '{$CashPool['Id']}'
            WHERE p.CustomerId = '{$CashPool['CustomerId']}' AND p.CurrencyId = '{$CashPool['CurrencyId']}'                    
                AND InvoiceStatus = 1                
                AND p.EstPaydate > '".date('Y-m-d',strtotime("{$CashPool['PayDate']} +2 day"))."';";

        $handler = $this->db->query($sql);
        $data = $handler->result_array();

        $list = array();

        foreach($data as $v){
            $list[] = array(
                'id'=>$v['Id'],
                'vendorcode'=>$v['Vendorcode'],
                'invoice'=>$v['InvoiceNo'],
                'invdate'=>$v['InvoiceDate'],
                'amount'=>$v['InvoiceAmount'],
                'bidtype' => $v['BidType'],
                'bidrate' => $v['BidRate'],
                'estpaydate' => $v['EstPaydate']
            );
        }

        $this->load->model('Calculatemodel');

        $source = $this->Calculatemodel->calculate($CashPool['PayDate'],$list) ;

        $data = $this->Calculatemodel->analyticalPlan($CashPool['DesiredAPR'],$CashPool['PayAmount'],$source);

        var_dump($data);

        $this->data('result',$data);

        $this->data['uri'] = 'customer/demo';
        $this->load->view($this->data['uri'], $this->data);

    }

    //重新计算最优路径
    private function Calculate_OptimalAward($CashPool){

        $sql =" SELECT p.id,p.vendorcode,p.InvoiceNo as invoice,p.InvoiceDate as invdate,p.InvoiceAmount as amount,p.estpaydate,b.bidtype,b.bidrate 
            FROM `Customer_Payments` p
            INNER JOIN `Supplier_Bids` b ON b.CustomerId = p.CustomerId AND b.BidStatus = 1 AND b.Vendorcode = p.Vendorcode AND b.CashPoolId = '{$CashPool['Id']}'
            WHERE p.CustomerId = '{$CashPool['CustomerId']}' AND p.CurrencyId = '{$CashPool['CurrencyId']}'                    
                AND InvoiceStatus = 1
                AND p.EstPaydate > '".date('Y-m-d',strtotime("{$CashPool['PayDate']} +2 day"))."';";

        $handler = $this->db->query($sql);
        $list = $handler->result_array();

        $this->load->model('Calculatemodel');

        $source = $this->Calculatemodel->calculate($CashPool['PayDate'],$list) ;

        $data = $this->Calculatemodel->analyticalPlan($CashPool['DesiredAPR'],$CashPool['PayAmount'],$source);

        //将数据存入清算数据表
        $sql = "INSERT INTO `Customer_OptimalAwards`\n".
            "(`CreateUser`,`CustomerId`,`CurrencyId`,`PaymentId`,`PayDate`,`BidId`,`InvoiceId`,`PayAmount`,`PayDpe`,`AwardDate`,`AwardStatus`)\n".
            "VALUES";

        foreach( $data["list"] as $k=> $v)
        {

            $sql .= "\n";

            if($k != 0)
                $sql .= ",";

            $sql .= "(".
                "'".$this->session->userdata('EmailAddress')."',".
                "'".$CashPool['CustomerId']."',".
                "'".$CashPool['CurrencyId']."',".
                "'".$CashPool['PayId']."',".
                "'".$CashPool['PayDate']."',".
                "{$v["BidRate"]},".
                "{$v['Id']},".
                ($v['amount']-$v['accrual']).",".
                "{$v['diffdays']},".
                "'".date('Y-m-d',time())."',".
                "'0'".
                ")";

        }

        return $sql;
    }

    public function ajax_giveaward(){
        header('Content-type: text/json');
    
        $result = array( 'ret'=> -1 );
        $where = "";
        
        $CashPoolId = $this->input->post('cashpool') ;
        $bidId = $this->input->post('bidid') ;
        $data = $this->input->post('award_data');

        $CashPool = $this->get_current_cashpool_byid($CashPoolId);
        
        $ava_amount = $CashPool['PayAmount'] ;
        
        if(isset($data) && is_array($data))
            $where = implode(",",$data) ;
        
        //$where = "Id in ({$where})";
        
        $data = $this->get_supplier_payment_bywhere($where);

        $sql = "SELECT * FROM Supplier_Bids WHERE Id = '{$bidId}';";
        $handler = $this->db->query($sql);
        $SupplierBid = $handler->result_array(); 
        
        $exec_sql = array();
        
        //将数据存入清算数据表
        $sql = "INSERT INTO `Customer_PayAwards`\n".
            "(`CreateUser`,`CustomerId`,`CurrencyId`,`PaymentId`,`PayDate`,`BidId`,`InvoiceId`,`PayAmount`,`PayDpe`,`AwardDate`,`AwardStatus`)\n".
            "VALUES";        
        
        $amount = 0 ;
        
        foreach( $data as $k=>$v)
        {
            
            $dpe = (strtotime($v['EstPaydate'])  - strtotime($CashPool['PayDate'])) / 86400;
            $discount = round($v['InvoiceAmount']/365*$SupplierBid[0]['BidRate']/100 * $dpe ,2);
            
            $amount += $v['InvoiceAmount']-$discount;                        
            
            $sql .= "\n";
            
            if($k != 0)
             $sql .= ",";
             
            $sql .= "(".
                "'".$this->session->userdata('EmailAddress')."',".
                "'".$CashPool['CustomerId']."',".
                "'".$CashPool['CurrencyId']."',".
                "'".$CashPool['PayId']."',".
                "'".$CashPool['PayDate']."',".
                "'{$bidId}',".
                "'".$v['Id']."',".
                "'".($v['InvoiceAmount']-$discount)."',".
                "'".$dpe."',".
                "'".date('Y-m-d',time())."',".
                "'0'".
                ")";
        
        }
        
        
        if($amount > $ava_amount)
        {
            $result = array( 'ret' => -1,
                'msg' => "选择的发票大于可支付金额 '{$ava_amount}'"
            );
        }else{
                
            $sql .= ";";
            
            $exec_sql[] = $sql ;

            //发票状态变成待支付
            $exec_sql[] = "UPDATE `Customer_Payments` SET InvoiceStatus = 2 \n".
                "WHERE Id in ({$where});";

            //可支付金额减少
            $exec_sql[] = "UPDATE `Customer_CashPool_PaySchedule` SET AvaAmount = '".($ava_amount - $amount)."' \n".
                "WHERE Id = '".$CashPool['PayId']."';";

            $exec_sql[] = "UPDATE `Customer_OptimalAwards` SET AwardStatus = 1 \n".
                "WHERE `InvoiceId` in ({$where});";

            $exec_sql[] = "UPDATE `Customer_OptimalAwards` SET AwardStatus = -1 \n".
                "WHERE `AwardStatus` = 0;";

            $exec_sql[] = $this->Calculate_OptimalAward($CashPool);


            if($this->db_commit($exec_sql)){
                $result = array('ret' => 1 );
            }else{
                $result = array('ret' => -1 , 'msg' => '更新数据异常');
            }
        }
            
        echo json_encode($result);
    }

    private function db_commit($exec_sql){
        $this->db->trans_begin();
        
        if(is_array($exec_sql))
        {
            foreach( $exec_sql as $sql){
                $this->db->query($sql);
            }
        }
                
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
    
    
    public function ajax_invoices(){
        
        header('Content-type: text/json');
        
        $list = array();
        
        $list[] = array(
            'id' => 1,
            'vendorcode' => 'S01',
            'supplier' => 'Cascah',
            'invoiceno' => 'IMV_20171002001',
            'amount' => 12917.20,
            'paydate' => '2017-12-27'  
        );
        
        $list[] = array(
            'id' => 2,
            'vendorcode' => 'S01',
            'supplier' => 'Cascah',
            'invoiceno' => 'IMV_20170922001',
            'amount' => 20178.25,
            'paydate' => '2017-11-26'
        );
        
        $list[] = array(
            'id' => 3,
            'vendorcode' => 'S01',
            'supplier' => 'Cascah',
            'invoiceno' => 'IMV_20171012001',
            'amount' => 42017.19,
            'paydate' => '2017-01-07'
        );
        
        
        $list[] = array(
            'id' => 4,
            'vendorcode' => 'L01',
            'supplier' => 'Piliy',
            'invoiceno' => 'PMV_20171002001',
            'amount' => 14611.20,
            'paydate' => '2017-12-27'
        );
        
        $list[] = array(
            'id' => 5,
            'vendorcode' => 'L01',
            'supplier' => 'Piliy',
            'invoiceno' => 'PMV_20170929001',
            'amount' => 45113.60,
            'paydate' => '2017-12-12'
        );                
        
        
        
        $keywork = $_GET['keyword'];
        $draw = $_GET["draw"];
        $start = $_GET["start"];
        $length = $_GET["length"];
        $totalCount = count($list);
        $recordsFiltered = count($list);
        
        $result = array(
            'draw' => $draw, 
            'recordsTotal' => $totalCount, 
            'recordsFiltered' => $recordsFiltered, 
            'data' => $list
        ) ;
                
        
        echo json_encode($result);
    }
    
    
    public function base64url_encode($data) {

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {

        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function register($userdata) {

        $this->isLogin();
        $useremail = $this->base64url_decode($userdata);


        $inquirytable = 'users_enquiry';
        if ($inquirytable) {
            $udata = array('ContactEmail' => $useremail);
            $userresult = $this->UniversalModel->checkRecord($inquirytable, $udata);
        }
       
        $admintable = 'invite_users_by_admin';
          if ($admintable) {
          $uadata = array('invite_email' => $useremail);
          $useradminresult = $this->UniversalModel->checkRecord($admintable, $uadata );
          }
        if (!$userresult) {

            redirect(site_url(), "refresh");
        }

		$table = 'site_users';

        $this->data['title'] = 'Customer Registration';
        $this->data['useremail'] = $useremail;


        //$this->form_validation->set_message('is_unique', 'The %s is already taken');
        // FOrm Validation
        /**/
        $config = array(
			array(
                'field' => 'EmailAddress',
                'label' => 'Email Address',
                'rules' => 'trim|required|valid_email|xss_clean|is_unique[site_users.EmailAddress]' 
            ),
            array(
                'field' => 'Password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[8]|max_length[15]|xss_clean'
            ),
            array(
                'field' => 'ConfirmPassword',
                'label' => 'ConfirmPassword',
                'rules' => 'trim|required|matches[Password]'
            ),
            array(
                'field' => 'CompanyName',
                'label' => 'CompanyName',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'Region',
                'label' => 'Region',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'ContactName',
                'label' => 'ContactName',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'Position',
                'label' => 'Position',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'Telephone',
                'label' => 'Telephone',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'Cellphone',
                'label' => 'Cellphone',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'captcha',
                'label' => 'Confirm security code',
                'rules' => 'trim|required|xss_clean|callback_captcha_check'
            )
        );


        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {

            $this->middle = 'customer/register';
			//$this->load->view('customer/register', $this->data);
        } else {


            if ($this->input->post('Role') == 'customer') {

                $this->middle = 'customer/register';

                $columns = array();

                $columns['Role'] = $this->input->post('Role');

                $columns['EmailAddress'] = $this->input->post('EmailAddress');

                $columns['Password'] = md5($this->input->post('Password'));

                $columns['CompanyName'] = $this->input->post('CompanyName');

                $columns['Region'] = $this->input->post('Region');

                $columns['ContactName'] = $this->input->post('ContactName');

                $columns['Position'] = $this->input->post('Position');

                $columns['Telephone'] = $this->input->post('Telephone');

                $columns['Cellphone'] = $this->input->post('Cellphone');
                
                $columns['Language'] = $this->input->post('Language');
                
                $columns['RegisterStatus'] = '1';

                $columns['LastLogin'] = $this->currentDateTime;

                $UserId = $this->UniversalModel->save($table, $columns, NULL, NULL);

                if (!$UserId) {

                    $this->session->set_flashdata('registermessage', 'Could not save in db!');
                } else {

                    // Redirect to Success page

                    $this->session->set_userdata('logged', TRUE);

                    $this->session->set_userdata('Role', $this->input->post('Role'));

                    $this->session->set_userdata('UserId', $UserId);

                    $this->session->set_userdata('EmailAddress', $this->input->post('EmailAddress'));

                    $this->session->set_userdata("CompanyName", $this->input->post('CompanyName'));

                    $this->session->unset_userdata('captchaword');

                    //$this->session->unset_userdata("catmessage");

                    if ($this->session->userdata("Role") == 'customer') {

                        redirect("customer/markets", "refresh");
                    }
                }
            }
        }


        // County Listing

        $this->data['datacounty'] = $this->UniversalModel->getCounty();
        
        $this->data['datalanguage'] = array( 
            'chinese' => $this->lang->line('Language')['chinese'] , 
            'english' => $this->lang->line('Language')['english']           
        );
                
        
        $randomString = $this->UniversalModel->generateRandomNumber(5);

        // Captcha Image

        $vals = array(
            'word' => $randomString,
            'img_path' => './captcha/',
            'img_url' => base_url() . 'captcha/',
            'font_path' => './fonts/calibri-webfont.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200
        );

        $cap = create_captcha($vals);

        $this->session->set_userdata('captchaword', $cap['word']);

        $this->data['captchaImage'] = $cap['image'];

        //$this->middle = 'customer/register';
		$this->data['title'] = '注册';
		$this->data['uri'] = 'customer/register/'.$userdata;
        $this->load->view('customer/register', $this->data);
        //parent::layout();
    }

    public function isLogin() {

        if ($this->UniversalModel->isLogin()) {

            if ($this->session->userdata("Role") == 'customer') {

                redirect('home', 'refresh');

            } else {

                redirect('auth', 'refresh');

            }
        }
    }


    private function _validaties() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        if ($this->input->post('supplierid') == '' && empty($this->input->post('supplierid'))) {
            $data['inputerror'][] = 'supplierid';
            $data['error_string'][] = 'Please enter the supplier ID record in your internal ERP to identify this supplier ';
            $data['status'] = FALSE;
        }

        if ($this->input->post('suppliername') == '' && empty($this->input->post('suppliername'))) {
            $data['inputerror'][] = 'suppliername';
            $data['error_string'][] = 'Please enter the supplier name record in your internal ERP';
            $data['status'] = FALSE;
        }

        if ($this->input->post('contactemail') == '' && empty($this->input->post('contactemail'))) {
            $data['inputerror'][] = 'contactemail';
            $data['error_string'][] = 'Please enter the finance contact email address of your supplier';
            $data['status'] = FALSE;
        }

        $myemail = $this->input->post('contactemail');

        if ($myemail != '' && !empty($myemail)) {
            $emailexistsql = "SELECT Email from  supplier_by_customer where CustomerId='" . $this->session->userdata('UserId') . "' And Email='" . $this->input->post('contactemail') . "'";
            $emailexiststatus = $this->db->query($emailexistsql);
            $emailexistdata = $emailexiststatus->result_array();
            $emailexists = $emailexistdata[0]['Email'];
        }
        if ($emailexists == $myemail && $emailexists != '' && !empty($emailexists)) {

            $data['inputerror'][] = 'contactemail';
            $data['error_string'][] = 'Contact Email Already Exists';
            $data['status'] = FALSE;
        }

        //$myemail1 = test_input($this->input->post('contactemail'));


        if ($myemail != '' &&!preg_match('/@.+\./', $myemail)) {

            $data['inputerror'][] = 'contactemail';
            $data['error_string'][] = 'Invalid email format';
            $data['status'] = FALSE;
        }




 if ($this->input->post('contactposition') == '' && empty($this->input->post('contactposition'))) {
            $data['inputerror'][] = 'contactposition';
            $data['error_string'][] = 'Please enter the finance contact person’s position of your supplier';
            $data['status'] = FALSE;
        }


        if ($this->input->post('contactperson') == '' && empty($this->input->post('contactperson'))) {
            $data['inputerror'][] = 'contactperson';
            $data['error_string'][] = 'Please enter the finance contact person name of your supplier';
            $data['status'] = FALSE;
        }
        if ($this->input->post('contactphone') == '' && empty($this->input->post('contactphone'))) {
            $data['inputerror'][] = 'contactphone';
            $data['error_string'][] = 'Please enter the finance contact phone number of your supplier';
            $data['status'] = FALSE;
        }
		
		if ($this->input->post('contactphone')=='0' ) {
            $data['inputerror'][] = 'contactphone';
            $data['error_string'][] = 'Please Enter Contact Phone Greater than zero';
            $data['status'] = FALSE;
        }
		if (!is_numeric($this->input->post('contactphone'))) {
			$data['inputerror'][] = 'contactphone';
            $data['error_string'][] = 'Please Enter Contact Phone and fill Only numeric Value';
            $data['status'] = FALSE;
		}

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }


    public function settingcompany() {
		
            $UserId = $this->checkLogin();
                
            $companyname = $this->input->post('companyname');   	       

            $emaildata = $this->data['userdata'];
            
            /* if($emaildata[0]['Role']=='customer'){
              $siteurl = site_url('customer/settings');

              } */
			
					
			if($emaildata[0]['CompanyName']!=$companyname){
				 $admintable = 'admin_bak';
            $admindata = $this->UniversalModel->getRecords($admintable);

            $tid = 10;
            $resultTemplate = $this->TemplateModel->getTemplateById($tid);

            $mixed_search = array("{Role}", "{siteurl}", "{EmailAddress}", "{UserId}", "{CompanyName}");
            $mixed_replace = array($emaildata[0]['Role'], $siteurl, $emaildata[0]['EmailAddress'], $emaildata[0]['UserId'], $emaildata[0]['CompanyName']);
            $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

            $data = array();
            $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
            $data['contents'] = $messagebody;
            $body = $this->parser->parse('emailtemplate/company', $data, TRUE);
            //echo $body; die;                

            $return = $this->SendEmailModel->sendEmail($admindata[0]['Email'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['FirstName']);
            $this->session->set_flashdata('companymessage', 'Email sent successfully');             

            if ($emaildata[0]['CompanyName']) {
               $updateusersql = "UPDATE `site_users` SET NewCompanyName='" . $companyname . "' where UserId='" . $UserId . "' ";
                $cnquery = $this->db->query($updateusersql);
				
            }
					}
				else{
                echo 'Company Name Same';
           
           }
            
    }
	
	public function settinguser() {
		
        $UserId = $this->checkLogin();
		$this->_validatecontact();
        $UserId = $this->session->userdata['UserId'];        
        $contactemail = $this->input->post('contactemail');
        $contactname = $this->input->post('contactname');
        $contactposition = $this->input->post('contactposition');
        $contactphone = $this->input->post('contactphone');
		
		 echo json_encode(array("status" => TRUE));      

        if ($contactname) {
            $updateusersql = "UPDATE `site_users` SET  ContactName='" . $contactname . "', Position='" . $contactposition . "', Cellphone='" . $contactphone . "' where UserId='" . $UserId  . "' ";
            $cnquery = $this->db->query($updateusersql);          
        }
    }
		
	private function _validatecontact() {
		  
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        if ($this->input->post('contactname') == '' && empty($this->input->post('contactname'))) {
            $data['inputerror'][] = 'contactname';
            $data['error_string'][] = 'Contact Name is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('contactposition') == '' && empty($this->input->post('contactposition'))) {
            $data['inputerror'][] = 'contactposition';
            $data['error_string'][] = 'Contact Position is required';
            $data['status'] = FALSE;
        }

        

        if ($this->input->post('contactphone') == '' && empty($this->input->post('contactphone'))) {
            $data['inputerror'][] = 'contactphone';
            $data['error_string'][] = 'Contact Phone is required';
            $data['status'] = FALSE;
        }
		
		if ($this->input->post('contactphone')=='0' ) {
            $data['inputerror'][] = 'contactphone';
            $data['error_string'][] = 'Please Enter Contact Phone Greater than zero';
            $data['status'] = FALSE;
        }
        

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    /* End APR Settings */



    /* Start Settings Code */

    public function settings() {
        
        $UserId = $this->checkLogin();

        $this->data['results'] = $this->CurrencylistModel->getCurrency();
        $config = array(
            array(
                'field' => 'CurrencyName',
                'label' => 'CurrencyName',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'CapitalCost',
                'label' => 'CapitalCost',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'ExpectAPRPercent',
                'label' => 'ExpectAPRPercent',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {

            $validation_errors = validation_errors();
            $valerrors = explode('</p>', $validation_errors);

            foreach ($valerrors as $index => $error) {
                $error = str_replace('<p>', '', $error);
                $error = trim($error);

                if (!empty($error)) {
                    $errors[$index] = $error;
                }
            }


            //echo json_encode($errors, JSON_FORCE_OBJECT);
            if ($errors) {
                echo json_encode($errors, JSON_FORCE_OBJECT);
            }
        } else {

            $data = array();


            $data['CurrencyId'] = $this->input->post('CurrencyId');
            $data['CustomerId'] = $UserId;
            $data['CurrencyName'] = $this->input->post('CurrencyName');
            $data['CapitalCost'] = $this->input->post('CapitalCost');
            $data['ExpectedAPRType'] = $this->input->post('ExpectAPRRate');
            $data['ExpectedAPR'] = $this->input->post('ExpectAPRPercent');
            $data['AddedDate'] = $this->currentDateTime;
            $result = $this->db->insert('apr_customer_setting', $data);
            $this->session->set_flashdata('settingsuccess', 'Your Customer Setting is added successfully');

            redirect("customer/settings", "refresh");
        }
		$this->data['title'] = 'Settings';
		$this->data['uri'] = 'customer/settings';
        $this->right = 'customer/settings';
        parent::innerLayout();
    }

    /* End Settings Code */


	public function downloadinvoice($planid)
	{
		$order_by = array("WinnerId","ASC");
		################
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();	 
					 
		$where =array('PlanId'=>$planid);
		$this->data['result'] = $this->UniversalModel->getRecords('winners',$where,$order_by);
		//echo '<pre>'; print_r($this->data['result']); die;
	    $raw=2;	
	    	
		for($i=0;$i<count($this->data['result']);$i++){
          
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Vendore Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Invoice');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Amount');					  
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$raw, $this->data['result'][$i]['Vendorcode']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$raw, $this->data['result'][$i]['InvoiceId']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$raw, $this->data['result'][$i]['InvAmount']);					   						  
			$raw++;		 
			
		}
		  
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.'Invoicelist-'.date('d-m-Y').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
				
	}
    /* End upload payment copy */
   

    public function duplicate_currency_check($currencyname) {

        $UserId  = $this->checkLogin();
                       
        $result = $this->UniversalModel->checkRecord('apr_customer_setting', array('CurrencyName' => $currencyname, 'CustomerId' => $UserId));

        //pr($result);
        if ($result) {
            $this->form_validation->set_message('duplicate_currency_check', 'Sorry ! You have already entered settings for this currency');
            return FALSE;
        } else {

            return TRUE;
        }
    }


    
    private function export($data,$columns = array())
    {
        
        
        // Create new PHPExcel object        
        $objPHPExcel = new PHPExcel();
    
        $count = count(data);
    
        $col = array_keys($data[0]);
    
        
        if(!is_array($columns) || count($columns) <=0)
        {
            $columns = array();
            
            foreach($col as $value)
            {
                $columns[] = array(
                    'datakey' => $value,
                    'colheader' => $value,
                    'coltype' => 'text',
                    'colwidth' => 'auto',
                    'colcolor' => 'default'
                );
            }
            
        }        
        
        $xlsCol = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    
        foreach($columns as $key=>$c)
        {
                        
            $objPHPExcel->getActiveSheet()->SetCellValue($xlsCol[$key].'1', $c['colheader']);                      
        }
    
        $raw=1;
        foreach($data as $i){
            $raw++;
            
            foreach($columns as $key=>$c)
            {
                $objPHPExcel->getActiveSheet()->SetCellValue($xlsCol[$key].$raw, $i[$c['datakey']]);
            }
                         
        }
        
        //设置样式
        
        foreach($columns as $key=>$c)
        {
                                              
            $fill = $xlsCol[$key].'2:'.$xlsCol[$key].$raw ;
                                    

            if(isset($c['colwidth']))
                $objPHPExcel->getActiveSheet()->getColumnDimension($xlsCol[$key])->setWidth($c['colwidth']);
            else
                $objPHPExcel->getActiveSheet()->getColumnDimension($xlsCol[$key])->setAutoSize(true);
                
            
            if(isset($c['colcolor']))
                $objPHPExcel->getActiveSheet()->getStyle($fill)->getFont()->getColor()->setARGB($c['colcolor']);
                        
            
            if(isset($c['coltype']))
            {
                                                               
                $objPHPExcel->getActiveSheet()->getStyle($xlsCol[$key])->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::FORMAT_NUMBER);                           
            }
            
            
        }
        
        
        //选择所有数据
        $fill = $xlsCol[0].'1:'.$xlsCol[count($columns) - 1 ].$raw ;
        
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle($fill)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle($fill)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
            
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'payblelist-'.date('d-m-Y').'.xls"');
        header('Cache-Control: max-age=0');
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    
    
    }
    
    
    private function checkUploadFile(){

        //check if this is an ajax request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            die('Please use ajax request!');
        }
        
        //Is file size is less than allowed size.
        if ($_FILES["file"]["size"] > 5242880) {
            die("File size is too big!");
        }
        
        //allowed file type Server side check
        
        switch (strtolower($_FILES["file"]["type"])) {
            //allowed file types
            case 'application/vnd.ms-excel':        
                break;
            case 'image/jpg':
                echo "jpg";
                break;
            case 'image/png':
                echo "png";
                break;
            default:
                die('Unsupported File!'); 
        }
               
        $UploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/epfo/uploads/';
        
        $File_Name = strtolower($_FILES['file']['name']);
        $File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extention
        $Random_Number = rand(0, 9999999999); //Random number to be added to name.
        $NewFileName = $Random_Number . $File_Ext; //new file name
                
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $UploadDirectory . $NewFileName)) {            
            return $UploadDirectory . $NewFileName;
        }else{
            die ('Upload File Unsuccessfully!');            
        }        
        
    }
   
    
}

/* End of file welcome.php */

/* Location: ./application/controllers/register.php */
