<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Market extends MY_Controller {


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
        $active_sql= 'SELECT p.Id,		
            p.CashpoolCode as cashpoolcode,		
            p.createtime,						
            c.companyname as buyer,				
            p.CompanyDivision as division,		
            p.currencyname as currency, 		
            p.currencysign as currencysign,		
            p.marketstatus,			
            x.Cnt  as ScheduleCount,		
            IFNULL(p.AvailableAmount,0) as totalamount,
            IFNULL(p.AutoAmount,0) as avaamount,
            p.NextPaydate
            FROM   `Customer_Cashpool` p             
            INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId		
            LEFT JOIN (
                SELECT CashpoolCode, COUNT(Id) Cnt FROM  `Customer_Cashpool_Allocate` WHERE AllocateStatus in (0,1) GROUP BY CashpoolCode
            ) x ON x.CashpoolCode = p.CashpoolCode
            WHERE p.MarketStatus >= 0 AND exists(
                            SELECT InvoiceNo FROM `Customer_Payments` m WHERE m.CashpoolCode = p.CashpoolCode
                            AND m.InvoiceStatus = 1 AND m.IsIncluded = 1 AND m.EstPaydate > p.NextPaydate AND m.InvoiceAmount < p.AutoAmount
                        )              
            ORDER BY p.NextPaydate ;';

        $query = $this->db->query($active_sql);
        $rs = $query->result_array();
        $active = [];
        foreach($rs as $item){
            $active[] = array(
                "id" => $item['Id'],
                "market_id" => $item['cashpoolcode'].'/'.$item['Id'],
                "market_name" => $item['division'],
                "market_status" => ($item['marketstatus']==1)? "Open" : "Closed",
                "currency_sign"  => $item['currencysign'],
                "currency_name" => $item['currency'],
                "cash_schedule" => $item['ScheduleCount'],
                "create_time" => $item['createtime'],
                "next_paydate" => $item['NextPaydate'],
                "cash_amount" => $item['totalamount'],
                "cash_available" => $item['avaamount']
            );
        }


        $this->data['active'] = $active;

        $where = "";
        foreach( $active as $m){
            $where .= $m["id"].",";
        }

        $where .= "0";

        $inactive_sql = ' SELECT p.Id,
            p.CashpoolCode as cashpoolcode,	
            p.createtime,					
            c.companyname as buyer,			
            p.CompanyDivision as division,	
            p.currencyname as currency, 	
            p.currencysign as currencysign,	 
            p.marketstatus,	
            x.Cnt  as ScheduleCount,
            IFNULL(p.AvailableAmount,0) as totalamount,
            IFNULL(p.AutoAmount,0) as avaamount,	
            p.NextPaydate
            FROM   `Customer_Cashpool` p 
            LEFT JOIN `Customer_Cashpool_Allocate` ps ON ps.Id = p.AllocateId AND ps.AllocateStatus = 1
            LEFT JOIN `Base_Companys` c ON c.Id = p.CustomerId		
            LEFT JOIN (
                SELECT CashpoolCode, COUNT(Id) Cnt FROM  `Customer_Cashpool_Allocate` WHERE AllocateStatus in(0,1) GROUP BY CashpoolCode
            ) x ON x.CashpoolCode = p.CashpoolCode
            WHERE p.MarketStatus >= 0 
            	AND p.Id NOT IN ( '. $where . ') 	
            ORDER BY p.NextPaydate ;';


        $query = $this->db->query($inactive_sql);
        $rs = $query->result_array();
        $inactive = [];
        foreach($rs as $item){
            $inactive[] = array(
                "id" => $item['Id'],
                "market_id" => $item['cashpoolcode'].'/'.$item['Id'],
                "market_name" => $item['division'],
                "market_status" => ($item['marketstatus']==1)?"Open":"Closed",
                "currency_sign"  => $item['currencysign'],
                "currency_name" => $item['currency'],
                "cash_schedule" => $item['ScheduleCount'],
                "create_time" => $item["createtime"],
                "next_paydate" => $item['NextPaydate'],
                "cash_amount" => $item['totalamount'],
                "cash_available" => $item['avaamount']
            );
        }


        $this->data['inactive'] = $inactive;

        $this->data['title'] = 'Market Manager';
        $this->data['link'] = $this->current_controller;

        $this->load->view('supplier/market_list', $this->data);

    }

    //市场开价记录、当日结果
    public function detail($cashpoolcode,$cashpoolid,$query_date=null,$vendorcode=null,$cleartype=-1){

       $this->db = $this->load->database('bird',true);

       $this->data['cashpoolcode']=$cashpoolcode;
       $this->data['cashpoolid']=$cashpoolid;
       $this->data['cleartype']=$cleartype;

       $this->data['cleartype_option'] = array(
           "-1"=>"全部清算",
           "0"=>"平台清算",
           "1"=>"手工清算"
       );

       $query_date = ($query_date==null || $query_date=="")?date('Y-m-d',mktime()):$query_date;
       $this->data['querydate']=$query_date;

       $vendorcode = ($vendorcode==null || $vendorcode=="")?"all":$vendorcode;
       $this->data['vendorcode'] = $vendorcode;

       //============供应商列表=========
       $vendor_sql = "select supplier, vendorcode from Customer_Suppliers where cashpoolcode = '{$cashpoolcode}'";
       $query = $this->db->query($vendor_sql);
       $vendor_rs = $query->result_array();
       $this->data['vendors'] = $vendor_rs;
       //============供应商列表=========


       //=======获取市场相关信息=======
        $market_info_sql = "SELECT p.Id,
			p.CashpoolCode as cashpoolcode,	
			c.companyname as company,			
			p.CompanyDivision as buyer,	
			p.currencyname as currency, 	
			p.currencysign as currencysign	
			FROM   `Customer_Cashpool` p 			
			INNER JOIN `Base_Companys` c ON c.Id = p.CompanyId	
			WHERE p.Id = '{$cashpoolid}' ";
        $query = $this->db->query($market_info_sql);
        $info_rs = $query->result_array();
        $this->data['info']=$info_rs;
       //=======获取市场相关信息=======


       //=======开价记录=============
        $vendor_c = ($vendorcode=="all")?"":" and q.Vendorcode = '{$vendorcode}'";
       $price_sql = "SELECT q.Id,											# 开价记录ID
			s.Supplier as supplier, 		# 供应商
            s.vendorcode,											# 供应商 Code
            q.createtime,											# 开价时间
			q.bidtype,												# 开价类型 小写或大写 APR  年化收益率 | DISCOUNT 折扣率			
            q.bidrate,												# 该值为 %  
            q.minamount,											# 最低成交金额
			IFNULL(q.AwardAPR,0) as apr,							# 年化收益 值后面添加 %
			IFNULL(q.AwardDiscount,0) as discount,					# 折扣金额			
            IFNULL(q.clearpayment,0) as clearpayment,				# 清算金额
            IFNULL(q.noclearpayment,0) as noclearpayment			# 未清算金额
			FROM `Supplier_Bid_Queue` q			
			INNER JOIN`Customer_Cashpool` p ON q.CashPoolId = p.Id	
			LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = q.Vendorcode
            WHERE q.CashpoolId = {$cashpoolid} 
            #AND to_days(q.CreateTime) = {$query_date} {$vendor_c} 
			ORDER BY q.CreateTime DESC";
       $query = $this->db->query($price_sql);
       $price_rs = $query->result_array();
       $this->data['price']=$price_rs;
       //=======开价记录=============

       //=======当日市场结果=============
        $vendor_c1 = ($vendorcode=="all")?"":" and p.Vendorcode = '{$vendorcode}'";
        $cleartype_sql = ($cleartype=="" || $cleartype=='-1')?'':' and a.IsManual='.$cleartype;

        $result_sql = "SELECT 
								
				IFNULL(ClearAmount,0) as ClearAmount, 						# 清理的应收发票金额
				IFNULL(NoClearAmount,0) as NoClearAmount,					# 未清理的应收发票金额
				IFNULL(Discount,0) as Discount 								# 折扣金额
                from (
					SELECT '{$cashpoolid}' as CashpoolId, SUM(a.PayAmount) as ClearAmount, SUM(a.PayDiscount) as Discount
                    FROM `Customer_PayAwards` a
                    INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId and p.InvoiceStatus = 2					# 发票只有 InvoiceStatus = 2 才为真正的成交结果
                    WHERE a.CashpoolId = '{$cashpoolid}' AND a.AwardDate = '{$query_date}' 
					{$cleartype_sql}    
                    {$vendor_c1}
				) x                
                LEFT join (
                
					SELECT '{$cashpoolid}' as CashpoolId,SUM( p.InvoiceAmount) as NoClearAmount
					from `Customer_Payments` p                            					
					WHERE p.CashpoolCode = '{$cashpoolcode}' 
					{$vendor_c1} 	#若需要过滤供应商
					AND p.EstPayDate > '{$query_date}'						# 发票 EstPayDate < NOW 的发票不算入未清理发票的统计
					AND (  p.InvoiceStatus = 1 												# 发票 InvoiceStatus  = 1 是指发票未清算，因有可能查询历史成交记录
						OR 
						(p.InvoiceStatus = 2 AND p.AwardDate > '{$query_date}')	#发票清算日期 > 当天的在这里也要统计为 当天未清算
						) 			
                                       
				) y ON y.CashpoolId = x.CashpoolId";


        $result_query = $this->db->query($result_sql);
        $result_rs = $result_query->first_row('array');
        $this->data['results']=$result_rs;
       //=======当日市场结果=============
       $this->data['pre_nav'] = array('title' => 'Market Manager', 'uri'=> $this->current_controller) ;
       $this->data["title"] = "Market Award detail";
       $this->load->view('supplier/market_detail', $this->data);
   }
    
    //开价记录发票明细
    public function invoice($cashpoolcode,$queryid){
        $invoice_sql = "SELECT 
			s.Supplier as supplier, 	# 供应商
            s.vendorcode,										# 供应商 Code
			p.InvoiceNo,										# 发票编号
			p.InvoiceDate,										# 发票开票日期
			p.EstPaydate,										# 原支付日期
			p.InvoiceAmount, 									# 发票金额
			CASE WHEN a.Id IS NOT NULL THEN 1 ELSE 0 END as IsAwarded	# 若为 1 则为清算， 0 则为未清算
			FROM `Supplier_Bid_Queue` q
			INNER JOIN `Customer_Payments` p ON p.Vendorcode = q.Vendorcode AND p.CashpoolCode = '{$cashpoolcode}'
			LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = q.Vendorcode			
			LEFT JOIN `Customer_PayAwards` a ON a.InvoiceId = p.Id
			WHERE q.Id =  {$queryid}  ";

        $this->db = $this->load->database('bird',true);
        $query = $this->db->query($invoice_sql);
        $rs = $query->result_array();
        $this->toJson($rs);
    }


    //当日结果发票明细
    public function invoice_rs($cashpoolid,$vendorcode=null,$awarddate=null){
        $awarddate_sql = ($awarddate==null || $awarddate=="")?'':" AND a.AwardDate = '{$awarddate}'";
        $vendorcode_sql = ($vendorcode==null || $vendorcode=="null")?'':" AND s.Vendorcode = '{$vendorcode}'";

        $invoice_sql = "SELECT 
				s.Supplier as supplier, 		# 供应商
				s.vendorcode,											# 供应商 Code
				p.InvoiceNo,											# 发票编号
				p.InvoiceDate,											# 发票开票日期
				p.EstPaydate,											# 原支付日期
				p.InvoiceAmount 										# 发票金额
				FROM `Customer_PayAwards` a
				INNER JOIN `Customer_Payments` p ON p.Id = a.InvoiceId and p.InvoiceStatus = 2					# 发票只有 InvoiceStatus = 2 才为真正的成交结果
				LEFT JOIN `Customer_Suppliers` s ON s.CashpoolCode = p.CashpoolCode AND s.Vendorcode = p.Vendorcode
				WHERE a.CashpoolId = {$cashpoolid} {$awarddate_sql} {$vendorcode_sql}
				";
        $this->db = $this->load->database('bird',true);
        $query = $this->db->query($invoice_sql);
        $rs = $query->result_array();
        $this->toJson($rs);
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
