<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util extends CI_Model {
	
	
	
	public function __construct()    {
        parent::__construct();		
		$this->currentDate = mdate($this->datestring, time()) ;
        $this->currentDateTime = mdate($this->dateStringWithTime, time()) ;	
		
    } 	
	
    public function loadDataFromFile($sheetData,$supplierModel,$UserId)
    {
        //die('Success! File Uploaded.');
        
        $totalrecoreds = count($sheetData);
        
             for ($i = 2; $i <= $totalrecoreds; $i++) {
        
                $SheetEstPaydate = $sheetData[$i]['3'];
                $epdate = explode('-', $SheetEstPaydate);
                //pr($epdate);
                $mon = $epdate[0];
                $mondate = $epdate[1];
                $year = $epdate[2];
        
                $EstPaydate = date('Y-m-d', strtotime($year . "-" . $mon . "-" . $mondate));
                $SheetInvDate = $sheetData[$i]['5'];
                $invdate = explode('-', $SheetInvDate);
                //pr($epdate);
                $invmon = $invdate[0];
                $invmondate = $invdate[1];
                $invyear = $invdate[2];
        
                $InvoiceDate = date('Y-m-d', strtotime($invyear . "-" . $invmon . "-" . $invmondate));
        
                $data = array(
                    'Vendorcode' => $sheetData[$i]['0'],
                    'supplier' => $sheetData[$i]['1'],
                    'InvoiceId' => $sheetData[$i]['2'],
                    'EstPaydate' => $EstPaydate,
                    'InvAmount' => $sheetData[$i]['4'],
                    'InvDate' => $InvoiceDate,
                    'Netterm' => $sheetData[$i]['6'],
                    'CustomerId' => $this->session->userdata('UserId'),
                    'InviteStatus' => 0,
                    'AddedDate' => $this->currentDateTime
                );
        
                //pr($data);
                $listdata[] = $data;
        
                ###############################################################
        
                /*$paytable = 'supplier_by_customer';
                 $pcolumns['Vendorcode']   = $sheetData[$i]['0'];
                 $checkexistresult = $this->UniversalModel->getRecords($paytable, $pcolumns);
                 pr(array_unique($checkexistresult)); */
        
                
                ###############################################################
        
            }
           return $listdata;
    }

    public function SendTemplate($phpExcel , $supplierModel){
        
    }
    
}
