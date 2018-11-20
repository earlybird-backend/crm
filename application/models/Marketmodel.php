<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MarketModel extends CI_Model {

    protected $profile ;

	public function __construct()    {
        parent::__construct();

        $this->db = $this->load->database('cisco',true);

        #$this->profile = $this->cache->memcached->get('profile');
    }

    //查询所有打开的市场
    public function get_markets(){

        $result = array(); //$this->cache->memcached->get('markets-open');

        if( $result == null || empty($result)) {

            $sql = "select 
              p.Id, 
              p.CreateTime,
              p.CashpoolCode,    
              p.CompanyId,
              p.CompanyDivision , 
              p.MarketStatus, 
              p.CurrencySign, 
              p.CurrencyName, 
              p.MiniAPR, 
              p.ExpectAPR, 
              p.NextPaydate, 
              IFNULL(p.AutoAmount,0) as AvailableAmount ,
              IFNULL(AvailableAmount, 0) as TotalAmount,
              IFNULL(AllocateId,0) as AllocateId,
              ReservePercent,
              ReconciliationDate
              from  `Customer_Cashpool` p                 
              where p.MarketStatus >= 0
              ORDER BY p.NextPaydate ASC;";

            $query = $this->db->query($sql);

            $result = array();
            $rst = $query->result_array();

            foreach( $rst as $item){
                $result[$item["CashpoolCode"]] = array(
                    "id" => $item["Id"],
                    "create_time" => date('Y-m-d H:i:s', strtotime($item['CreateTime'])),
                    "company_id" => $item["CompanyId"],
                    "market_name" => $item["CompanyDivision"],
                    "market_status" => $item["MarketStatus"],
                    "currency_sign" => $item["CurrencySign"],
                    "currency" => $item["CurrencyName"],
                    "mini_apr" => $item["MiniAPR"],
                    "expect_apr" => $item["ExpectAPR"],
                    "paydate" => $item["NextPaydate"],
                    "available_amount" => $item["AvailableAmount"],
                    "total_amount" => $item["TotalAmount"],
                    "allocate_id" => $item["AllocateId"],
                    "reserve_percent" => $item["ReservePercent"],
                    "reconciliation_date" => $item["ReconciliationDate"]
                );
            }

            //$this->cache->memcached->save( 'markets-open', $result, 360);

        }
        return $result;
    }

    //查询某市场
    public function get_market_by_code($cashpoolCode){

        $result = array(); //$this->cache->memcached->get('markets-open');

        if( $result == null || empty($result)) {

            $sql = "select 
              p.Id, 
              p.CreateTime,
              p.CashpoolCode,    
              p.CompanyId,
              p.CompanyDivision , 
              p.MarketStatus, 
              p.CurrencySign, 
              p.CurrencyName, 
              p.MiniAPR, 
              p.ExpectAPR, 
              p.NextPaydate, 
              IFNULL(p.AutoAmount,0) as AvailableAmount ,
              IFNULL(AvailableAmount, 0) as TotalAmount,
              IFNULL(AllocateId,0) as AllocateId,
              ReservePercent,
              ReconciliationDate
              from  `Customer_Cashpool` p                 
              where p.CashpoolCode = '{$cashpoolCode}'
              LIMIT 1;";

            $query = $this->db->query($sql);

            $result = array();
            $item = $query->first_row('array');


            $result = array(
                "id" => $item["Id"],
                "create_time" => date('Y-m-d H:i:s', strtotime($item['CreateTime'])),
                "cashpool_code" => $item['CashpoolCode'],
                "company_id" => $item["CompanyId"],
                "market_name" => $item["CompanyDivision"],
                "market_status" => $item["MarketStatus"],
                "currency_sign" => $item["CurrencySign"],
                "currency" => $item["CurrencyName"],
                "mini_apr" => $item["MiniAPR"],
                "expect_apr" => $item["ExpectAPR"],
                "paydate" => $item["NextPaydate"],
                "available_amount" => $item["AvailableAmount"],
                "total_amount" => $item["TotalAmount"],
                "allocate_id" => $item["AllocateId"],
                "reserve_percent" => $item["ReservePercent"],
                "reconciliation_date" => $item["ReconciliationDate"]
            );

        }

        return $result;
    }


    public function get_service_time(){

        $result = array(); //$this->cache->memcached->get('service-time');

        if( $result == null || empty($result)) {

            $sql = "select StartTime as starttime, EndTime as endtime  
              from Customer_Cashpool_Service where `ServiceStatus` = 1;";

            $query = $this->db->query($sql);

            $result = $query->first_row('array');

            //$this->cache->memcached->save( 'service-time', $result, 3600);
        }

        return $result;
    }



}
