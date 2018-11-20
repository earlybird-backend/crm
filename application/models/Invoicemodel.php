<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class InvoiceModel extends CI_Model {


	public function __construct()    {
        parent::__construct();

        $this->db = $this->load->database('bird', true);

        //$this->load->driver('cache');

    }

    //供应商的状态值转换
    public function getInvoiceStatus(){

        return  array(
            -1 => "无效",
            0 => "调整",
            1 => "正常",
            2 => "已清",
            3 => "待清"
        );
    }


    //查询某市场的所有发票
    public function get_market_invoices($cashpoolCode){


        $result = array(); // $this->cache->memcached->get($cashpoolCode . '-invoice');

        if( $result == null || empty($result)) {

            $sql = "SELECT i.Id as id, 
                      i.Vendorcode as vendorcode, 
                      i.InvoiceStatus as status, 
                       i.IsIncluded as include,
                      i.InvoiceNo as invoiceno, 
                      i.InvoiceAmount as amount, 
                      i.EstPaydate as paydate
                    FROM `Customer_Payments` i                   
                    where i.CashpoolCode = '{$cashpoolCode}'
                    and i.InvoiceStatus > -2;";

            $query = $this->db->query($sql);


            $result = $query->result_array();

           // $this->cache->memcached->save($cashpoolCode . '-invoice', $result, 60);

        }
        return $result;

    }

    //查询某市场的所有发票
    public function get_market_invoices_valid($cashpoolCode, $paydate){

        $result = array();

        if( $result == null || empty($result)) {

            $sql = "SELECT i.Id as id, 
                      i.Vendorcode as vendorcode, 
                      i.InvoiceNo as invoiceno, 
                      i.InvoiceAmount as amount, 
                      i.EstPaydate as paydate
                    FROM `Customer_Payments` i                   
                    where i.CashpoolCode = '{$cashpoolCode}'
                    and i.InvoiceStatus = 1
                    and i.IsIncluded = 1
                    and i.EstPaydate > '{$paydate}';";

            $query = $this->db->query($sql);


            $result = $query->result_array();

        }
        return $result;

    }

    //查询某市场中某供应商发票
    public function get_market_vendor_invoices($cashpoolCode, $vendorcode){

        $result = array();

        if( $result == null || empty($result)) {

            $sql = "SELECT i.Id as id, 
                      i.Vendorcode as vendorcode, 
                      i.InvoiceStatus as status, 
                      i.IsIncluded as include,
                      i.InvoiceNo as invoiceno, 
                      i.InvoiceAmount as amount, 
                      i.EstPaydate as paydate
                    FROM `Customer_Payments` i                   
                    where i.CashpoolCode = '{$cashpoolCode}'
                    and i.Vendorcode = '{$vendorcode}'
                    and i.InvoiceStatus > -2;";

            $query = $this->db->query($sql);

            $result = $query->result_array();

        }
        return $result;
    }

    //查询某市场中某供应商发票
    public function get_market_vendor_invoices_valid($cashpoolCode, $vendorcode, $paydate){
        $result = array() ; //$this->cache->memcached->get("{$cashpoolCode}-{$vendorcode}-validinvoice");

        if( $result == null || empty($result)) {

            $sql = "SELECT i.Id as id, 
                      i.Vendorcode as vendorcode, 
                      i.InvoiceStatus as status, 
                      i.IsIncluded as include,
                      i.InvoiceNo as invoiceno, 
                      i.InvoiceAmount as amount, 
                      i.EstPaydate as paydate
                    FROM `Customer_Payments` i                   
                    where i.CashpoolCode = '{$cashpoolCode}'
                    and i.Vendorcode = '{$vendorcode}'
                    and i.InvoiceStatus = 1
                    and i.IsIncluded = 1
                    and i.EstPaydate > '{$paydate}';";

            $query = $this->db->query($sql);

            $result = $query->result_array();
            //$this->cache->memcached->save("{$cashpoolCode}-{$vendorcode}-validinvoice", $result, 60);

        }
        return $result;
    }

    //设置某发票是否包含
    public function set_invoice_included($Id){

    }

    public function set_invoice_included_batch($list){

    }


		
}
