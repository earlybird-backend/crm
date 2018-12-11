<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Home extends MY_Controller {

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


        $this->data['link'] = $this->current_controller;

					
    }

    public function checkLogin() {
        if ($this->session->userdata("Role") != 'customer') {

            $this->session->set_flashdata('message', $this->lang->line('auth_login_error'));

            redirect('auth', 'refresh');
            
        }
    }
    public function index() {
        $this->data['title'] = 'Dashboard';
        $db = $this->load->database('bird',true);
        $sql = "select CashpoolCode,-- 市场编号
                    Currency,-- 市场关联币别
                    CashpoolName,-- 市场名称/公司名称
                    CashpoolStatus,-- 市场状态
                    AllocateCash,-- 计划资金
                    AllocateDate,-- 启用日期
                    ValidAmount,-- 可用资金
                    TotalAmount, -- 有效的应付发票(总金额)
                    TotalInvoiceCount,-- 有效的应付发票(发票数量)
                    0.0,-- 已清算发票(总金额)
                    0.0, -- 已清算发票(总数量)
                    EstPaydate,-- 下一个早付日期
                    PayAmount, -- 当前可清算发票总金额
                    PayDiscount, -- 折扣金额
                    AvgAPR -- 平均年化率
                    from stat_current_cashpools";
        $markets = $db->query($sql)->result_array();
        $this->data['markets'] = $markets;
        $sql = "select 
                    -- 供应商注册数统计
                    (select COUNT(1) from Customer_Suppliers where Id in (select SupplierId from Customer_Suppliers_Users where UserStatus>0)) as suppliersCount
                    -- 市场总数
                    ,(select COUNT(1) from Customer_Cashpool where MarketStatus>=0) as cashpoolCount
                    -- 已清算发票数量
                    ,(select IFNULL(SUM(InvoiceCount),0) from Customer_DailyAwards) as invoiceCount
                    -- 供应商未注册数统计
                    ,(SELECT COUNT(1) FROM	Customer_Suppliers WHERE NOT Id IN (SELECT SupplierId FROM Customer_Suppliers_Users WHERE UserStatus > 0) and Vendorcode in (select Vendorcode from Customer_Payments where InvoiceStatus=1)) as noRegSuppliersCount";
      $statistics = $db->query($sql)->row_array();
      $this->data['statistics'] = $statistics;
        $sqltemp = "";
        $union = "";
        for($i=0;$i<90;$i++)
        {
            $sqltemp = "$sqltemp
                        $union
                        select 0 as invoiceCount,0 as validInvoiceCount,DATE_FORMAT(date_add(NOW(), interval -$i day),'%Y-%m-%d') awardDate,$i as distanceDay";
            $union = "union";
        }
        $sql = "select *
                    ,(TO_DAYS(NOW())-TO_DAYS(awardDate)) as distanceDay -- 相距今天天数
                     from 
                    ((SELECT
                        SUM(IFNULL(InvoiceCount, 0)) AS invoiceCount,-- 清算发票数
                        SUM(IFNULL(ValidInvoiceCount, 0)) AS validInvoiceCount,-- 可清算的发票数
                        AwardDate as awardDate
                    FROM
                        Customer_DailyAwards
                    WHERE
                      AwardDate is not null  and DATE_ADD(AwardDate, INTERVAL 90 DAY) >= NOW() GROUP BY AwardDate) a
                      )
                      $union
                      $sqltemp";
        $sql = "SELECT
                SUM(invoiceCount) AS invoiceCount,
                SUM(validInvoiceCount) AS validInvoiceCount,
                DATE_FORMAT(MAX(awardDate),'%m-%d') as MaxAwardDate,
				DATE_FORMAT(MIN(awardDate),'%m-%d') as MinAwardDate,
                (distanceDay DIV 1) AS dkey
                FROM
                ($sql) aaa
               GROUP BY
               dkey
               ORDER BY MinAwardDate DESC";
        $dayStatistics = $db->query($sql)->result_array();//90天的数据统计，以10天为统计单位
        foreach ($dayStatistics as $v)
        {
            $dayDate[] = $v["MaxAwardDate"];
            $day90Statistics1[] = $v["invoiceCount"];
            $day90Statistics2[] = $v["validInvoiceCount"];
        }
        $this->data["dayStatistics"]['date'] = $dayDate;
        $this->data["dayStatistics"]['value1'] = $day90Statistics1;
        $this->data["dayStatistics"]['value2'] = $day90Statistics2;
        $this->load->view('customer/dashboard2018', $this->data);
     /*$day90Statistics = $db->query($sql)->result_array();
        $day90Statistics1 = array();
        $day90Statistics2 = array();
        $day90date = array();
        $distanceDay = -1;
        $cAwardDate = date("Y-m-d");
        $day90Statistics1Count = 0;
        $day90Statistics2Count = 0;
        $nm = 10;
        $nmTemp = $nm;
        foreach ($day90Statistics as $v)
        {
            $distanceDayTemp = $distanceDay;
            $cAwardDate = $v["awardDate"];
            $distanceDay = intval($v["distanceDay"]);
            //今天
            if($distanceDay == 0)
            {
                $day90date[] = date("Y-m-d", strtotime($v["awardDate"]));
                $day90Statistics1[] = number_format($v["invoiceCount"],2);
                $day90Statistics2[] = number_format($v["validInvoiceCount"],2);
                continue;
            }
            else if($distanceDayTemp == -1)
            {
                $day90date[] = date("Y-m-d", strtotime("+$distanceDay day", strtotime($v["awardDate"])));
                $day90Statistics1[] = number_format(0,2);
                $day90Statistics2[] = number_format(0,2);
            }
            //====
            if($distanceDayTemp == -1)
            {
                $n = $distanceDay-1;
            }
            else
            {
                $n = $distanceDay - $distanceDayTemp-1;
            }
            while($n>0)
            {
                --$nmTemp;
                if($nmTemp<=0) {
                    $day90date[] = date("Y-m-d", strtotime("+$n day", strtotime($v["awardDate"])));
                    $day90Statistics1[] = number_format($day90Statistics1Count,2);
                    $day90Statistics2[] = number_format($day90Statistics2Count,2);
                    $day90Statistics1Count = 0;
                    $day90Statistics2Count = 0;
                    $nmTemp = $nm;
                }
                --$n;
            }
            --$nmTemp;
            $day90Statistics1Count += floatval($v["invoiceCount"]);
            $day90Statistics2Count += floatval($v["validInvoiceCount"]);
            if($nmTemp<=0) {
                $day90date[] = $v["awardDate"];
                $day90Statistics1[] = number_format($day90Statistics1Count,2);
                $day90Statistics2[] = number_format($day90Statistics2Count,2);
                $nmTemp = $nm;
                $day90Statistics1Count = 0;
                $day90Statistics2Count = 0;
            }
        }
        $n = 90 - $distanceDay;
        $nn = 1;
        while($n>=0)
        {
            --$nmTemp;
            if($nmTemp<=0) {
                $day90date[] = date("Y-m-d", strtotime("-$nn day", strtotime($cAwardDate)));
                $day90Statistics1[] = number_format($day90Statistics1Count,2);
                $day90Statistics2[] = number_format($day90Statistics2Count,2);
                $nmTemp = $nm;
                $day90Statistics1Count = 0;
                $day90Statistics2Count = 0;
            }
            --$n;
            $nn++;
        }
        $this->data["day90Statistics"]['date'] = $day90date;
        $this->data["day90Statistics"]['value1'] = $day90Statistics1;
        $this->data["day90Statistics"]['value2'] = $day90Statistics2;*/


    }
    //今日看板
    /*public function index1() {

        $this->data['title'] = 'Dashboard';

        $sql = 'SELECT p.Id,								#主ID , 用户不可见，子页均以该ID为查询条件
            p.CashpoolCode as cashpoolcode,				#市场编号Code 
            p.createtime,								#市场创建时间
            c.companyname as buyer,						#市场买家公司名称
            p.CompanyDivision as division,				#市场子公司 DIVISION
            p.currencyname as currency, 				#市场的使用的币别
            p.currencysign as currencysign,				#市场的币别符号       
            
            IFNULL(p.AvailableAmount,0) as totalamount,	#本次现金计划	
            IFNULL(p.AutoAmount,0) as avaamount,	#剩余可用现金
            
            IFNULL(p.NextPaydate,\'-\') as Paydate,		#下一支付日(NEXT PAYDATE)
            IFNULL(supplier.Cnt,0) as `suppliercount`,	#总涉及的供应商数量（有可用发票包括过期的）
            IFNULL(offer.Cnt,0) as `bidcount`,			#有开价的供应商数量
            IFNULL(ava.amount,0) as `avapayment`,		#共有多少可支付的发票金额
            IFNULL(award.amount,0) as `awardamount`		#当日已经清理多少发票
        
            FROM   `Customer_Cashpool` p 																			#数据表为记录 买家 市场的设置	
            INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId														#基础资料 —— 公司基础信息
            LEFT JOIN `Customer_Cashpool_Allocate` ps ON ps.Id = p.AllocateId
            LEFT JOIN (
                select CashpoolCode, COUNT(DISTINCT Vendorcode) as Cnt 
                from `Customer_Payments` 																			#买家导入的应付发票；CashpoolCode 字段是指 买家市场的编号，Vendorcode 字段是指 供应商在该市场的编号， InvoiceStatus 字段是指发票的状态 
                WHERE InvoiceStatus = 1
                GROUP BY CashpoolCode
            ) supplier ON supplier.CashpoolCode = p.CashpoolCode
            LEFT JOIN (
                select CashpoolId,COUNT(DISTINCT Vendorcode) as Cnt 
                from  earlybird.Supplier_Bids WHERE BidStatus >=0 AND CashpoolId IS NOT NULL GROUP BY CashpoolId				#供应商(Vendor)对资金计划的开价，每个资金计划仅有一个开价记录且字段 BidStatus >= 0 时才为有效
            ) offer ON offer.CashpoolId = p.Id
            LEFT JOIN (
                select CashpoolCode,SUM(InvoiceAmount) as amount
                from `Customer_Payments`  
                WHERE InvoiceStatus = 1 AND EstPaydate > DATE_FORMAT(NOW() ,"%Y-%m-%d") 							# 字段 EstPaydate 是指每笔发票的原付款日期
                GROUP BY CashpoolCode
            ) ava ON ava.CashpoolCode = p.CashpoolCode
            LEFT JOIN (
                select CashpoolId,SUM(PayAmount) as amount
                from `Customer_PayAwards`  																			# 平台计算出来的 供应商 与 买家的发票成交记录； CashpoolId 字段是指资金计划的 ID, PayAmount 字段是指发票支付金额
                WHERE AwardStatus >= 0 AND AwardDate = DATE_FORMAT(NOW() ,"%Y-%m-%d") 								# AwardStatus 字段是指成交记录的状态 AwardStatus >= 0 时才为有效；AwardDate 字段是指成交日期，每张发票只能 “成交”一次
                GROUP BY CashpoolId
            ) award ON award.CashpoolId = p.Id
            WHERE p.MarketStatus >= 0 AND exists(
                            SELECT InvoiceNo FROM `Customer_Payments` m WHERE m.CashpoolCode = p.CashpoolCode
                            AND m.InvoiceStatus = 1 AND m.IsIncluded = 1 AND m.EstPaydate > p.NextPaydate AND m.InvoiceAmount < p.AutoAmount
                        )                   
            ORDER BY p.CreateTime DESC;';

        $this->db = $this->load->database('cisco',true);
        $handler = $this->db->query($sql);
        $rs = $handler->result_array();
        $this->data['marks'] = $rs;
        $this->load->view('customer/dashboard', $this->data);
    }
    //市场日志
    public function activity($id){

        $id = $this->input->get('id');
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $role = $this->input->get('role');
        $level = $this->input->get('level');
        $time_sql = " AND LogTime > DATE_SUB(SYSDATE(),INTERVAL 4 HOUR)";
        if(isset($start) && $start!=""){
            $this->data['start'] = $start;
            if(isset($start)==false || $end==""){
                $time_sql = " AND LogTime >= '{$start}'";
            }else{
                $this->data['end'] = $end;
                $time_sql = " AND LogTime >= '{$start}' and LogTime <= '{$end}'";
            }

        }


        $role_sql = "";
        if(isset($role) && $role!="")
        {
            $this->data['role'] = $role;
            $role_sql = " and (";
            $role_array = explode(',',$role);
            $tmp = [];
            foreach($role_array as $item){
                $tmp[] = " UserRole='{$item}' ";
            }
            $role_sql .= implode("or", $tmp);
            $role_sql .= ")";
        }

        $level_sql = "";
        if(isset($level) && $level!=""){
            $this->data['level'] = $level;
            $level_sql = " and (";
            $level_array = explode(',',$level);
            $tmp = [];
            foreach($level_array as $item){
                $tmp[] = " MethodType='{$item}' ";
            }
            $level_sql .= implode("or", $tmp);
            $level_sql .= ")";
        }

        $this->data['id'] = $id;
        $this->data['pre_nav'] = array('title' => 'Dashboard', 'uri'=> $this->link) ;
        $this->data["title"] = "Market Activity Log";

        $sql = "SELECT 
                ID,							
                LogTime,					
                UserRole,					
                UserName,					
                MethodType,					
                LogContent					
                FROM `activity`.`User_Log`	
                WHERE CashpoolCode = '{$id}'
                {$time_sql}{$role_sql}{$level_sql} 
                ORDER BY LogTime DESC;";
        $handler = $this->db->query($sql);
        $rs = $handler->result_array();
        $this->data['logs'] = $rs;
        $this->load->view('customer/activity_log', $this->data);
    }*/


    private function get_invoice($cashpoolCode, $paydate)
    {
        $invoices = array();

        $sql = "SELECT p.Id, p.Vendorcode, p.InvoiceNo, p.InvoiceAmount, p.EstPaydate  
                    FROM `Customer_Payments` p                    
                    WHERE p.EstPaydate > '" . $paydate . "' 
                    AND p.InvoiceStatus = 1
                    AND p.IsIncluded = 1
                    AND p.CashpoolCode = '{$cashpoolCode}'
                    Order by p.Vendorcode; ";

        $db = $this->load->database('bird',true);
        $query = $db->query($sql);

        $result = $query->result_array();

        foreach ($result as $row) {

            $invoices[] =
                array(
                    "Id" => intval($row["Id"]),
                    "Vendorcode" =>  $row["Vendorcode"],
                    "InvoiceNo" => $row["InvoiceNo"],
                    "InvoiceAmount" => $row["InvoiceAmount"],
                    "EstPaydate" => $row["EstPaydate"],
                    "Dpe" => (strtotime($row['EstPaydate']) - strtotime($paydate)) / 86400
                );
        }

        return $invoices;
    }

    private function get_market($cashpoolCode){
        $sql = "SELECT p.Id,p.CashpoolCode,c.CompanyName, p.CompanyDivision , p.CurrencySign, p.CurrencyName,p.NextPaydate,p.AvailableAmount,p.AutoAmount,p.MiniAPR,p.ExpectAPR,p.PaymentType,p.PaymentDay
            FROM  `Customer_Cashpool` p 
            INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId
            WHERE p.CashpoolCode = '{$cashpoolCode}' and p.MarketStatus >= 0
            ORDER BY p.Id;";

        $db = $this->load->database('bird',true);
        $query = $db->query($sql);
        $result = $query->row_array();

        $sql = "select ServiceDate from Customer_Cashpool_Service where ServiceStatus = 1;";
        $query = $db->query($sql);
        $service = $query->row_array();
        $result["ServiceDate"] = $service  ["ServiceDate"];

        return $result;

    }

    private function get_offers($cashpoolId)
    {
        /*$sql = "  select b.Vendorcode,b.BidRate,b.ResultRate,b.MinAmount,
                    CASE WHEN IFNULL(q.CreateTime,'".date("Y-m-d", time())."') < '".date("Y-m-d", time())."' THEN '".date("Y-m-d", time())."' ELSE q.CreateTime END as CreateTime
                from 
                 Supplier_Bids b
                left join Supplier_Bid_Queue q on q.Id = b.QueueId
                where b.CashpoolId = '{$cashpoolId}'
                and b.BidStatus >= 0 and b.BidRate > 0";*/

        $sql = "select 
                b.Vendorcode -- 供应商ID
                ,b.BidRate -- 开价APR
                ,b.ResultRate -- 获得APR
                ,b.MinAmount -- 最小成交金额
                ,CASE WHEN IFNULL(q.CreateTime,'2018-12-04') < '2018-12-04' 
                THEN '2018-12-04' ELSE q.CreateTime END as CreateTime -- 开价时间
                ,IFNULL(a.PayDiscount,0) as discount -- 折扣金额
                ,IFNULL(a.PayAmount,0) as clear -- 清算应收
                ,IFNULL(a.NoPayAmount,0) as noclear -- 未清算应收
                ,IFNULL(a.Supplier,'') as Supplier
                ,CashpoolStatus -- 对于供应商 (当前市场的状态 0 : 未参与市场 1 : 正常竞价 2 : 无可用发票)
                from 
                Supplier_Bids b
                left join stat_current_cashpools_vendors a on a.Vendorcode=b.Vendorcode and a.CashpoolId = b.CashpoolId 
                left join Supplier_Bid_Queue q on q.Id = b.QueueId
                where b.CashpoolId = '{$cashpoolId}'";
        $db = $this->load->database('bird',true);
        $query = $db->query($sql);
        $result = $query->result_array();

        $offers = array();

        foreach ( $result as $val){
            $offers[$val["Vendorcode"]] = array(
                "total" => 0,
                "discount" => floatval($val["discount"]),
                "clear" => floatval($val["clear"]) ,
                "noclear" => floatval($val["noclear"]) ,
                "offerAPR" => $val["BidRate"],
                "getAPR" => $val["ResultRate"],
                "minPaid" => floatval($val["MinAmount"]),
                "offerTime" => $val["CreateTime"],
                "Supplier" => $val["Supplier"],
                "CashpoolStatusForSupplier"=> intval($val["CashpoolStatus"])
            );
        }

        return $offers;
    }

    private function get_awards($cashpoolId)
    {

        $awards = array();

        $sql = "SELECT p.InvoiceId, p.PayDpe, p.PayDiscount, p.PayAmount  
                    FROM `Customer_PayAwards` p                    
                    WHERE p.AwardDate = '" . date("Y-m-d", time()) . "' 
                    AND p.AwardStatus >= 0
                    AND p.CashpoolId = '{$cashpoolId}'; ";

        $db = $this->load->database('bird',true);
        $query = $db->query($sql);

        $result = $query->result_array();

        foreach ($result as $row) {

            $awards[$row["InvoiceId"]] = array(
                "dpe" =>  $row["PayDpe"],
                "discount" => $row["PayDiscount"],
                "amount" => $row["PayAmount"]

            );
        }

        return $awards;

    }

    private function get_suppliers($cashpoolCode)
    {
        $sql = "select IFNULL(OriginalSupplier,Supplier) as Supplier, Vendorcode from Customer_Suppliers    where  CashpoolCode = '{$cashpoolCode}'; ";

        $db = $this->load->database('bird',true);
        $query = $db->query($sql);

        $result = $query->result_array();

        $suppliers = array();

        foreach ( $result as $val) {
            $suppliers[$val["Vendorcode"]] = $val["Supplier"];
        }

        return $suppliers;
    }
    public function market_current(){
        /*$sql = 'select

                from stat_current_cashpools';*/
        $cashpoolCode = $this->input->get('id');
        $sql = "select 
                    a.CashpoolId,-- 市场Id
                    a.CashpoolCode,-- 市场编号
                    a.CashpoolName,-- 公司名称
                    a.Currency,-- 市场关联币别
                    a.CurrencySign,-- 市场关联币别标识
                    b.NextPaydate,-- 下一次支付日期
                    b.ExpectAPR,-- 期望年化率
                    b.MiniAPR,-- 成本年化率
                    a.CompanyName,-- 市场所属的公司名称
                    a.AllocateCash, -- 计划资金分配金额
                    a.ValidCash, -- 可用资金
                    b.PaymentType,-- 支付周期类型（年，月，日，周等）
                    b.PaymentDay, -- 周期数量
                    
                    (a.PayDiscount + a.NoPayDiscount) as totalDiscount, -- 市场总值
                    a.ValidAmount, -- (市场总值)应付
                    -- (市场总值)年化率(暂时没有)
                    a.ValidAvgDpe,-- (市场总值)提前天数
                    
                    a.PayDiscount,-- 清算总值
                    a.PayAmount,-- (清算总值)应付
                    a.AvgAPR,-- (清算总值)年化率
                    a.AvgDpe,-- (清算总值)提前天数
                    
                    a.NoPayDiscount, -- 未清算总值
                    a.NoPayAmount,-- (未清算总值)应付
                    a.NoAvgAPR,-- (未清算总值)年化率
                    a.NoAvgDpe -- (未清算总值)提前天数
                     from stat_current_cashpools a
                    INNER JOIN Customer_Cashpool b ON a.CashpoolCode=b.CashpoolCode 
                    where a.CashpoolCode = '$cashpoolCode' LIMIT 1";
        $db = $this->load->database('bird',true);
        $query = $db->query($sql);
        $result = $query->row_array();
        $cashpoolId = $result["CashpoolId"];
        $offers = $this->get_offers( $cashpoolId);
        $this->data['list'] = $offers;
        $market = array();
        $market["Id"] = $result["CashpoolId"];
        $market["CashpoolCode"] = $result["CashpoolCode"];
        $market["CompanyName"] = $result["CompanyName"];
        $market["CompanyDivision"] = $result["CashpoolName"];
        $market["CurrencySign"] = $result["CurrencySign"];
        $market["CurrencyName"] = $result["CurrencyName"];
        $market["CurrencyName"] = $result["Currency"];
        $market["NextPaydate"] = $result["NextPaydate"];
        $market["AvailableAmount"] = $result["AllocateCash"];
        $market["AutoAmount"] = $result["ValidCash"];
        $market["MiniAPR"] = $result["MiniAPR"];
        $market["ExpectAPR"] = $result["ExpectAPR"];
        $market["PaymentType"] = $result["PaymentType"];
        $market["PaymentDay"] = $result["PaymentDay"];
        $ServiceDate = $db->query("select ServiceDate from Customer_Cashpool_Service where ServiceStatus = 1;")
            ->row_array()["ServiceDate"];
        $market["ServiceDate"] = $ServiceDate;
        $this->data['market'] = $market;

        $stat = array();
        $stat["currencySign"] = $result["CurrencySign"];
        $total = array();
        $total["paid"] = floatval($result["ValidAmount"]);
        $total["discount"] = floatval($result["totalDiscount"]);
        $total["dpe"] = floatval($result["ValidAvgDpe"]);
        $total["apr"] = 0.00;//目前方法统计
        $stat["total"] = $total;
        $clear = array();
        $clear["paid"] = floatval($result["PayAmount"]);
        $clear["discount"] = floatval($result["PayDiscount"]);
        $clear["dpe"] = floatval($result["AvgDpe"]);
        $clear["apr"] = floatval($result["AvgAPR"]);
        $clear["list"] = array();//这里未填充数据
        $stat["clear"] = $clear;
        $noclear = array();//之前是使用它计算dpe，现在直接获取不需要这个列表
        $noclear["paid"] = floatval($result["NoPayAmount"]);
        $noclear["discount"] = floatval($result["NoPayDiscount"]);
        $noclear["dpe"] = floatval($result["NoAvgDpe"]);
        $noclear["apr"] = floatval($result["NoAvgAPR"]);
        $noclear["list"] = array();//之前是使用它计算dpe，现在直接获取不需要这个列表
        $stat["noclear"] = $noclear;
        $this->data['stat'] = $stat;
        $this->data['title'] = '实时市场 ';
        $this->data['pre_nav'] = array('title' => 'Dashboard', 'uri'=> $this->current_controller) ;
        $this->load->view('customer/market_current', $this->data);
        //echo json_encode($this->data);
        //exit;
    }
    /*public function market_current(){
        //echo json_encode($this->data);
        $cashpoolCode = $this->input->get('id');

        $market = $this->get_market($cashpoolCode);

        $cashpoolCode = $market["CashpoolCode"];
        $cashpoolId = $market["Id"];
        $paydate = $market["NextPaydate"];
        $currencySign = $market["CurrencySign"];

        $invoices = $this->get_invoice( $cashpoolCode , $paydate );
        $offers = $this->get_offers( $cashpoolId);
        $suppliers = $this->get_suppliers($cashpoolCode);
        $awards = $this->get_awards($cashpoolId);

        $total = array(
            "paid" => 0.00,
            "discount" => 0.00,
            "dpe" => 0.00,
            "apr" => 0.00
        );

        $clear =  array(
            "paid" => 0.00,
            "discount" => 0.00,
            "dpe" => 0.00,
            "apr" => 0.00,
            "list" => array()
        );

        $noclear = array(
            "paid" => 0.00,
            "discount" => 0.00,
            "dpe" => 0.00,
            "apr" => 0.00,
            "list" => array()
        );




                if ($key == $inv["Vendorcode"]) {
                    foreach ($offers as $key => &$item) {

                    $item["supplier"] = $suppliers[$key];

                    foreach ($invoices as $inv) {


                        $total["dpe"] += $inv["Dpe"];

                    if (isset( $awards[$inv["Id"]])) {

                        $clear["list"][] = array( 'dpe' => $inv["Dpe"], 'discount'=> $awards[$inv["Id"]]["discount"]);
                        $clear["dpe"] += $inv["Dpe"];
                        $clear["paid"] += $inv["InvoiceAmount"];
                        $clear["discount"] += $awards[$inv["Id"]]["discount"];

                        $item["discount"] += $awards[$inv["Id"]]["discount"];
                        $item["clear"]  += $inv["InvoiceAmount"];
                    } else {

                        $discount = round($inv["InvoiceAmount"] * $inv["Dpe"] * floatval($item["offerAPR"])/365/100 ,2 );

                        $noclear["list"][] = array( 'dpe' => $inv["Dpe"], 'discount'=> $discount);
                        $noclear["dpe"] += $inv["Dpe"];
                        $noclear["paid"] += $inv["InvoiceAmount"];
                        $noclear["discount"] += $discount;

                        $item["noclear"]  += $inv["InvoiceAmount"];
                    }
                }
            }
        }



        $total["paid"] = $clear["paid"] + $noclear["paid"];
        $total["discount"] = $clear["discount"] + $noclear["discount"];
        $clear["dpe"] = count($clear["list"]) > 0 ? round( $clear["dpe"]/count($clear["list"]),1 ) : 0 ;
        $noclear["dpe"] = count($noclear["list"]) > 0 ? round( $noclear["dpe"]/count($noclear["list"]),1 ) : 0 ;

        $total["dpe"] = count($clear["list"]) > 0 || count($noclear["list"]) > 0 ?
            round(  $total["dpe"] / (count($noclear["list"])  + count($clear["list"]) ), 1) : 0 ;

        $avg_apr = 0;

        foreach( $clear["list"] as $val)
        {
            $clear["apr"] += round($val['discount']/$val['dpe']*365*100/$clear["paid"], 2);
            $avg_apr  += round($val['discount']/$val['dpe']*365*100/ $total["paid"], 2);
        }

        foreach( $noclear["list"] as $val)
        {
            $noclear["apr"] += round($val['discount']/$val['dpe']*365*100/$noclear["paid"], 2);
            $avg_apr  += round($val['discount']/$val['dpe']*365*100/ $total["paid"], 2);
        }

        $total["apr"] = $avg_apr;

        $this->data["list"] = $offers;

        $this->data["market"] = $market;

        $this->data["stat"] = array(
            "currencySign" => $currencySign ,
            "total" => $total,
            "clear"  => $clear,
            "noclear"  => $noclear
        );

        $this->data['title'] = '实时市场 ';
        $this->data['pre_nav'] = array('title' => 'Dashboard', 'uri'=> $this->current_controller) ;
        //echo json_encode($this->data);
        $this->load->view('customer/market_current', $this->data);
    }*/

    public function profile() {

        $this->data['title'] = 'User Profile';

        $this->load->view('customer/profile', $this->data);

        parent::innerLayout();
    }
    

    private function get_suppliers_withbided($cashpool){

        //获取开价的供应商列表
        $sql = "SELECT c.Id as SupplierId,c.supplier,b.*
        FROM `earlybird`.`Supplier_Bids` b
        INNER JOIN `Customer_Suppliers` c ON c.CustomerId = b.CustomerId AND c.Vendorcode = b.Vendorcode
        WHERE b.CashPoolId = '{$cashpoolId}' AND b.BidStatus = 1
        ORDER BY b.BidRate DESC ;";
                
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();
        
        $min_bid = 0 ;
        $max_bid = 0 ;
        $total_discount = 0 ;
        $total_payable = 0 ;
        $total_invoice = 0 ;
        $total_supplier = 0;
        
        $list = array();
        
        foreach ($data as $v){
        
        if( ($min_bid > 0 && $min_bid > $v['BidRate']) || $min_bid === 0 )
            $min_bid = $v['BidRate'];
        
            if( ($max_bid > 0 && $max_bid < $v['BidRate']) || $max_bid === 0 )
                $max_bid = $v['BidRate'];
        
                $total_supplier ++;
        
                    $payments = $this->get_supplier_payment($this->data['CompanyId'], $this->data['CurrencyId'], $v['Vendorcode']);
                    $discount = 0 ;
                    $amount = 0;
                    $dpe = 0;
                    $_discount = 0;
                    
                        foreach ( $payments as $p){
                            $total_invoice ++;
                            $amount += $p['InvoiceAmount'];
                            if(isset($paydate) && !empty($paydate) && strtotime($paydate) > time()){
                                
                                $dpe  = (strtotime($p['EstPaydate'])  - strtotime($paydate)) / 86400;
                                $_discount = round($p['InvoiceAmount']/365*$v['BidRate']/100 * $dpe ,2);
            
                            }
                            $discount += $_discount;
                            $total_discount += $_discount;
                            $total_payable += $p['InvoiceAmount'];
                        }
        
        
                            $list[] = array(
                                'SupplierId' => $v['SupplierId'],
                                'supplier' => $v['supplier'],
                                'Vendorcode' => $v['Vendorcode'],
                                'Discount' => $discount > 0 ? $discount : '-',
                                'Eligible' => $amount,
                                'APR' => $v['BidRate']
                            );
        
                        }
                        
                        
             return     array(
                            'list' => $list,
                            'stat' => array(
                                'supplier_cnt' => $total_supplier,
                                'invoice_cnt' =>$total_invoice,
                                'discount' => $total_discount,
                                'payable' => $total_payable,
                                'min_bid' => $min_bid,
                                'max_bid' => $max_bid
                                )
                        );
             
    }

    private function get_optimal_award($CashPool){
        
        $sql = "SELECT s.supplier,s.Vendorcode,b.BidRate as `APR`,count(o.Id) as InvoiceCount ,sum(p.InvoiceAmount) as Eligible,sum(p.InvoiceAmount) - sum(o.PayAmount) as Discount
                FROM `Customer_OptimalAwards` o
                INNER JOIN `Customer_Payments` p ON p.Id = o.InvoiceId
                INNER JOIN `Customer_Suppliers` s ON s.Vendorcode = p.Vendorcode
                INNER JOIN `Supplier_Bids` b ON b.Id = o.BidId
                WHERE o.CustomerId = '{$CashPool['CustomerId']}' AND o.CurrencyId =  '{$CashPool['CurrencyId']}'  AND o.AwardStatus = 0
                GROUP BY s.supplier,s.Vendorcode,b.BidRate
                ORDER BY b.BidRate DESC 
            ";

        $handler = $this->db->query($sql);

        $data = $handler->result_array();

        return $data;
    }

    private function get_supplier_statistic(){

    }

    private function analyze_current_statistic($CashPool){
        

        
        $_market = array(
            'total_payable' => 0,
            'clear_payable' => 0,
            'nonclear_payable' => 0
        );
        
        
        $_canvas = array(
            
        );

        $_current = array(
            'supplier_cnt' => 0,
            'invoice_cnt' => 0 ,
            'payable' => 0 ,
            'discount' => 0 ,
            'avg_dpe' => 0 ,
            'avg_apr' => 0
        );

        $_clearing = array(
            'supplier_cnt' => 0,
            'invoice_cnt' => 0 ,
            'payable' => 0 ,
            'discount' => 0 ,
            'avg_dpe' => 0 ,
            'avg_apr' => 0
        );

        $_pending = array(
            'supplier_cnt' => 0,
            'invoice_cnt' => 0 ,
            'payable' => 0 ,
            'discount' => 0 ,
            'min_bid' => 0 ,
            'max_bid' => 0 ,
            'avg_dpe' => 0 ,
            'avg_apr' => 0

        );
        
        
        if( $CashPool['MarketStatus'] != 1){
            $stat_date = date('Y-m-d',strtotime("-1 day"));
        }else{
            $stat_date = date('Y-m-d',time());
        }

        //本期
        $market_date = date('Y-m-d',strtotime(date('Y-n-',strtotime($stat_date)) .$CashPool['ReconciliationDate'])) ;
    
        if($market_date > $stat_date)
        {
            date('Y-m-d',strtotime("$market_date -1 month"));
        }

        //本期统计
        $sql = "SELECT sum(p.InvoiceAmount) as TotalAmount,
                sum(CASE WHEN a.Id IS NULL THEN 0 ELSE p.InvoiceAmount END) as ClearAmount ,
                sum(CASE WHEN a.Id IS NOT NULL THEN 0 ELSE p.InvoiceAmount END) as NonClearAmount
                FROM `Customer_Payments` p
                LEFT JOIN `Customer_PayAwards` a ON p.Id = a.InvoiceId        
                WHERE p.CustomerId = '{$CashPool['CustomerId']}' AND p.CurrencyId =  '{$CashPool['CurrencyId']}'  
                  AND p.InvoiceStatus >= 1 AND p.EstPaydate > '".date('Y-m-d',strtotime("+2 day"))."'
              ;";

        $handler = $this->db->query($sql);

        $data = $handler->result_array();

        if(count($data) > 0)
        {
            $_market = array(
                'total_payable' => $data[0]['TotalAmount'],
                'clear_payable' => $data[0]['ClearAmount'],
                'nonclear_payable' => $data[0]['NonClearAmount']
            );
        }

        //分析当天待清算状态
        $sql = "SELECT p.Vendorcode,b.BidRate,count(o.Id) as InvoiceCount ,sum(p.InvoiceAmount) as InvoiceAmount,sum(p.InvoiceAmount) - sum(o.PayAmount) as Discount,sum(o.PayDpe) as `Dpe`
        FROM `Customer_OptimalAwards` o
        INNER JOIN `Customer_Payments` p ON p.Id = o.InvoiceId
        INNER JOIN `Supplier_Bids` b ON b.Id = o.BidId 
        WHERE o.CustomerId = '{$CashPool['CustomerId']}' AND o.CurrencyId =  '{$CashPool['CurrencyId']}'  
                AND o.AwardStatus = 0                                           
        GROUP BY p.Vendorcode,b.BidRate
        ORDER BY b.BidRate DESC ;";
        
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();

        if(count($data) > 0)
        {
            $_pending['supplier_cnt'] = count($data);
            $_pending['min_bid'] = $data[count($data) - 1]['BidRate'];
            $_pending['max_bid'] = $data[0]['BidRate'];
        }

        foreach ($data as $v)
        {
            $_pending['avg_dpe'] += $v['Dpe'];
            $_pending['invoice_cnt'] += $v['InvoiceCount'];
            $_pending['payable'] += $v['InvoiceAmount'];
            $_pending['discount'] += $v['Discount'];
        }


        $_pending['avg_dpe'] = round($_pending['avg_dpe']/$_pending['invoice_cnt'],1);

        //当天已清算状态
        $sql = "SELECT p.Vendorcode,b.BidRate,count(o.Id) as InvoiceCount ,sum(p.InvoiceAmount) as InvoiceAmount,sum(p.InvoiceAmount) - sum(o.PayAmount) as Discount,sum(o.PayDpe) as `Dpe`
        FROM `Customer_PayAwards` o
        INNER JOIN `Customer_Payments` p ON p.Id = o.InvoiceId
        INNER JOIN `Supplier_Bids` b ON b.Id = o.BidId 
        WHERE o.CustomerId = '{$CashPool['CustomerId']}' AND o.CurrencyId =  '{$CashPool['CurrencyId']}'  
                AND o.AwardDate = '{$stat_date}'                                         
        GROUP BY p.Vendorcode,b.BidRate
        ORDER BY b.BidRate DESC ;";

        $handler = $this->db->query($sql);

        $data = $handler->result_array();


        foreach ($data as $v)
        {
            $_clearing['supplier_cnt'] += 1;
            $_clearing['avg_dpe'] += $v['Dpe'];
            $_clearing['invoice_cnt'] += $v['InvoiceCount'];
            $_clearing['payable'] += $v['InvoiceAmount'];
            $_clearing['discount'] += $v['Discount'];
        }

        $_clearing['avg_dpe'] = round($_clearing['avg_dpe']/$_clearing['invoice_cnt'],1);

        //分析当天已经成交记录
        $sql = "SELECT p.Id,b.BidRate,p.InvoiceAmount,(p.InvoiceAmount - o.PayAmount) as Discount,'clear' as Type
                FROM `Customer_PayAwards` o
                INNER JOIN `Customer_Payments` p ON p.Id = o.InvoiceId
                INNER JOIN `Supplier_Bids` b ON b.Id = o.BidId
                WHERE o.CustomerId = '{$CashPool['CustomerId']}' AND o.CurrencyId =  '{$CashPool['CurrencyId']}'
                  AND o.AwardDate = '{$stat_date}'  
                UNION     
                SELECT p.Id,b.BidRate,p.InvoiceAmount,(p.InvoiceAmount - o.PayAmount) as Discount,'pending'
                FROM `Customer_OptimalAwards` o
                INNER JOIN `Customer_Payments` p ON p.Id = o.InvoiceId
                INNER JOIN `Supplier_Bids` b ON b.Id = o.BidId
                WHERE o.CustomerId = '{$CashPool['CustomerId']}' AND o.CurrencyId =  '{$CashPool['CurrencyId']}'
                  AND o.AwardStatus = 0   
                ;";


        $_current = array(
            'supplier_cnt' => 0,
            'invoice_cnt' => $_pending['invoice_cnt'] + $_clearing['invoice_cnt'] ,
            'payable' => $_pending['payable'] + $_clearing['payable']  ,
            'discount' => $_pending['discount'] + $_clearing['discount']  ,
            'avg_dpe' => round($_pending['avg_dpe']*$_pending['avg_dpe']/($_pending['avg_dpe'] + $_clearing['avg_dpe']),1) + round($_clearing['avg_dpe']*$_clearing['avg_dpe']/($_pending['avg_dpe'] + $_clearing['avg_dpe']),1),
            'avg_apr' => 0
        );


        $stat = array(
            'market' => $_market,       //本期统计数据
            'canvas' => $_canvas,       //统计图的数据

            'current' => $_current,
            'clearing' => $_clearing,         //当天已经清除
            'pending' => $_pending      //当天待清除
        );

        return $stat;
    }

    public function markets(){
        
        $UserId = $this->checkLogin();
        
        //获取当前市场的资金池信息        
        $CashPool = $this->get_current_cashpool($this->data['CompanyId'],$this->data['CurrencyId']);
        
        $this->data['CashPool'] = $CashPool;
        
        //$data = $this->get_suppliers_withbided($CashPool);
        $data = $this->get_optimal_award($CashPool);
        
        $awardlist = array();
        
        if(count($data) >0 )
        {
            
            $cnt = count($data) > 5 ? 5 : count($data);    
        
            for($i = 0;$i < $cnt ; $i++){
                $awardlist[] = $data[$i];         
            }               
        }
                
        $this->data['award_data'] = $awardlist;                
        
        $this->data['stat'] = $this->analyze_current_statistic($CashPool);





        //获取已开价的供应商统计数        
        $sql = "            
            SELECT count(b.id) as supplier_cnt, sum( case when p.Vendorcode is null then 0 else 1 end ) as ap_cnt
            FROM `Supplier_Bids` b
            INNER JOIN `Customer_Suppliers` c ON c.CustomerId = b.CustomerId AND c.Vendorcode = b.Vendorcode
            LEFT JOIN (SELECT DISTINCT Vendorcode FROM `Customer_Payments` 
                WHERE  CurrencyId = '".$this->data['CurrencyId']."' AND CustomerId = '".$this->data['CompanyId']."' 
                ) p ON p.Vendorcode = b.Vendorcode
            WHERE b.CashPoolId = '{$CashPool['Id']}' AND b.BidStatus = 1;";
        
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();
        
        $this->data['supplier']['bided'] = array(
            'count' => $data[0]['supplier_cnt'],
            'ap' => isset($data[0]['ap_cnt'])?$data[0]['ap_cnt']:0       
        );
       
        //获取已注册的供应商统计数
        $sql = "
            SELECT count(b.id) as supplier_cnt, sum( case when p.Vendorcode is null then 0 else 1 end ) as ap_cnt
            FROM `Customer_Suppliers` b
            INNER JOIN (
                SELECT DISTINCT SupplierId FROM `Customer_Supplier_Users`
                WHERE CustomerId = '".$this->data['CompanyId']."' 
            ) u ON u.SupplierId = b.Id
            LEFT JOIN (
                SELECT DISTINCT Vendorcode FROM `Customer_Payments`
                WHERE  CustomerId = '".$this->data['CompanyId']."'  
            ) p ON p.Vendorcode = b.Vendorcode
            WHERE b.CustomerId = '".$this->data['CompanyId']."' ;";
        
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();
        
        $this->data['supplier']['registered'] = array(
            'count' => $data[0]['supplier_cnt'],
            'ap' => isset($data[0]['ap_cnt'])?$data[0]['ap_cnt']:0
        );
        
        //获取供应商统计数
        $sql = "
            SELECT count(b.id) as supplier_cnt, sum( case when p.Vendorcode is null then 0 else 1 end ) as ap_cnt
            FROM `Customer_Suppliers` b           
            LEFT JOIN (
                SELECT DISTINCT Vendorcode FROM `Customer_Payments`
                WHERE  CurrencyId = '".$this->data['CurrencyId']."' AND CustomerId = '".$this->data['CompanyId']."'
            ) p ON p.Vendorcode = b.Vendorcode
            WHERE b.CustomerId = '".$this->data['CompanyId']."';";
        
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();
        
        $this->data['supplier']['total'] = array(
            'count' => $data[0]['supplier_cnt'],
            'ap' => isset($data[0]['ap_cnt'])?$data[0]['ap_cnt']:0
        );
        
        //注册供应商的排名
        $sql = "
            SELECT s.Id,s.supplier,p.TotalAmount
            FROM (
                SELECT CustomerId,Vendorcode,sum(InvoiceAmount) as TotalAmount 
                FROM `Customer_Payments`
                WHERE  CurrencyId = '".$this->data['CurrencyId']."' AND CustomerId = '".$this->data['CompanyId']."'
                GROUP BY CustomerId,Vendorcode
                ) p
            INNER JOIN `Customer_Suppliers` s ON s.CustomerId = p.CustomerId AND s.Vendorcode = p.Vendorcode
            INNER JOIN (
                SELECT DISTINCT SupplierId FROM `Customer_Supplier_Users`
                WHERE CustomerId = '".$this->data['CompanyId']."' 
                ) u ON u.SupplierId = s.Id
            ORDER BY TotalAmount DESC
            LIMIT 5;";
       
        $handler = $this->db->query($sql);
        
        $data = $handler->result_array();
                
        $this->data['reg_supplier_rank'] = $data;
        
        //所有供应商的排名
        $sql = "
            SELECT s.Id,s.supplier,p.TotalAmount
            FROM (
                SELECT CustomerId,Vendorcode,sum(InvoiceAmount) as TotalAmount
                FROM `Customer_Payments`
                WHERE  CurrencyId = '".$this->data['CurrencyId']."' AND CustomerId = '".$this->data['CompanyId']."'
                GROUP BY CustomerId,Vendorcode
                ) p
            INNER JOIN `Customer_Suppliers` s ON s.CustomerId = p.CustomerId AND s.Vendorcode = p.Vendorcode            
            ORDER BY TotalAmount DESC
            LIMIT 5;";
        
        $handler = $this->db->query($sql);
        
                
        $data = $handler->result_array();
        
        $this->data['all_supplier_rank'] = $data;
                

        $this->data['title'] = 'Open Markets';
        $this->data['uri'] = 'customer/markets';
        
        $this->load->view('customer/markets', $this->data);        
    }  
    
    private function market_statistics($CashPool){

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
        
        //统计结果
        $stat = array();
        
        //获取周期内的统计
        $sql = "
            SELECT SUM(p.InvoiceAmount) as TotalAP,SUM(a.PayAmount) as TotalPay,AVG(a.PayDpe) as AVGDpe 
            FROM `Customer_PayAwards` a
            INNER JOIN `Customer_Payments` p ON p.Id = a.PayId
            WHERE a.CustomerId = '{$CashPool['CustomerId']}' AND a.CurrencyId = '{$CashPool['Currencyid']}'
            AND a.AwardDate >= '{$beginDate}' AND a.AwardDate < '{$endDate}';
            ";
        
        $handler = $this->db->query($sql);
        $result = $handler->result_array();
        
        $stat['total'] = array(
            'discount' => $result[0]['TotalAP']- $result[0]['TotalPay'],
            'ap' =>  $result[0]['TotalAP'],
            'dpe' => $result[0]['AVGDpe'],
            'apr' => ($result[0]['TotalAP']- $result[0]['TotalPay'])/$result[0]['TotalAP']/$result[0]['AVGDpe']*365
        );
        
        //获取当天的统计
        $sql = "
            SELECT SUM(p.InvoiceAmount) as TotalAP,SUM(a.PayAmount) as TotalPay,AVG(a.PayDpe) as AVGDpe
            FROM `Customer_PayAwards` a
            INNER JOIN `Customer_Payments` p ON p.Id = a.PayId
            WHERE a.CustomerId = '{$CashPool['CustomerId']}' AND a.CurrencyId = '{$CashPool['Currencyid']}'
            AND a.AwardStatus = 0 AND a.AwardDate = '{$curDay}' ;
        ";
        
        $handler = $this->db->query($sql);
        $result = $handler->result_array();
        
        $stat['current'] = array(
            'discount' => $result[0]['TotalAP']- $result[0]['TotalPay'],
            'ap' =>  $result[0]['TotalAP'],
            'dpe' => $result[0]['AVGDpe'],
            'apr' => ($result[0]['TotalAP']- $result[0]['TotalPay'])/$result[0]['TotalAP']/$result[0]['AVGDpe']*365
        );
        
        //获取当天未能清算的统计
        $sql = "
        SELECT SUM(p.InvoiceAmount) as TotalAP,SUM(a.PayAmount) as TotalPay,AVG(a.PayDpe) as AVGDpe
        FROM `Customer_PayAwards` a
        INNER JOIN `Customer_Payments` p ON p.Id = a.PayId
        WHERE a.CustomerId = '{$CashPool['CustomerId']}' AND a.CurrencyId = '{$CashPool['Currencyid']}'
        AND a.AwardStatus = 0 AND a.AwardDate = '{$curDay}' ;
        ";
        
        $handler = $this->db->query($sql);
        $result = $handler->result_array();
        
        $stat['nonclear'] = array(
            'discount' => $result[0]['TotalAP']- $result[0]['TotalPay'],
        'ap' =>  $result[0]['TotalAP'],
            'dpe' => $result[0]['AVGDpe'],
            'apr' => ($result[0]['TotalAP']- $result[0]['TotalPay'])/$result[0]['TotalAP']/$result[0]['AVGDpe']*365
            );
        
        //获取当天未支付的统计
                
        return $stat;
    }
    
    public function market_detail(){
             
        $UserId = $this->checkLogin();
        //  获取当前市场的资金池信息        
        $CashPool = $this->get_current_cashpool($this->data['CompanyId'],$this->data['CurrencyId']);
        
        $this->data['CashPool'] = $CashPool;
        
        $data = $this->get_suppliers_withbided($CashPool['Id'],$CashPool['PayDate']);
                        
        $this->data['award_data'] = $data['list'] ;
        
        $this->data['stat'] = array(
            'clearing' => $data['stat']
        );
                
        $this->data['CashPool'] = $CashPool;
        $this->data['title'] = $CashPool['CurrencyName']." Market";
        $this->data['pre_nav'] = array('title' => 'Open Markets', 'uri'=>'customer/markets') ;
        $this->data['uri'] = "customer/market_detail/".$this->base64url_encode($this->data['CompanyId'])."/".$this->base64url_encode($this->data['CurrencyId']);
        
        $this->data['dis_companynav'] = true;
         
        $this->load->view('customer/market_detail', $this->data);
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
