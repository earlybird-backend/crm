<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//Created by loudon

class InvoicelistModel extends CI_Model {

    
    var $datestring = "%Y-%m-%d";
    var $dateStringWithTime = "%Y-%m-%d %H:%i:%s";
    var $currentDate = '';
    var $currentDateTime = '';    
        
    public function __construct() {
        parent::__construct();
        $this->currentDate = mdate($this->datestring, time());
        $this->currentDateTime = mdate($this->dateStringWithTime, time());        
    }

    // Get valid invoices
    public function getValidInvoices($customerId = 0 ,$currencyId = 0, $paydate = null) {
        
        $where = "";
               
   
        if($customerId > 0 && $currencyId > 0)
        {
            $validDate = $this->currentDate ;
            
            //若有支付日期则需要将
            if(isset($paydate) && !empty($paydate)  && $validDate > $paydate)
            {
                $validDate = $paydate ;                
            }
            
            $validDate = mdate($this->datestring,strtotime($validDate)-48*60*60);
            
            $sql = "
                SELECT
                    p.Id,
                    p.CustomerId,
                    s.supplier,
                    p.Vendorcode,
                    p.InvoiceNo,
                    p.InvoiceAmount,
                    p.InvoiceDate,
                    p.EstPaydate,
                    p.InvoiceStatus
                FROM `Customer_Payments` p
                INNER JOIN `Customer_Suppliers` s ON s.CustomerId = p.CustomerId AND s.Vendorcode = p.Vendorcode
                WHERE p.`InvoiceStatus` = 1 AND p.`CustomerId` = '{$customerId}' AND p.`CurrencyId` = '{$currencyId}'
                AND p.`EstPaydate` > '{$validDate}' 
                Order by p.`EstPaydate` DESC
                ; ";
                
            $handler = $this->db->query($sql);
            
            if ($handler->num_rows() > 0) {
                return $handler->result_array();
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
    
    // Get valid invoices
    public function getInvalidInvoices($customerId = 0 ,$currencyId = 0, $paydate = null) {
    
        $where = "";
         
         
        if($customerId > 0 && $currencyId > 0)
        {
            $validDate = $this->currentDate ;
    
            //若有支付日期则需要将
            if(isset($paydate) && !empty($paydate)  && $validDate > $paydate)
            {
                $validDate = $paydate ;
            }
    
            $validDate = mdate($this->datestring,strtotime($validDate)+48*60*60);
    
            
            $sql = "
            SELECT
                p.Id,
                p.CustomerId,
                s.supplier,
                p.Vendorcode,
                p.InvoiceNo,
                p.InvoiceAmount,
                p.InvoiceDate,
                p.EstPaydate,
                case when p.`EstPaydate` <= '{$validDate}' then -1 else  p.InvoiceStatus end as `InvoiceStatus`
            FROM `Customer_Payments` p
            INNER JOIN `Customer_Suppliers` s ON s.CustomerId = p.CustomerId AND s.Vendorcode = p.Vendorcode
            WHERE p.`CustomerId` = '{$customerId}' AND p.`CurrencyId` = '{$currencyId}'
            AND ( (p.`InvoiceStatus` < 1 AND p.`InvoiceStatus` > -2)  OR p.`EstPaydate` <= '{$validDate}') 
            Order by p.`EstPaydate` DESC
            ; ";
  
            $handler = $this->db->query($sql);
    
            if ($handler->num_rows() > 0) {
                return $handler->result_array();
            } else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function updateInvoiceStatus($customerId = 0, $currencyId = 0 , $paydate){
        
    }
    
    public function updateInvoiceByIds($id = array(),$status = 0 ){
        
                
        if(isset($id) && is_array($id) && count($id) > 0)
        {
            $where = "Id in (";
            foreach ($id as $i){                
                $where .= $i.",";                
            }
            
            $where .= "0)";
            
        }else if( intval($id) && $id > 0 )
        {
            $where = "Id = '{$id}' ";
        }else{
            return false;
        }
        
        $sql= "UPDATE `Customer_Payments` SET InvoiceStatus = '{$invStatus}' \n".
            "WHERE {$where};";
        
        
        return $this->db_commit($sql);
        
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

}
