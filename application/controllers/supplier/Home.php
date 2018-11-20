<?php
//echo 'Test'; die;
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
        
        $this->load->model('UploadsupplierModel');
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
        
        $where = array('UserId' => $this->session->userdata('UserId') ,'Role' => $this->session->userdata('Role'));
        
        $table = 'site_users';
        $result = $this->UniversalModel->getRecords($table, $where);
        
        $this->userdata = $result[0];
        
        $this->userdata['Role'] = $this->session->userdata("Role");
        
        $this->userdata = $this->session->userdata;
        
        if ($this->userdata['Role'] == 'supplier') {
            
            $this->data['userdata'] =  $result;
        }
        
        return $result[0]['UserId'] ;
        
        //$this->userdata['Username'] = $this->session->userdata("Username");
        //$this->session->set_userdata("Username", $this->userdata['Username']);
        //$this->session->set_userdata("catid", $this->userdata['CategoryId']);
    }
//今日看板
    public function index() {

        $today = date('Y-m-d',time());

        $sql = "SELECT p.Id,		
            p.CashpoolCode as cashpoolcode,	
            p.createtime,					
            c.companyname as buyer,			
            p.CompanyDivision as division,	
            p.currencyname as currency, 	
            p.currencysign as currencysign,	
            
            IFNULL(p.AvailableAmount,0) as totalamount,
            IFNULL(p.AutoAmount,0) as avaamount,
            
            IFNULL(p.NextPaydate,'-') as Paydate,	
            IFNULL(supplier.Cnt,0) as `suppliercount`,
            IFNULL(offer.Cnt,0) as `bidcount`,		
            IFNULL(ava.amount,0) as `avapayment`,	
            IFNULL(award.amount,0) as `awardamount`	
        
            FROM   `Customer_Cashpool` p 						
            INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId	
            LEFT JOIN `Customer_Cashpool_Allocate` ps ON ps.Id = p.AllocateId
            LEFT JOIN (
                select CashpoolCode, COUNT(DISTINCT Vendorcode) as Cnt 
                from `Customer_Payments` 
                WHERE InvoiceStatus = 1
                AND EstPaydate > '{$today}'	
                GROUP BY CashpoolCode
            ) supplier ON supplier.CashpoolCode = p.CashpoolCode
            LEFT JOIN (
                select CashpoolId,COUNT(DISTINCT Vendorcode) as Cnt 
                from  Supplier_Bids 
                WHERE BidStatus >=0 AND BidRate > 0 
                AND CashpoolId IS NOT NULL 
                GROUP BY CashpoolId
            ) offer ON offer.CashpoolId = p.Id
            LEFT JOIN (
                select CashpoolCode,SUM(InvoiceAmount) as amount
                from `Customer_Payments`  
                WHERE InvoiceStatus = 1 and IsIncluded = 1 
                AND EstPaydate > '{$today}'		
                GROUP BY CashpoolCode
            ) ava ON ava.CashpoolCode = p.CashpoolCode
            LEFT JOIN (
                select CashpoolId,SUM(PayAmount) as amount
                from `Customer_PayAwards`  			
                WHERE AwardStatus >= 0 AND AwardDate = '{$today}'
                GROUP BY CashpoolId
            ) award ON award.CashpoolId = p.Id
            WHERE p.MarketStatus >= 0 AND exists(
                            SELECT InvoiceNo FROM `Customer_Payments` m WHERE m.CashpoolCode = p.CashpoolCode
                            AND m.InvoiceStatus = 1 AND m.IsIncluded = 1 AND m.EstPaydate > p.NextPaydate AND m.InvoiceAmount < p.AutoAmount
                        )              
            AND p.NextPaydate > '{$today}'
            ORDER BY p.CreateTime DESC;";

        $this->db = $this->load->database('bird',true);
        $handler = $this->db->query($sql);
        $rs = $handler->result_array();
        $this->data['marks'] = $rs;


        $this->data['title'] = 'Current Dashboard ';
        $this->load->view('supplier/dashboard', $this->data);

    }

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
        $sql = "  select b.Vendorcode,b.BidRate,b.ResultRate,b.MinAmount,
                    CASE WHEN IFNULL(q.CreateTime,'".date("Y-m-d", time())."') < '".date("Y-m-d", time())."' THEN '".date("Y-m-d", time())."' ELSE q.CreateTime END as CreateTime
                from Supplier_Bids b
                left join Supplier_Bid_Queue q on q.Id = b.QueueId
                where b.CashpoolId = {$cashpoolId}
                and b.BidStatus >= 0 and b.BidRate > 0";

        $db = $this->load->database('bird',true);
        $query = $db->query($sql);
        $result = $query->result_array();

        $offers = array();

        foreach ( $result as $val){
            $offers[$val["Vendorcode"]] = array(
                        "total" => 0,
                        "discount" => 0,
                        "clear" => 0 ,
                        "noclear" => 0,
                        "offerAPR" => $val["BidRate"],
                        "getAPR" => $val["ResultRate"],
                        "minPaid" => floatval($val["MinAmount"]),
                        "offerTime" => $val["CreateTime"]
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



        foreach ($offers as $key => &$item) {

            $item["supplier"] = $suppliers[$key];

            foreach ($invoices as $inv) {

                if ($key == $inv["Vendorcode"]) {

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
        $this->load->view('supplier/market_current', $this->data);
    }

    //市场日志
    public function activity(){
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

                $this->load->view('supplier/activity_log', $this->data);
            }


            public function profile() {

                $this->data['title'] = 'User Profile';

                $this->load->view('supplier/profile', $this->data);

                parent::innerLayout();
            }




            public function multipledelete() {
                $ids = $_GET['id'];
                //pr($ids); die;
                if (!$ids) {
                    redirect('supplier/dashboard', 'refresh');
                } else {
                    $idbreak = explode(',', $ids);
                    //pr($idbreak); die;

                    for ($i = 0; $i < count($idbreak); $i++) {
                        if ($idbreak[$i] || $idbreak[$i] != '') {

                            //$id = $this->UploadsupplierModel->getdeleteid($idbreak[$i]);
                            $this->UploadsupplierModel->deleteRecord($idbreak[$i]);
                        }
                    }
                    $this->session->set_flashdata('message', 'Record deleted successfully');
                    redirect('supplier/dashboard', 'refresh');
                }
            }

            public function activate($SupplierId) {

                $this->checkLogin();

                $table = 'supplier_by_customer';
                $data = array('SupplierId' => $SupplierId);
                $emaildata = $this->UniversalModel->getRecords($table, $data);

                $cc = ''; //NUR@site.co.uk;
                //Get Template details
                $tid = 8;
                $resultTemplate = $this->TemplateModel->getTemplateById($tid);
                //pr($resultTemplate);
                //Generate Ramdom String
                $random_string = random_string('unique');
                $verifyurl = base_url() . 'supplier/register/' . urlencode(base64_encode($emaildata[0]['Email'])) . '/' . $random_string;

                //$durl = urlencode(base64_encode($verifyurl));
                //$gurl = urldecode(base64_decode($durl));
                //pr($gurl);die;
                // Replace string
               $mixed_search = array("{firstname}","{text}", "{verifyurl}");
                     $supppliertext='You have invited as a supplier for registration';

                    $mixed_replace = array($emaildata[0]['ContactPerson'],$supppliertext, $verifyurl);
                $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

                $data = array();
                $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                $data['contents'] = $messagebody;
                $body = $this->parser->parse('emailtemplate/registertemplate', $data, TRUE);
                //pr($body);die;
                //Send Email
                $this->SendEmailModel->sendEmail($emaildata[0]['Email'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['ContactPerson'], $cc);

                if ($SupplierId) {
                    $statussql = "UPDATE `supplier_by_customer` SET ActivateStatus='1' where SupplierId='" . $SupplierId . "' ";
                    $query = $this->db->query($statussql);
                    //$result = $query->result_array();
                    $this->session->set_flashdata('activatemessage', 'User Activated For Registration');
                    redirect("supplier/dashboard", "refresh");
                }



                //return true;
            }

            public function invitescustomeres() {

                $UserId = $this->checkLogin();

                $ecurrencysql = "SELECT Email from customer_by_supplier where Email ='" . $this->input->post('emailaddress') . "'";
                $ecurrencystatus = $this->db->query($ecurrencysql);
                $ecurrencydata = $ecurrencystatus->result_array();
                $currency = $ecurrencydata[0]['Email'];
                if ($currency == $this->input->post('emailaddress')) {
                    echo "Email exixt";
                } else {
                    $data = array();
                    $data['SupplierId'] = $UserId;
                    $data['CompanyName'] = $this->input->post('companycname');
                    $data['Email'] = $this->input->post('emailaddress');
                    $data['Phone'] = $this->input->post('phones');
                    $data['contact'] = $this->input->post('contact');
                    $data['position'] = $this->input->post('position');
                    $data['InviteStatus'] = '0';
                    $data['RegisterStatus'] = '0';
                    $data['AddedDate'] = $this->currentDateTime;
                    $this->db->insert('customer_by_supplier', $data);
                    $insertid = $this->db->insert_id();
                }


                if ($data['Email']) {


                    $data = array('SupplierId' => $data['SupplierId']);
                    $emaildata = $this->UniversalModel->getRecords('customer_by_supplier', $data);

                    $cc = ''; //earlypay@site.com;
                    //Get Template details

                    $tid = 8;

                    $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                    $random_string = random_string('unique');

                    $verifyurl = base_url() . 'customer/register/' . $this->base64url_encode($emaildata[0]['CustomerId']) . '-' . $this->base64url_encode($data['SupplierId']) . '-' . $this->base64url_encode($emaildata[0]['CompanyName']) . '-' . $this->base64url_encode($emaildata[0]['Email']) . '/' . $random_string;
                    $mixed_search = array("{firstname}", "{verifyurl}");
                    $mixed_replace = array($emaildata[0]['CompanyName'], $verifyurl);
                    $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);
                    $data = array();
                    $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                    $data['contents'] = $messagebody;
                    $body = $this->parser->parse('emailtemplate/registertemplate', $data, TRUE);
        //            pr($body);
        //            die;

                    $this->SendEmailModel->sendEmail($emaildata[0]['Email'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['CompanyName'], $cc);

                    if ($emaildata[0]['Email']) {

                        $statussql = "UPDATE `customer_by_supplier` "
                                . "SET  InviteStatus='1' where SupplierId='" . $emaildata[0]['SupplierId'] . "'"
                                . "and CustomerId='" . $insertid . "'";
                        $query = $this->db->query($statussql);
                    }
                }
            }

            public function activatesupplierlist($CustomerId) {

                $UserId = $this->checkLogin();

                $customerbytable = 'customer_by_supplier';

                $data = array('CustomerId' => $CustomerId);

                $emaildata = $this->UniversalModel->getRecords($customerbytable, $data);
                //pr($emaildata);


                $cc = ''; //NUR@site.co.uk;
                //Get Template details

                $tid = 8;

                $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                $random_string = random_string('unique');

                $verifyurl = base_url() . 'customer/register/' . $this->base64url_encode($emaildata[0]['CustomerId']) . '-' . $this->base64url_encode($this->data['userdata'][0]['UserId']) . '-' . $this->base64url_encode($emaildata[0]['CompanyName']) . '-' . $this->base64url_encode($emaildata[0]['Email']) . '/' . $random_string;

                $mixed_search = array("{firstname}", "{verifyurl}");

                $mixed_replace = array($emaildata[0]['CompanyName'], $verifyurl);

                $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

                $data = array();
                $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];

                $data['contents'] = $messagebody;

                $body = $this->parser->parse('emailtemplate/registertemplate', $data, TRUE);

                //pr($body); die;
                //Send Email
                $this->SendEmailModel->sendEmail($emaildata[0]['Email'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['CompanyName'], $cc);
                $this->session->set_flashdata('sentmail', 'Email sent');
                redirect("supplier/invitecustomer", "refresh");
                if ($CustomerId) {

                    $statussql = "UPDATE `customer_by_supplier` SET InviteStatus='1' where  CustomerId='" . $CustomerId . "' ";

                    $query = $this->db->query($statussql);

                    $result = $query->result_array();

                    $this->session->set_flashdata('activatemessage', 'User Activated For Registration');

                    redirect("supplier/invitecustomer", "refresh");
                }
            }


            private function get_currency_list(){

                $sql = "SELECT Id,CurrencyName,CurrencySign FROM `Base_Currency` ;";
                $handler = $this->db->query($sql);
                $result = $handler->result_array();

                $list = array();
                foreach($result as $v)
                {
                    $list[$v['Id']] = array(
                        'CurrencyName' => $v['CurrencyName'],
                        'CurrencySign' => $v['CurrencySign']
                    );
                }

                return $list;

            }

            private function get_award_payment($paymentId,$awarddate,$vendorcode){

                $sql = "
                    SELECT A.PaymentId,A.CustomerId,P.Vendorcode,P.InvoiceNo,P.EstPaydate,B.BidRate,P.InvoiceAmount,A.PayDpe,A.PayAmount,P.InvoiceAmount - A.PayAmount as Discount
                    FROM `Customer_PayAwards` A
                    INNER JOIN `Customer_Payments` P ON P.Id = A.InvoiceId AND P.Vendorcode = '{$vendorcode}'
                    INNER JOIN `Supplier_Bids` B ON B.Id = A.BidId
                    WHERE A.PaymentId = '{$paymentId}' AND A.AwardDate = '{$awarddate}';
                    " ;

                $handler = $this->db->query($sql);
                $result = $handler->result_array();

                return $result;
            }


            public function historyplan() {

               $UserId =  $this->checkLogin();


               /*
               //查询当前的 PLAN 清单
               $sql =      "SELECT p.PlanId,w.Vendorcode,count(w.InvoiceId) as TotalInvoice,sum(w.InvAmount) as TotalAmount , sum(dpe) as TotalDPE , sum(AccrualAmount) as TotalDiscount
                            FROM `proposal_report_by_admin` p
                            INNER JOIN `winners` w ON w.PlanId = p.PlanId
                            INNER JOIN `supplier_by_customer` s ON s.CustomerId = w.CustomerId AND s.Vendorcode = w.Vendorcode
                            WHERE s.Email = '".$this->data['userdata'][0]['EmailAddress']."'
                            GROUP BY w.PlanId,w.Vendorcode;
                            ";
               */
       
       $currency = $this->get_currency_list();
       
       $sql = "
           SELECT S.CompanyName,S.CompanyEnName,A.PaymentId,A.CurrencyId,C.Vendorcode,B.BidRate,A.AwardDate,A.PayDate,sum(P.InvoiceAmount) as InvAmount,sum(A.PayAmount) as PayAmount,sum(A.PayDpe) as PayDpe
           FROM `Customer_PayAwards` A
           INNER JOIN `Customer_Payments` P ON P.Id = A.InvoiceId
           INNER JOIN `Base_Companys` S ON S.Id = A.CustomerId
           INNER JOIN `Customer_Suppliers` C ON C.CustomerId = A.CustomerId and P.Vendorcode = C.Vendorcode
           INNER JOIN `Customer_Supplier_Users` U ON U.SupplierId = C.Id AND U.UserStatus = 1
           INNER JOIN `Supplier_Bids` B ON B.Id = A.BidId
           WHERE U.UserEmail = '".$this->data['userdata'][0]['EmailAddress']."'
           GROUP BY S.CompanyName,S.CompanyEnName,A.PaymentId,A.CurrencyId,C.Vendorcode,B.BidRate,A.AwardDate DESC,A.PayDate DESC;
           ";
                   
           $handler = $this->db->query($sql);
           $result = $handler->result_array();
                      
           $list = array();
                      
           foreach ( $result as $k ){
               
               $avg_discount = 0 ;
               $avg_apr = 0 ;
               $avg_dpe = 0 ;
               
              
               $items = $this->get_award_payment($k['PaymentId'], $k['AwardDate'], $k['Vendorcode']);
               
               foreach ( $items as $i){
                   $avg_discount += round(  ($i['InvoiceAmount']- $i['PayAmount'])/$k['InvAmount'] , 2 );                   
               }
                             
               $list[] = array(    
                   'PaymentId' => $k['PaymentId'],
                   'AwardDate' => $k['AwardDate'],
                   'CompanyName' => $k['CompanyName'],
                   'CompanyEnName' => $k['CompanyEnName'],
                   'PaymentId' => $k['PaymentId'],
                   'PayDate' => $k['PayDate'],
                   'Vendorcode' => $k['Vendorcode'],
                   'BidRate' => $k['BidRate'],
                   'CurrencyName' => $currency[$k['CurrencyId']]['CurrencyName'],
                   'CurrencySign' => $currency[$k['CurrencyId']]['CurrencySign'],
                   'InvAmount' => $k['InvAmount'],
                   'PayAmount' => $k['PayAmount'],
                   'Discount' => $k['InvAmount'] - $k['PayAmount'],
                   'InvoiceCnt' => count($items),
                   'AvgDiscount' => $avg_discount*100,                   
                   'AvgDPE' => round($k['PayDpe']*1.0/count($items),1)
               ) ;
           }
       
        $this->data['result'] = $list;
       
        $this->data['SupplierId'] = $UserId;      
		$this->data['title'] = 'History Plans';
		$this->data['uri'] = 'supplier/historyplan';
        $this->right = 'supplier/historyplan';

        parent::innerLayout();
    }

    public function invitecustomer() {

        $this->checkLogin();

        $table = 'site_users';

        $data = array('UserId' => $this->session->userdata('UserId'));

        $this->data['userdata'] = $this->UniversalModel->getRecords($table, $data);
        $suppliertable = 'customer_by_supplier';

        //$customerdata = array('Email' => $this->data['userdata'][0]['EmailAddress']);

        $customerdata = array('SupplierId' => $this->session->userdata('UserId'));

        $this->data['customerdata'] = $this->UniversalModel->getRecords($suppliertable, $customerdata);

		$this->data['title'] = 'Invite Customer';
		$this->data['uri'] = 'supplier/invitecustomer';
        $this->right = 'supplier/invitecustomer';

        parent::innerLayout();
    }

    public function settings() {
        
        $UserId = $this->checkLogin();

        $tables = 'apr_supplier_setting';

        $data = array('SupplierId' => $UserId);

        $this->data['customerdata'] = $this->UniversalModel->getRecords($tables, $data);
        
        /*
        $sql = "SELECT DISTINCT CustomerId, s.CompanyName ,s.EmailAddress, c.Email
                FROM `supplier_by_customer` c
                INNER JOIN  `site_users` s ON s.UserId=c.CustomerId
                WHERE c.Email = '".$this->data['userdata'][0]['EmailAddress']."' ;
            " ;

        $handler = $this->db->query($sql);
        
        $this->data['customers'] = $handler->result_array();
        */
        
        
        $sql = "select c.*
                from apr_currency_by_admin c
                left join apr_supplier_setting s on s.CurrencyId = c.CurrencyId and SupplierId = '{$UserId}' 
                where s.SettingId is null;
                ";
        
        $handler = $this->db->query($sql);
        
        $this->data['results'] = $handler->result_array();
        //$this->data['results'] = $this->CurrencylistModel->getCurrency();
        
        $config = array(  
           
            array(
                'field' => 'CurrencyName',
                'label' => 'CurrencyName',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'CapitalCost',
                'label' => 'CapitalCost',
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
            echo json_encode($errors, JSON_FORCE_OBJECT);
            
        } else {

            $data = array();


            $data['CurrencyId'] = $this->input->post('CurrencyId');
            $data['SupplierId'] = $this->session->userdata('UserId');
            $data['CurrencyName'] = $this->input->post('CurrencyName');
            $data['CapitalCost'] = $this->input->post('CapitalCost');
            $data['AddedDate'] = $this->currentDateTime;
            $result = $this->db->insert('apr_supplier_setting', $data);
            $this->session->set_flashdata('settingsuccess', 'Your supplier Setting is added successfully');
            redirect("supplier/settings", "refresh");
        }


		$this->data['title'] = 'Settings';
		$this->data['uri'] = 'supplier/settings';
        $this->right = 'supplier/settings';
        parent::innerLayout();
    }

    public function aprpreferencesdelete($id = NULL) {
        
        $this->checkLogin();
        $this->db->where('SettingId', $id);
        $this->db->delete('apr_supplier_setting');
        //$this->session->set_flashdata('settingdelete', 'Your Setting delete successfully');
        redirect("supplier/settings", "refresh");
    }

    public function addsettings() {
        
        $UserId = $this->checkLogin();

        $tables = 'apr_supplier_setting';

        $data = array('SupplierId' => $UserId);

        $this->data['customerdata'] = $this->UniversalModel->getRecords($tables, $data);
    
        $this->data['results'] = $this->CurrencylistModel->getCurrency();
        
        $this->_validate();

        $data = array();
          
        $data['CurrencyId'] = $this->input->post('CurrencyId');
        $data['SupplierId'] = $this->session->userdata('UserId');
        $data['CurrencyName'] = $this->input->post('CurrencyName');
        $data['CapitalCost'] = $this->input->post('CapitalCost');
        $data['AddedDate'] = $this->currentDateTime;
        $result = $this->db->insert('apr_supplier_setting', $data);
        $sucess["msg"] = 'Your supplier Setting is added successfully';
        //$this->session->set_flashdata('settingsuccess', 'Your supplier Setting is added successfully');
        echo json_encode(array("status" => TRUE));
       
    }
	
	 private function _validate() {
	     
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        $ecurrencysql = "SELECT CurrencyName  from apr_supplier_setting 
            where CurrencyName ='" . $this->input->post('CurrencyName') . "'
            AND SupplierId = '".$this->session->userdata('UserId')."'
                ";
        
        $ecurrencystatus = $this->db->query($ecurrencysql);
        $ecurrencydata = $ecurrencystatus->result_array();
        $currency = $ecurrencydata[0]['CurrencyName'];
		
		 if ($this->input->post('CurrencyName') == '' && empty($this->input->post('CurrencyName'))) {
            $data['inputerror'][] = 'CurrencyName';
            $data['error_string'][] = 'CurrencyName is required';
            $data['status'] = FALSE;
        }
        if ($this->input->post('CurrencyName') == $currency && $this->input->post('CurrencyName') != '') {
            $data['inputerror'][] = 'CurrencyName';
            $data['error_string'][] = 'CurrencyName Already Exists';
            $data['status'] = FALSE;
        }


        if ($this->input->post('CapitalCost') == '' && empty($this->input->post('CapitalCost'))) {
            $data['inputerror'][] = 'CapitalCost';
            $data['error_string'][] = 'CapitalCost is required';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function settingsedit() {

            $this->checkLogin();
            $this->_validateedit();	
		    $data = array();
            $data['SettingId'] = $this->input->post('SettingId');
            $data['CurrencyId'] = $this->input->post('EditCurrencyid');
            $data['SupplierId'] = $this->session->userdata('UserId');
            $data['CurrencyName'] = $this->input->post('EditCurrencyName');
            $data['CapitalCost'] = $this->input->post('EditCapitalCost');
            $data['AddedDate'] = $this->currentDateTime;
            $this->db->where('SettingId', $data['SettingId']);
            $result = $this->db->update('apr_supplier_setting', $data);
           // $this->session->set_flashdata('settingupdate', 'Your supplier Setting is update successfully');
             echo json_encode(array("status" => TRUE));			
       
    }
	
	  private function _validateedit() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('EditCapitalCost') == '') {
            $data['inputerror'][] = 'EditCapitalCost';
            $data['error_string'][] = 'Capital Cost is required';
            $data['status'] = FALSE;
        }

      
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

  	 public function settingcompany() {
  	     
        $UserId = $this->checkLogin();
        
		$this->data['title'] = 'Settings';		

        $companyname = $this->input->post('companyname');           
        
        $emaildata = $this->data['userdata'];
        
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
                $updateusersql = "UPDATE `site_users` SET NewCompanyName='".$companyname."'  where UserId='". $UserId."' ";
                $cnquery = $this->db->query($updateusersql);
            }
		}
		else{
            echo 'Company Name Same';       
       }
      
    }
    
    
    public function settinguser() {
        
        $UserId = $this->checkLogin();
        
		$this->data['title'] = 'Settings';
		$this->_validatescontact();
		
        //$UserId = $this->session->userdata['UserId'];       
        $contactemail = $this->input->post('contactemail');
        $contactname = $this->input->post('contactname');
        $contactposition = $this->input->post('contactposition');
        $contactphone = $this->input->post('contactphone');		      

        if ($contactname) {
            
            $updateusersql = "UPDATE `site_users` SET  EmailAddress='$contactemail',ContactName='$contactname',Position='$contactposition',Cellphone='$contactphone' "
                    . "where UserId='" . $this->session->userdata('UserId') . "' ";
            $cnquery = $this->db->query($updateusersql);
            //$this->session->set_flashdata('contactsettings', 'Contact Settings Update successfully');
			 echo json_encode(array("status" => TRUE));         
        }
    }
	
	 private function _validatescontact() {
		  
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

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function register($userdata = NULL) {

       // $this->isLogin();
        
        $explodedata = array();
        $this->data['uri'] = 'supplier/register/'.$userdata;
        $explodedata = explode('-', $userdata);

        //pr($explodedata); 
        //echo $this->base64url_decode($explodedata[0]);die;
        //echo $this->base64url_decode($explodedata[1]);

        
        if (!empty($explodedata)) {
            $useremail = $this->base64url_decode($explodedata[3]);    
            $supplierid = $this->base64url_decode($explodedata[0]);        
        }
        
        //判断是否已经注册
        $userresult = $this->UniversalModel->checkRecord('site_users', array('Role'=> 'supplier','EmailAddress' => $useremail ) ); //die;
        
        if (isset($userresult) && $userresult == 1) {
            redirect(site_url(), "refresh");
        }
                        
        
        $supplier = $this->UniversalModel->getRecords('supplier_by_customer', array('SupplierId' => $supplierid) ); //die;
        //pr($userresult); die;

        //若没有查到这个记录则返回
        if (!isset($supplier) || empty($supplier) || count($supplier)<=0) {
            redirect(site_url(), "refresh");
        }
        
        
        $table = 'site_users';

        $this->data['title'] = 'supplier Registration';
        $this->data['CustomerIdForSupplier'] = $this->base64url_decode($explodedata[1]);
        $this->data['CompanyName'] = $this->base64url_decode($explodedata[2]);
        $this->data['useremail'] = $useremail;
        $this->data['ContactName'] = $supplier[0]['ContactPerson'];
        $this->data['Position'] = $supplier[0]['Position'];
        $this->data['Telephone'] = $supplier[0]['Phone'];
        

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
                'rules' => 'trim|required|min_length[6]|max_length[15]|xss_clean'
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
            /*
            array(
                'field' => 'Cellphone',
                'label' => 'Cellphone',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            */
            array(
                'field' => 'captcha',
                'label' => 'Confirm security code',
                'rules' => 'trim|required|xss_clean|callback_captcha_check'
            )
        );
        
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            //echo 'Test2'; die;
            //$this->middle = 'supplier/register';
			
        } else {

            //echo 'Test3'; die;
            if ($this->input->post('Role') == 'supplier') {
                $this->middle = 'supplier/register';
                //echo 'Test4'; die;
                $columns = array();
                $columns['Role'] = $this->input->post('Role');
                $columns['CustomerIdForSupplier'] = $this->input->post('CustomerIdForSupplier');
                $columns['EmailAddress'] = $this->input->post('EmailAddress');
                $columns['Password'] = md5($this->input->post('Password'));
                $columns['CompanyName'] = $this->input->post('CompanyName');
                $columns['Region'] = $this->input->post('Region');
                $columns['ContactName'] = $this->input->post('ContactName');
                $columns['Position'] = $this->input->post('Position');
                $columns['Telephone'] = $this->input->post('Telephone');
                $columns['Cellphone'] = $this->input->post('Cellphone');
                $columns['RegisterStatus'] = '1';
                $columns['Language'] = $this->input->post('Language');
                $columns['RegisterDate'] = $this->currentDateTime;

                //pr($columns); die;

                $UserId = $this->UniversalModel->save($table, $columns, NULL, NULL);

                //$statussql = "UPDATE `supplier_by_customer` SET RegisterStatus='1' where SupplierId='".$this->base64url_decode($explodedata[0])."' ";

                $statussql = "UPDATE `supplier_by_customer` SET RegisterStatus='1' where Email='" . $useremail . "' ";
                $query = $this->db->query($statussql);

                $vendorstatussql = "UPDATE `payable_supplier_by_customer` SET RegisterStatus='1' where Vendorcode='" . $userresult[0]['Vendorcode'] . "' ";
                $vendorquery = $this->db->query($vendorstatussql);

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
                    if ($this->session->userdata("Role") == 'supplier') {
                        redirect("supplier/plans", "refresh");
                    }
                }
            }
        }


        // County Listing
        $this->data['datacounty'] = $this->UniversalModel->getCounty();
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
        
        $this->data['datalanguage'] = array(
            'chinese' => $this->lang->line('Language')['chinese'] ,
            'english' => $this->lang->line('Language')['english']
        );
        
        
        $cap = create_captcha($vals);
        $this->session->set_userdata('captchaword', $cap['word']);
        $this->data['captchaImage'] = $cap['image'];

        //$this->middle = 'supplier/register';
        //parent::layout();
		$this->data['title'] = 'Register';		
        $this->load->view('supplier/register', $this->data);
    }

    
    
    //查询买家的支付计划信息
    private function get_current_cashpool_byid($cashpoolId)
    {
        $sql = "SELECT B.CompanyName,C.Id,C.MarketStatus,C.CustomerId,C.CurrencyId,C.CurrencySign,C.CurrencyName,C.MiniAPR,C.DesiredAPR,C.CashAmount,C.ReservePercent,P.Id as PayId ,P.PayAmount as SetAmount,P.AvaAmount as PayAmount,P.PayDate
                FROM `Customer_CashPool_Setting` C
                INNER JOIN `Base_Companys` B ON B.Id = C.CustomerId 
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
        AND EstPaydate > '".date('Y-m-d',time())."'
        ORDER BY CreateTime DESC;";
        
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
    
        return $data;
    }
    
    public function marketdetail($marketcode){
        
        $UserId = $this->checkLogin();
        
        $_param = $marketcode;
        
        $_params = explode("-", $_param);
        
        $cashpoolId = $this->base64url_decode($_params[0]);
        $vendorcode = $this->base64url_decode($_params[1]);
                
        $CashPool = $this->get_current_cashpool_byid($cashpoolId);
        $CurBid = $this->get_supplier_bid($cashpoolId,$vendorcode);
        
        $data = $this->get_supplier_payment($CashPool['CustomerId'],$CashPool['CurrencyId'],$vendorcode);        

        
        $export = $this->uri->segment(4);
        
        if( isset($export) && $export === 'export'){
            
            $this->load->library('PHPExcel');
            $header = array(
                                        
                    array(
                        'datakey' => 'InvoiceNo','colheader' => '发票号','colwidth' => '16'
                    ),
                    array(
                        'datakey' => 'EstPaydate','colheader' => '原付款日','colwidth' => '14'
                    ),
                    array(
                        'datakey' => 'InvoiceAmount','colheader' => '发票金额'
                    )              
                );
            
            self::export_xls($data,$header);
            exit;
        }
        
        //查看是否有设定早付日期
        if( isset($CashPool['PayDate']) && $CashPool['PayDate'] > date('Y-m-d',time()) )
        {
            foreach($data as &$v){
                
                $v['dpe'] =  (strtotime($v['EstPaydate'])  - strtotime($CashPool['PayDate'])) / 86400;            
                $v['Discount'] = round($v['InvoiceAmount']/365*$CurBid['BidRate']/100 *  $v['dpe'] ,2);
                $v['DiscountRate'] = round($v['Discount']/$v['InvoiceAmount'],4)*100 ;
            }
        
        }
        
        $this->data['ajax_set_bid'] = 'supplier/ajax_supplier_set_bid';
        $this->data['marketbid'] = $CurBid;
        $this->data['vendorcode'] = $vendorcode ;
        $this->data['result'] = $data;
        $this->data['market'] = $CashPool;
        $this->data['title'] = 'Market Detail';
        $this->data['page'] = 'marketdetail';
        $this->data['uri'] = 'supplier/marketdetail/'.$marketcode;
        $this->right = 'supplier/marketdetail';

        parent::innerLayout();
         
        
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
        
    public function ajax_supplier_set_bid(){
    
        header('Content-type: text/json');
        $exec_sql = array();
        $result = array('ret' => 0 );       
        
        $cashpoolId = $this->input->post('cashpool') ;
        //指定给某供应商        
        $data = $this->input->post('form_data');
        
        $_vendorcode = '';
        $_bidrate = 0 ;
        $_bidtype = 'apr';
        $_minamount = 0;
        
        foreach($data as $v){
            if ($v['name'] === 'vendorcode')
                $_vendorcode = $v['value'];
            if ($v['name'] === 'bidrate')
                $_bidrate = $v['value'];
            if ($v['name'] === 'bidtype')
                $_bidtype = $v['value'];
            if ($v['name'] === 'minamount')
                $_minamount = $v['value'];
        }
                
        $CashPool = $this->get_current_cashpool_byid($cashpoolId);
        
        $sql = "update `Supplier_Bids` set BidStatus = 2
                WHERE  BidStatus = 1
                AND CashPoolId ='{$CashPool['Id']}'                
                AND Vendorcode ='{$_vendorcode}'
                ;" ;
        
        $exec_sql[] = $sql ;
        
        $sql = "INSERT INTO `Supplier_Bids`\n".
            "(`CreateUser`,`CustomerId`,`CashPoolId`,`Vendorcode`,`BidType`,`BidRate`,`MinAmount`)\n".
            "VALUES".
            "('".$this->session->userdata('EmailAddress')."',".            
            "'".$CashPool['CustomerId']."',".
            "'".$CashPool['Id']."',".
            "'{$_vendorcode}',".
            "'{$_bidtype}',".
            "'{$_bidrate}',".
            "'{$_minamount}');";

        $exec_sql[] = $sql ;
              
        if($this->db_commit($exec_sql)){
            $result = array('ret' => 1 );
        }else{
            $result = array('ret' => -1 , 'msg' => '更新数据异常');
        }        
        
        echo json_encode($result);
        
    }
    
    private function get_cashpool_schedule($cashpoolId)
    {
        $sql = "SELECT * FROM `Customer_CashPool_PaySchedule` 
                WHERE CashPoolId = '{$cashpoolId}' 
                AND PayDate > '".date('Y-m-d',time())."'
                ORDER BY PayDate 
                LIMIT 1;";
            
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
                
        return isset($data) ? $data[0] : null;
    }
    
    public function plans() {

        $UserId = $this->checkLogin();

        //$this->data['timesettings'] = $this->ConfigurationModel->getConfiguration();
       
        
        //查询当前的 Market 清单
            $sql = "
                    SELECT C.Id,B.CompanyName,S.CustomerId,S.Vendorcode,C.CurrencyId,C.CurrencySign,C.CurrencyName,InvCount,InvAmount
                    FROM Customer_Supplier_Users U
                    INNER JOIN Customer_Suppliers S ON S.Id = U.SupplierId
                    INNER JOIN Base_Companys B ON B.Id = S.CustomerId
                    INNER JOIN Customer_CashPool_Setting C ON C.CustomerId = S.CustomerId                    
                    INNER JOIN (
                          SELECT CustomerId , CurrencyId , Vendorcode , count(Id) as InvCount ,sum(InvoiceAmount) as InvAmount
                          FROM Customer_Payments C
                          WHERE InvoiceStatus = 1
                          GROUP BY CustomerId , CurrencyId , Vendorcode
                    ) L ON L.CustomerId = S.CustomerId AND L.Vendorcode = S.Vendorcode AND L.CurrencyId = C.CurrencyId
                    WHERE U.UserStatus = 1 AND U.UserEmail = '".$this->data['userdata'][0]['EmailAddress']."'
                    ORDER BY L.InvAmount DESC 
                    ;";
            
            $handler = $this->db->query($sql);
            $data = $handler->result_array();
            
            foreach( $data as &$v ){
                
                $_schedule = $this->get_cashpool_schedule($v['Id']);
                
                $v['EstPayDate'] = isset($_schedule) ? $_schedule['PayDate'] : null ;
                
            }
            
            
            $this->data['result'] = $data;
            $this->data['planCount'] = count($data);
            
        //查询winner数量        
            $winsql = "
                    SELECT count(p.PlanId) as winCount,sum(w.InvAmount) as winAmount
                    FROM `customer_early_pay_plans` p
                    INNER JOIN (
                        SELECT w.PlanId , sum(w.InvAmount) InvAmount 
                        FROM `winners` w
                        INNER JOIN `supplier_by_customer` s ON s.CustomerId = w.CustomerId AND s.Vendorcode = w.Vendorcode
                        WHERE s.Email = '".$this->data['userdata'][0]['EmailAddress']."' 
                    ) w ON w.PlanId = p.PlanId
                    WHERE p.PlanStatus NOT IN ('ongoing','finished','canceled');";
            
            $handler = $this->db->query($winsql);
            $windata = $handler->result_array();
            
            $this->data['winCount'] = $windata[0]['winCount'];
            $this->data['winAmount'] = $windata[0]['winAmount'];
            
         //查询customer数量
            $custsql = "
                        SELECT count(distinct w.CustomerId) as count
                        FROM `winners` w
                        INNER JOIN `supplier_by_customer` s ON s.CustomerId = w.CustomerId AND s.Vendorcode = w.Vendorcode
                        WHERE s.Email = '".$this->data['userdata'][0]['EmailAddress']."' AND w.PlanId IN(
                            SELECT PlanId FROM `customer_early_pay_plans`  WHERE PlanStatus NOT IN ('ongoing','finished','canceled')
                        )
                        GROUP BY w.CustomerId    
                        ;";
            
            $handler = $this->db->query($custsql);
            $custdata = $handler->result_array();
            
            $this->data['custCount'] = $custdata[0]['count'];            
            
            
        $this->data['title'] = 'Plans';
        $this->data['uri'] = 'supplier/plans';
        $this->right = 'supplier/plans';

        parent::innerLayout();
    }

    public function changeplandetail() {

        $encodeplanid = $this->input->post('planid');
        $explodedata = explode("-", $encodeplanid);
        $planid = $this->base64url_decode($explodedata[0]);
        $vendorcode = $this->base64url_decode($explodedata[1]);
        
        $UserId = $this->checkLogin();
                                
        $this->data['planid'] = $planid;
        $this->data['vendorcode'] = $vendorcode;
        $suppliercountsql = "SELECT SUM(`InvAmount`) as InvAmount FROM `payable_supplier_by_customer` WHERE `PlanId` ='" . $planid . "' AND `Vendorcode`='" . $vendorcode . "' ";
        $supplierquery = $this->db->query($suppliercountsql);
        $suppliercount = $supplierquery->result_array();
        $this->data['InvAmount'] = round($suppliercount[0]['InvAmount'], 2);
        
        if ($planid) {
            
            $plantable = 'customer_early_pay_plans';
            $plandata = array('PlanId' => $planid);
            $this->data['plandata'] = $this->UniversalModel->getRecords($plantable, $plandata);
            //$this->data['Vendorcode'] = $this->data['plandata'][0]['Vendorcode'];
        }
        
        $suppliertable = 'supplier_by_customer';
        $customerdata = array('Email' => $this->session->userdata('EmailAddress'));
        $customerdata = $this->UniversalModel->getRecords($suppliertable, $customerdata);
        
        //pr($customerdata);
        $config = array(
            array(
                'field' => 'BidType',
                'label' => 'BidType',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'BidRate',
                'label' => 'BidRate',
                'rules' => 'trim|required|numeric|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);

        $supplierdatasql = "SELECT * FROM `payable_supplier_by_customer`"
                . " WHERE `PlanId` ='" . $planid . "' AND `Vendorcode`='" . $vendorcode . "' ";

        $supplierdataquery = $this->db->query($supplierdatasql);
        $supplierdata = $supplierdataquery->result_array();
        $this->data['supplierdata'] = $supplierdataquery->result_array();

        $planbidssql = "SELECT * FROM `plan_bids` WHERE `PlanId` ='" . $planid . "' AND `Vendorcode`='" . $vendorcode . "' ";

        $planbidsdataquery = $this->db->query($planbidssql);
        $this->data['supplierbidsdata'] = $planbidsdataquery->result_array();
        //pr($this->data['supplierbidsdata']);
        $this->data['BidId'] = $this->data['supplierbidsdata'][0]['BidId'];
        $this->data['BidRate'] = $this->data['supplierbidsdata'][0]['BidRate'];
        $this->data['BidType'] = $this->data['supplierbidsdata'][0]['BidType'];


        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $table = 'plan_bids';
            $columns = array();
            $columns['PlanId'] = $planid;
            $columns['CustomerId'] = $supplierdata[0]['CustomerId'];
            $columns['SupplierId'] = $this->session->userdata('UserId');
            $columns['supplier'] = $supplierdata[0]['supplier'];
            $columns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
            $columns['Email'] = $this->session->userdata('EmailAddress');
            $columns['EnableBid'] = $this->input->post('EnableBid');
            $columns['BidType'] = $this->input->post('BidType');
            $columns['BidRate'] = $this->input->post('BidRate');
            
			$columns['AddedDate'] = $this->currentDateTime;
            
			$pcolumns['PlanId'] = $planid;
            $pcolumns['CustomerId'] = $supplierdata[0]['CustomerId'];
            $pcolumns['SupplierId'] = $this->session->userdata('UserId');
            $pcolumns['supplier'] = $supplierdata[0]['supplier'];
            $pcolumns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
            $pcolumns['Email'] = $this->session->userdata('EmailAddress');

            //echo $pcolumns['BidType']  = $this->input->post('BidType');
            //echo $pcolumns['BidRate']  = $this->input->post('BidRate');

            $result = $this->UniversalModel->checkRecord($table, $pcolumns);
            $getbidid = $this->UniversalModel->getRecords($table, $pcolumns);
            //pr($getbidid);

            $bidid = $getbidid[0]['BidId'];

            if ($result) {

                $bcolumns['CustomerId'] = $supplierdata[0]['CustomerId'];
                $bcolumns['SupplierId'] = $this->session->userdata('UserId');
                $bcolumns['supplier'] = $supplierdata[0]['supplier'];
                $bcolumns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
                $bcolumns['Email'] = $this->session->userdata('EmailAddress');
                $bcolumns['EnableBid'] = $this->input->post('EnableBid');
                $bcolumns['BidType'] = $this->input->post('BidType');
                $bcolumns['BidRate'] = $this->input->post('BidRate');

                $where = 'BidId';
                $this->UniversalModel->save($table, $bcolumns, $where, $bidid);
                $this->session->set_flashdata('bidmessage', ' submitted successfully');
                //redirect('supplier//'.$encodeplanid, 'refresh');
                
            } else {
                
                $this->UniversalModel->save($table, $columns, NULL, NULL);
                
                
                /*
                $admintable = 'admin_bak';
                $admindata = $this->UniversalModel->getRecords($admintable);		
                
                $planbidtable = 'plan_bids';
                $data = array('SupplierId' => $this->session->userdata('UserId'));
                $plansdata = $this->UniversalModel->getRecords($planbidtable, $data);
                
                $table = 'site_users';                   
                $where = array('UserId' => $plansdata[0]['CustomerId']);				  
                $emaildata = $this->UniversalModel->getRecords($table, $where);
                $swhere = array('EmailAddress' => $plansdata[0]['Email']);				  
                $supplieremaildata = $this->UniversalModel->getRecords($table, $swhere);				  
                   //$emaildata[0]['siturl'] = site_url('admin_bak');
				  
		        if($emaildata){
    		       
    		 	    $tid = 17;
                    $resultTemplate = $this->TemplateModel->getTemplateById($tid);
    
                    // Replace string
                    $mixed_search = array("{firstname}","{suppliername}","{planid}");
                    $mixed_replace = array($emaildata[0]['ContactName'],$supplieremaildata[0]['ContactName'],$plansdata[0]['PlanId']);
                    $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);
    
                    $data = array();
                    $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                    $data['contents'] = $messagebody;
                    $body = $this->parser->parse('emailtemplate/bidsubmittemplate', $data, TRUE);
    
                   //$body; 
    				$return = $this->SendEmailModel->sendEmail($emaildata[0]['EmailAddress'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['CompanyName']);
				}
				
				if($admindata){
					$tid = 17;
                    $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                    // Replace string
                    $mixed_search = array("{firstname}","{suppliername}","{planid}");
                    $mixed_replace = array($admindata[0]['Name'],$supplieremaildata[0]['ContactName'],$plansdata[0]['PlanId']);
                    $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);
    
                    $data = array();
                    $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                    $data['contents'] = $messagebody;
                    $body = $this->parser->parse('emailtemplate/bidsubmittemplate', $data, TRUE);
    
                  //$body;
    				$return = $this->SendEmailModel->sendEmail($admindata[0]['Email'], $resultTemplate[0]['Subject'], $body, $admindata[0]['Name']);
    					
				}
				*/
				//pr($columns);
                //$this->UniversalModel->save($table, $columns, NULL, NULL);
                $this->session->set_flashdata('bidmessage', '您的竞价已经成功提交');
                redirect('supplier/plandetail/'.$encodeplanid, 'refresh');
            }
        }
    }
    
    public function customer_detail($customer){
        
        
        $UserId = $this->checkLogin();
        
        //echo $planid;
        $param = $customer;
        $explodedata = explode("-", $customer);
        
        $customerid = $this->base64url_decode($explodedata[0]);
        $useremail = $this->base64url_decode($explodedata[1]);
        
        
        
        if($useremail == $this->data['userdata'][0]['EmailAddress'])
        {
            
            $sql = "SELECT * FROM `site_users` where UserId = '{$customerid}'";
            
            $handler = $this->db->query($sql);
            $rst = $handler->result_array();
            $this->data['CustomerName'] = $rst[0]['CompanyName'];
            
            
            $sql = "SELECT c.*
                FROM `supplier_by_customer` c                
                WHERE ActivateStatus > -1 AND c.CustomerId = '{$customerid}' AND c.Email = '{$useremail}'
                Order by SupplierId desc;
            " ;
                        
            $handler = $this->db->query($sql);
            $this->data['suppliers'] = $handler->result_array();
                
            $this->data['uri'] = 'supplier/customer_detail/'.$param;
            
            $this->right = 'supplier/customer_detail';
            
            parent::innerLayout();
            
        }else{
            
            redirect('supplier/customers','refresh');
        }
        
        
    }

    public function plandetail($planid) {

        //echo $planid;
        $encodeplanid = $planid;
        $explodedata = explode("-", $planid);

        $planid = $this->base64url_decode($explodedata[0]);
        $vendorcode = $this->base64url_decode($explodedata[1]);
        
        
        $this->data['explanid'] = $explodedata[0];
        $this->data['exvendorcode'] = $explodedata[1];                
        
        if (!$planid) {
            redirect('supplier/plans','refresh');
        }
                
        $this->data['timesettings'] = $this->ConfigurationModel->getConfiguration();
        //pr($this->data['timesettings']);
        
        $UserId = $this->checkLogin();

        $this->data['planid'] = $planid;
        $this->data['vendorcode'] = $vendorcode;

        $sql = "select p.PlanCode,p.PlanStatus,s.CompanyName,p.EstimatePayDate,p.earlyPayAmount,a.CurrencyId,a.CurencyName,a.CurrencySign
        from `customer_early_pay_plans` p
        inner join `apr_currency_by_admin` a ON p.CurrencyType = a.CurrencyId
        inner join `site_users` s ON s.UserId = p.CustomerId
        where p.PlanId = '{$planid}';
        ";        
        
        $handler = $this->db->query($sql);
        $planData = $handler->result_array();
        
        $this->data['plan'] = $planData[0];
        
        $suppliertable = 'supplier_by_customer';
        $where = array('Email' => $this->session->userdata('EmailAddress'));
        $supplierdata = $this->UniversalModel->getRecords($suppliertable, $where);
         
        $config = array(
            array(
                'field' => 'BidType',
                'label' => 'BidType',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'BidRate',
                'label' => 'BidRate',
                'rules' => 'trim|required|numeric|xss_clean'
            )
        );
        
        $this->form_validation->set_rules($config);
         
        /*
        
        if ($this->form_validation->run() == FALSE) {
        
            //$this->load->view('users/enquiry', $this->data);
        } else {
        
            $table = 'plan_bids';
            $columns = array();
            $columns['PlanId'] = $planid;
            $columns['CustomerId'] = $supplierdata[0]['CustomerId'];
            $columns['SupplierId'] = $this->session->userdata('UserId');
            $columns['supplier'] = $supplierdata[0]['supplier'];
            $columns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
            $columns['Email'] = $this->session->userdata('EmailAddress');
            $columns['EnableBid'] = $this->input->post('EnableBid');
            $columns['BidType'] = $this->input->post('BidType');
            $columns['BidRate'] = $this->input->post('BidRate');
        
            $columns['AddedDate'] = $this->currentDateTime;
        
            //echo $planid;
            $pcolumns['PlanId'] = $planid;
            $pcolumns['CustomerId'] = $supplierdata[0]['CustomerId'];
            $pcolumns['SupplierId'] = $this->session->userdata('UserId');
            $pcolumns['supplier'] = $supplierdata[0]['supplier'];
            $pcolumns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
            $pcolumns['Email'] = $this->session->userdata('EmailAddress');
        
            //$pcolumns['BidType']  = $this->input->post('BidType');
            //$pcolumns['BidRate']  = $this->input->post('BidRate');
        
            $result = $this->UniversalModel->checkRecord($table, $pcolumns);
            
            $getbidid = $this->UniversalModel->getRecords($table, $pcolumns);
        
            $bidid = $getbidid[0]['BidId'];
            
            if ($result) {
        
                $bcolumns['CustomerId'] = $supplierdata[0]['CustomerId'];
                $bcolumns['SupplierId'] = $this->session->userdata('UserId');
                $bcolumns['supplier'] = $supplierdata[0]['supplier'];
                $bcolumns['Vendorcode'] = $supplierdata[0]['Vendorcode'];
                $bcolumns['Email'] = $this->session->userdata('EmailAddress');
                $bcolumns['EnableBid'] = $this->input->post('EnableBid');
                $bcolumns['BidType'] = $this->input->post('BidType');
                $bcolumns['BidRate'] = $this->input->post('BidRate');
                                $this->debuglog($bcolumns);
                $where = 'BidId';
                $this->UniversalModel->save($table, $bcolumns, $where, $bidid);
               
        
            } else {
        
                $this->UniversalModel->save($table, $columns, NULL, NULL);
                
            }
        }
         */
        
        $planbid = $this->UniversalModel->getRecords('plan_bids',
            array(
                'PlanId' =>  $planid,
                'SupplierId' => $this->session->userdata('UserId')
            )
        );
        
        $this->data['planbid'] = $planbid[0];
        
       
         
        if($planData[0]['PlanStatus'] === 'ongoing')
        {
                                    
            $sql = "SELECT p.*,'0' as BidAPR,'0' as IsWinner,DATEDIFF(p.EstPayDate,'".date('Y-m-d',strtotime($this->data['plan']['EstimatePayDate']))."') as dpe," ;
                             
                if(isset($planbid[0])){
                    if($planbid[0]['BidType'] == 'apr')                     
                        $sql .= "CONVERT(p.InvAmount * CONVERT(".$planbid[0]['BidRate']." /365 * DATEDIFF(p.EstPayDate,'".date('Y-m-d',strtotime($this->data['plan']['EstimatePayDate']))."') ,decimal(18,2)) /100 , decimal(18,2)) as AccrualAmount  ";
                    else
                        $sql .= "CONVERT(p.InvAmount *  ".$planbid[0]['BidRate']." / 100 , decimal(18,2)) as AccrualAmount " ;
                }                
                 else 
                    $sql .= "0 as AccrualAmount "; 
                 
             $sql .= "  FROM  `payable_supplier_by_customer`  p
                 INNER JOIN `supplier_by_customer` s ON s.CustomerId=p.CustomerId AND s.Vendorcode = p.Vendorcode
                        AND s.Email = '".$this->data['userdata'][0]['EmailAddress']." '
                        AND s.Vendorcode = '{$vendorcode}'                       
                 WHERE p.PlanId = '{$planid}';
                ";
           
        }else{
        
         $sql = "select p.*,IFNULL(w.BidAPR,0) as BidAPR,IFNULL(w.WinnerId,0) as IsWinner,w.PaymentCopy,w.dpe,w.AccrualAmount,w.apr
                 from  `payable_supplier_by_customer`  p
                 INNER JOIN `supplier_by_customer` s ON s.CustomerId=p.CustomerId AND s.Vendorcode = p.Vendorcode 
                        AND s.Email = '".$this->data['userdata'][0]['EmailAddress']." '
                        AND s.Vendorcode = '{$vendorcode}'
                 LEFT JOIN (
                    select a.* from `winners` a
                    inner join `customer_early_pay_plans` b on b.PlanId = a.PlanId and b.PlanStatus='finished'
                    where a.PlanId = '{$planid}'
                 ) w on w.PlanId=p.PlanId AND w.Vendorcode = p.Vendorcode AND w.InvoiceId = p.InvoiceId
                 where p.PlanId = '{$planid}'
                 ORDER BY IFNULL(w.WinnerId,0) DESC,p.SupplierId;
                ";

        }
        
        $handler = $this->db->query($sql);
        $data = $handler->result_array();
        
        if($this->uri->segment(4) == 'export')
        {
            
            $this->load->library('PHPExcel');
            
            $list = array();
            $n = 0;
            foreach($data as $v){
                
                
                $list[] = array(
                    'InvoiceId' => $v['InvoiceId'],
                    'EstPaydate' => date('Y-m-d',strtotime($v['EstPaydate'])),
                    'InvAmount' => $v['InvAmount'],
                    "EstimatePayDate" => date('Y-m-d',strtotime($planData[0]['EstimatePayDate'])),
                    'Discount' =>  floatval('-'.(isset($v['AccrualAmount']) ? $v['AccrualAmount'] : 0 )),
                    'PayAmount' => $v['InvAmount'] -(isset($v['AccrualAmount']) ? $v['AccrualAmount'] : 0 )                  
                );
            }
            
            $columns= array(
                    
                    array(
                        'datakey' => 'InvoiceId','colheader' => '发票号','colwidth' => '16'
                    ),
                    array(
                        'datakey' => 'EstPaydate','colheader' => '原付款日','colwidth' => '14'
                    ),
                    array(
                        'datakey' => 'InvAmount','colheader' => '发票金额'
                    ),
                    array(
                        'datakey' => 'EstimatePayDate','colheader' => '新付款日','colwidth' => '14'
                    ),
                    array(
                        'datakey' => 'Discount','colheader' => '折扣金额','colcolor'=> PHPExcel_Style_Color::COLOR_RED                     
                    ),
                    array(
                        'datakey' => 'PayAmount','colheader' => '折后金额'
                    )
                    
                );                
                             
                self::export($list,$columns);
            
        }
        
        
        $invAmount = 0;
        $winnerAmount = 0 ;
        $loserAmount = 0 ;
        $discountAmount = 0;
        $paymentcopy = '';
        
        $winner = array();
        
        $avg_all = array(
            'count' => 0,
            'total_dpe' => 0
        );
        $avg_winner= array(
            'count' => 0,
            'total_dpe' => 0
        );
        $avg_loser= array(
            'count' => 0,
            'total_dpe' => 0
        );
        
        $discount_rate = 0;
        $avg_apr = 0;
        
        foreach ($data as $v)
        {
            $invAmount = $invAmount + $v['InvAmount'];
            
            $avg_all['count'] = $avg_all['count'] + 1;
            $avg_all['total_dpe'] = $avg_all['total_dpe'] + $v['dpe'];
            
            if ( $v['IsWinner'] > 0 )
            {
                $winnerAmount += $v['InvAmount'] ;
                $discountAmount += $v['AccrualAmount'];
                
                $avg_winner['count'] = $avg_winner['count'] + 1;
                $avg_winner['total_dpe'] = $avg_winner['total_dpe'] + $v['dpe'];
                
                $winner[] = array(
                    'apr' => $v['apr']*$v['InvAmount'],                    
                    'discount' => $v['AccrualAmount']                        
                );
                
                if( !isset($paymentcopy) && empty($paymentcopy) && 1==0)
                {
                    $paymentcopy = $v['PaymentCopy'];
                }
            }else{
                
                $avg_loser['count'] = $avg_loser['count'] + 1;
                $avg_loser['total_dpe'] = $avg_loser['total_dpe'] + $v['dpe'];
                
                $loserAmount += $v['InvAmount'];
                
                if( $planData[0]['PlanStatus'] == 'ongoing')
                    $discountAmount += $v['AccrualAmount'];
            }
        }                      
          
        if( $planData[0]['PlanStatus'] == 'ongoing'){
            
            $discount_rate += round($discountAmount*100/$invAmount,2);            
            $avg_apr  = 0;
            
            foreach($data as $v ){
                
                $avg_apr +=  round( $v['AccrualAmount'] * 365 / $v['dpe'] / $invAmount ,4)*100;                
            }
            
        }else{
            foreach($winner as $w)
            {
                $discount_rate += round($w['discount']*100/$winnerAmount,2);
                $avg_apr += round($w['apr']/$winnerAmount,2);
            }         
        }
        
        
        $this->data['avg_all'] = round($avg_all['total_dpe']/$avg_all['count'],1);
        $this->data['avg_winner'] = round($avg_winner['total_dpe']/$avg_winner['count'],1);
        $this->data['avg_loser'] = round($avg_loser['total_dpe']/$avg_loser['count'],1);
        $this->data['avg_apr'] = $avg_apr;
        $this->data['discount_rate'] = $discount_rate;
        
        $this->data['WinnerAmount'] = round($winnerAmount,2);
        $this->data['LoserAmount'] = round($loserAmount,2);
        $this->data['discountAmount'] = round($discountAmount,2);
        $this->data['InvAmount'] = round($invAmount,2);
        $this->data['PaymentCopy'] =  $paymentcopy;
        $this->data['result'] = $data;
        
        $this->data['encode_planid'] = $encodeplanid ;
        $this->data['title'] = 'Plan Detail';
        $this->data['page'] = 'supplier_plandetail';
        $this->data['uri'] = 'supplier/plandetail/'.$encodeplanid;
        $this->right = 'supplier/plandetail';

        parent::innerLayout();
    }
    

    public function customers() {

        $UserId = $this->checkLogin();
                
        if(!isset($UserId) || empty($UserId))
            redirect('/','refresh');
        
        
        $sql = "SELECT B.* ,C.Vendorcode
                FROM `Base_Companys` B
                INNER JOIN `Customer_Suppliers` C ON C.CustomerId = B.Id
                INNER JOIN `Customer_Supplier_Users` U ON U.SupplierId = C.Id
                WHERE  U.UserStatus >= 0 AND U.UserEmail = '".$this->data['userdata'][0]['EmailAddress']."' ;
            ";
                
        /*
        $sql = "SELECT DISTINCT c.CustomerId, c.Email, s.*
                FROM `supplier_by_customer` c
                INNER JOIN  `site_users` s ON s.UserId=c.CustomerId
                WHERE c.Email = '".$this->data['userdata'][0]['EmailAddress']."' ;
            " ;
        */
        
        
        $handler = $this->db->query($sql);
        
        $this->data['customers'] = $handler->result_array(); 

        $this->data['title'] = 'Customers';
        $this->data['uri'] = 'supplier/customers';
        $this->right = 'supplier/customers';

        parent::innerLayout();
    }

    
    
    public function addcustomer() {

        $UserId = $this->checkLogin();
        
        $config = array(
            array(
                'field' => 'CompanyName',
                'label' => 'CompanyName',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'ContactPhone',
                'label' => 'ContactPhone',
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'ContactEmail',
                'label' => 'ContactEmail',
                'rules' => 'trim|required|xss_clean|valid_email|is_unique[users_enquiry.ContactEmail]'
            )
        );

        $this->form_validation->set_rules($config);


        if ($this->form_validation->run() == FALSE) {

            $this->right = 'supplier/addcustomer';
        } else {

            $table = 'users_enquiry';
            $columns = array();

            $columns['CompanyName'] = $this->input->post('CompanyName');
            $columns['ContactPhone'] = $this->input->post('ContactPhone');
            $columns['ContactEmail'] = $this->input->post('ContactEmail');
            $columns['EnquiryDate'] = $this->currentDateTime;

            $this->UniversalModel->save($table, $columns, NULL, NULL);

            $cc = ''; //earlypay@site.co.uk;
            //Get Template details

            $tid = 7;

            $resultTemplate = $this->TemplateModel->getTemplateById($tid);

            //pr($resultTemplate); 
            //Generate Ramdom String

            $random_string = random_string('unique');

            $verifyurl = base_url('enquiry');



            //echo $durl = $this->base64url_encode($emaildata[0]['Email']);
            //echo $gurl = $this->base64url_decode($durl);
            //pr($gurl);die;
            // Replace string

            $mixed_search = array("{firstname}", "{verifyurl}");

            $mixed_replace = array($this->input->post('CompanyName'), $verifyurl);

            $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);



            $data = array();

            $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];

            $data['contents'] = $messagebody;

            $body = $this->parser->parse('emailtemplate/template', $data, TRUE);

            //pr($body);die;
            //Send Email

            $this->SendEmailModel->sendEmail($this->input->post('ContactEmail'), $resultTemplate[0]['Subject'], $body, $this->input->post('CompanyName'), $cc);

            $this->session->set_flashdata('customermessage', 'You message is sent successfully');
        }


        $this->data['title'] = 'Add Customer';
        $this->data['uri'] = 'supplier/addcustomer';
        $this->right = 'supplier/addcustomer';

        parent::innerLayout();
    }

    public function suppliers($offset = 0) {

        //error_reporting(E_ALL);

        $UserId = $this->checkLogin();
        
        ############## Search data

        $search_data = '';

        $where = array('CustomerId' => $UserId);

        $order_by = array("SupplierId", "ASC");

        ################

        $this->load->library('pagination');

        $config['per_page'] = 10;

        $config['uri_segment'] = 3;

        $config['base_url'] = base_url() . '/customer/suppliers';

        $this->data['result'] = $this->UniversalModel->getRecords('supplier_by_customer', $where, $order_by, $config['per_page'], $offset);

        $config['total_rows'] = count($this->UniversalModel->getRecords('supplier_by_customer', $where, NULL, '', ''));

        $this->pagination->initialize($config);

        $this->data['paginglinks'] = $this->pagination->create_links();

        $this->data['per_page'] = $this->uri->segment(3);

        $this->data['title'] = 'Upload Suppliers';
        
        $this->data['uri'] = 'customer/suppliers';
        $this->right = 'customer/suppliers';

        parent::innerLayout();
    }

    public function confirmpayment($bidid) {
        
        $UserId = $this->checkLogin();
		
        $admintable = 'admin_bak';
        $admindata = $this->UniversalModel->getRecords($admintable);		
        
        $planbidtable = 'plan_bids';
        $data = array('BidId' => $bidid);
        $plansdata = $this->UniversalModel->getRecords($planbidtable, $data);
        
        $table = 'site_users';                   
        $where = array('UserId' => $plansdata[0]['CustomerId']);				  
        
        $emaildata = $this->UniversalModel->getRecords($table, $where);
        
        $swhere = array('EmailAddress' => $plansdata[0]['Email']);				  
        $supplieremaildata = $this->UniversalModel->getRecords($table, $swhere);				  
        $emaildata[0]['siturl'] = site_url('admin_bak');
		   
	    if($emaildata){
	        
			    $tid = 19;
                $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                // Replace string
                $mixed_search = array("{firstname}","{suppliername}","{planid}", "{siteurl}");
                $mixed_replace = array($emaildata[0]['ContactName'],$supplieremaildata[0]['ContactName'],$plansdata[0]['PlanId'],site_url('customer/plans'));
                $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

                $data = array();
                $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                $data['contents'] = $messagebody;
                $body = $this->parser->parse('emailtemplate/confirmpaymentsupplier', $data, TRUE);

                //echo $body; 
				$return = $this->SendEmailModel->sendEmail($emaildata[0]['EmailAddress'], $resultTemplate[0]['Subject'], $body, $emaildata[0]['CompanyName']);
				}
				if($admindata){
					$tid = 19;
                $resultTemplate = $this->TemplateModel->getTemplateById($tid);

                // Replace string
                $mixed_search = array("{firstname}","{suppliername}","{planid}", "{siteurl}");
                $mixed_replace = array($admindata[0]['Name'],$supplieremaildata[0]['ContactName'],$plansdata[0]['PlanId'],site_url('admin_bak'));
                $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

                $data = array();
                $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
                $data['contents'] = $messagebody;
                $body = $this->parser->parse('emailtemplate/confirmpaymentsupplier', $data, TRUE);

                //echo $body; die;
				$return = $this->SendEmailModel->sendEmail($admindata[0]['Email'], $resultTemplate[0]['Subject'], $body, $admindata[0]['Name']);
					
				}

        $updateplansql = "UPDATE `plan_bids` SET PaymentStatus='confirm', PaymentStatusDate='" . $this->currentDateTime . "'  WHERE `BidId` ='" . $bidid . "'  ";
        $updateplanquery = $this->db->query($updateplansql);

        if ($updateplanquery) {
            echo 'You request is confirmed';
		   //$this->session->set_flashdata('confirmreceived', ' You request is confirmed');
        }
    }

    public function username_check($Username) {

        $table = $this->input->post('type');

        $data = array('Username' => $Username);
        $result = $this->UniversalModel->checkRecord($table, $data);
        //$result = $this->UniversalModel->checkRecord('users', $data);

        if ($result == TRUE) {
            $this->form_validation->set_message('username_check', 'Sorry ! Username already exist.');
            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function emailaddress_check($EmailAddress) {

        $table = 'site_users';

        $data = array('EmailAddress' => $EmailAddress, 'Role' => $this->input->post('Role'));
        $result = $this->UniversalModel->checkRecord($table, $data);

        if ($result == TRUE) {
            $this->form_validation->set_message('emailaddress_check', 'Sorry ! Email address already exist.');
            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function captcha_check($word) {
        $captchaword = $this->session->userdata('captchaword');

        if ($word == $captchaword) {
            return TRUE;
        } else {

            $this->form_validation->set_message('captcha_check', 'Security code not matched !');
            return FALSE;
        }
    }

    public function export_proposal($params)
    {
    
        $exp = explode("-", $params);
    
        $export_type  = $this->base64url_decode($exp[3]);
        
        $vendorcode  = $this->base64url_decode($exp[2]);
        $awarddate = $this->base64url_decode($exp[1]);
        $paymentId = $this->base64url_decode($exp[0]);    
        
        $UserId  = $this->checkLogin();
    
        if( isset($UserId) )
        {
            $this->load->library('PHPExcel');
    
                $list = $this->get_award_payment($paymentId,$awarddate,$vendorcode);
                
               
                $columns= array(
                                        
                    array(
                        'datakey' => 'InvoiceNo','colheader' => '发票号','colwidth' => '16'
                    ),
                    array(
                        'datakey' => 'EstPaydate','colheader' => '原付款日','colwidth' => '14'
                    ),
                    array(
                        'datakey' => 'InvoiceAmount','colheader' => '发票金额'
                    ),
                    array(
                        'datakey' => 'PayDpe','colheader' => '提前天数'
                    ),
                    array(
                        'datakey' => 'PayAmount','colheader' => '实付金额'
                    ),                    
                    array(
                        'datakey' => 'Discount','colheader' => '折扣金额','colcolor'=> PHPExcel_Style_Color::COLOR_RED
                    )               
                );
                
                switch (strtolower($export_type)){
        
                    case 'xls':
                        self::export_xls($list,$columns);
                        break;
                    case 'csv':
                        self::export_csv($list,$columns);
                        break;
                    default:
                        break;
                }
        
        }
        else{
            redirect(base_url('auth/'), 'refresh');
        }
    
    }
    
    //sendEmailVerificationEmail
    public function sendEmailVerificationEmail($emaildata = array()) {
        $cc = ''; //NUR@site.co.uk;
        //Get Template details
        $tid = $this->lang->line('template_user_verify_email');
        $resultTemplate = $this->TemplateModel->getTemplateById($tid);

        //Generate Ramdom String
        $random_string = random_string('unique');

        $verifyurl = base_url() . 'register/confirm/' . $emaildata['type'] . '/' . $emaildata['UserId'] . '/' . $random_string;

        // Update User Details
        $columns = array();
        $columns['Verifystring'] = $random_string;
        $columns['Encrypt'] = $this->UniversalModel->encryptDecrypt($emaildata['Password']);
        $columns['VerifyEmailAddressStatus'] = '0';
        $where = 'UserId';
        $UserId = $emaildata['UserId'];
        $this->UniversalModel->save($emaildata['type'], $columns, $where, $UserId);

        // Replace string
        $mixed_search = array("{firstname}", "{verifyurl}");
        $mixed_replace = array($emaildata['FirstName'], $verifyurl);
        $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

        $data = array();
        $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
        $data['contents'] = $messagebody;
        $body = $this->parser->parse('emailtemplate/template', $data, TRUE);

        //Send Email
        $this->SendEmailModel->sendEmail($emaildata['EmailAddress'], $resultTemplate[0]['Subject'], $body, $emaildata['FirstName'], $cc);

        return true;
    }

    // sendWelcomeEmail
    public function sendWelcomeEmail($emaildata = array()) {
        
        $cc = "Sigma Technology";

        //Get Template details 
        $tid = $this->lang->line('template_user_welcome_email');
        $resultTemplate = $this->TemplateModel->getTemplateById($tid);

        // Replace string
        $mixed_search = array("{siteurl}", "{firstname}", "{username}", "{password}");
        $mixed_replace = array($emaildata['siteurl'], $emaildata['FirstName'], $emaildata['Username'], $emaildata['Password']);
        $messagebody = str_replace($mixed_search, $mixed_replace, $resultTemplate[0]['Body']);

        $data = array();
        $data['title'] = $data['heading'] = $resultTemplate[0]['Subject'];
        $data['contents'] = $messagebody;
        $body = $this->parser->parse('emailtemplate/template', $data, TRUE);
        //Send Email
        $this->SendEmailModel->sendEmail($emaildata['EmailAddress'], $resultTemplate[0]['Subject'], $body, $emaildata['FirstName'], $cc);

        return true;
    }

    //emailconfirm

    public function confirm($table = NULL, $UserId = NULL, $string = NULL) {

        $this->data['title'] = $this->lang->line('register_account_confirm_h');

        if (empty($UserId) || empty($table) || empty($string)) {

            $this->data['heading'] = $this->lang->line('register_error_h');
            $this->data['message'] = $this->lang->line('register_invalid_confirmation_code');
        } else {

            //$where = array('UserId'=> $UserId,'Verifystring'=> $string,'Status'=> '0');
            $where = array('UserId' => $UserId, 'Verifystring' => $string);
            $result = $this->UniversalModel->getRecords($table, $where);
            if ($result != NULL && sizeof($result) > 0) {
                //Creat Email data
                $emaildata = array();
                foreach ($result[0] as $k => $v) {
                    $emaildata[$k] = $v;
                }
                $emaildata['Password'] = $this->UniversalModel->encryptDecrypt($emaildata['Encrypt'], 'D');
                $emaildata['siteurl'] = site_url('auth');

                // Update User Details
                $data = array();
                $data['VerifyEmailAddressStatus'] = '1';
                $data['Verifystring'] = '';
                $data['Encrypt'] = '';
                $where = 'UserId';
                $this->UniversalModel->save($table, $data, $where, $UserId);

                $this->data['heading'] = $this->lang->line('register_account_confirm_h');
                $this->data['message'] = "Your email address is verified successfully!!";
            } else {

                $this->data['heading'] = $this->lang->line('register_account_error_h');
                $this->data['message'] = $this->lang->line('register_invalid_confirmation_code');
            }
        }

        $this->middle = 'register/success';
        parent::layout();
    }

    public function isLogin() {

        if ($this->UniversalModel->isLogin()) {
            if ($this->session->userdata("Role") == 'customer') {
                redirect('customer/plans', 'refresh');
            } elseif ($this->session->userdata("Role") == 'supplier') {
                redirect('supplier/dashboard', 'refresh');
            }
        }
    }
    
    private function export_csv($list,$header){ 
        
        header("Content-Type: text/csv");  
        header("Content-Disposition: attachment; filename=".date('Y-m-d').".csv");  
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');  
        header('Expires:0');  
        header('Pragma:public');  
        
        $str = '';
        $result = '' ;
        foreach($header as $v){
            $str .= $v['colheader']."|";
        }
        
        $result .= substr($str,0,strlen($str)-1) . "\r\n";
        
        
        foreach($list as $v){
            $str = '';
            
            foreach($header as $k){
                $str .= $v[$k['datakey']]."|";
            }            
            
           $result .= substr($str,0,strlen($str)-1) . "\r\n";
        }     

        echo $result ;
    
    }
    
    private function export_xls($data,$columns = array())
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
                );
            }
            
        }        
        
        $xlsCol = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $this->debuglog($columns);
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
                
                /*
                if(isset($c['coltype']))
                {
                    $objPHPExcel->getActiveSheet()->getStyle($xlsCol[$key].$raw)->getNumberFormat()->setFormatCode($c['coltype']);
                                        
                    //getActiveSheet()->setCellValueExplicit( $xlsCol[$key].$raw,$i[$c['datakey']],$c['coltype']);
                }
                */
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
                   
        }
        
        
        //选择所有数据
        $fill = $xlsCol[0].'1:'.$xlsCol[count($columns) - 1 ].$raw ;
        
        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle($fill)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        //所有垂直居中
        $objPHPExcel->getActiveSheet()->getStyle($fill)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'payblelist-'.date('Y-m-d').'.xls"');
        header('Cache-Control: max-age=0');
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        
        $objWriter->save('php://output');
        
        exit;
    
    
    }
    

    public function do_upload() {

        if (!$this->upload->do_upload()) {

            return FALSE;
            //return $data = array('error' => $this->upload->display_errors());
        } else {
            $this->data['upload_data'] = $this->upload->data();

            return $this->data['upload_data'];
        }
    }

    public function duplicate_currency_check($currencyname) {

        $aprtable = 'apr_supplier_setting';

        $this->checkLogin();
        if ($this->session->userdata('Role') == 'supplier') {

            $UserId = $this->userdata['UserId'];

            $table = 'site_users';

            $data = array('UserId' => $this->session->userdata('UserId'), 'Role' => $this->session->userdata('Role'));

            $this->data['userdata'] = $this->UniversalModel->getRecords($table, $data);
        }

        $data = array('CurrencyName' => $currencyname, 'SupplierId' => $this->session->userdata('UserId'));
        $result = $this->UniversalModel->checkRecord($aprtable, $data);

        //pr($result);
        if ($result) {
            $this->form_validation->set_message('duplicate_currency_check', 'Sorry ! You have already entered settings for this currency');
            return FALSE;
        } else {

            return TRUE;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/register.php */