<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SupplierModel extends CI_Model {

	public function __construct()    {
        parent::__construct();

        $this->db = $this->load->database('bird', true);

    }

    //供应商的状态值转换
    public function getSupplierStatus(){

        return  array(
            -1 => "无效",
            0 => "调整",
            1 => "正常"
        );
    }

    // Get category
    public function getSuppliers($cashpoolCode){

        $vendors = array(); //$this->cache->memcached->get($cashpoolCode."-vendors");

        if( $vendors ==  null || empty($vendors )){

            $vendors = array();

            $sql = 	"select 
                    v.Id, 
                    v.Supplier, 
                    v.Vendorcode,
                    v.VendorStatus                     
                    from  `Customer_Suppliers` v                     
                    where v.CashpoolCode = '{$cashpoolCode}'                    
                    ORDER BY v.`Id` DESC;

                  ";

            $query = $this->db->query($sql);

            //print_r($this->db->last_query()); die;

            if($query->num_rows() >0 )
            {

                $result = $query->result_array();

                foreach($result as $row){

                    if ( $row['Id'] == null || empty($row['Id']))
                        continue;

                    $vendors[$row['Vendorcode']] = array(
                        "supplier_id" => $row['Id'] ,
                        "supplier_name" => $row['Supplier'],
                        "supplier_status" => $row['VendorStatus'],
                        "vendor_code" => $row['Vendorcode'],
                        "supplier_users" => $row['VendorUsers']
                    );

                }
            }

           // $this->cache->memcached->save($cashpoolCode."-vendors", $vendors);
        }

        return $vendors;

    }


    //供应商的状态值转换
    public function getSupplierUserStatus(){

        return  array(
            -1 => "禁用",
            0 => "待注册",
            1 => "正常"
        );
    }

    public function getSuppliersUsers($cashpoolCode, $vendorId){

        $cashKey = $cashpoolCode."-".$vendorId;

        $users = $this->cache->memcached->get($cashKey);

        if( $users ==  null){

            $users = array();

            $sql = 	"select * from `Customer_Suppliers_Users` u  where SupplierId = '{$vendorId}' ORDER BY UserStatus desc, `CreateTime` desc; ";

            $query = $this->db->query($sql);

            if($query->num_rows() >0 )
            {
                $result = $query->result_array();

                foreach($result as $row){

                    if ( $row['Id'] == null || empty($row['Id']))
                        continue;

                    $users[] = array(

                        "user_id" => $row['Id'] ,
                        "user_email" => $row['UserEmail'],
                        "contact_name" => $row['UserContact'],
                        "position" => $row['UserPosition'],
                        "contact_phone" => $row['UserPhone'],
                        "status" => $row['UserStatus'],
                        "register_time" => $row['RegisterTime']
                    );
                }
            }

            $this->cache->memcached->save($cashKey, $users);
        }

        return $users;

    }



    public function reloadVendors($marketId){
        $this->cache->memcached->save($marketId, null);
    }

    public function reloadVendorUsers($vendorId){
        $this->cache->memcached->save($vendorId, null);
    }


		
}
