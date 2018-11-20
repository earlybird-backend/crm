<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class HistoryModel extends CI_Model {


	public function __construct()    {
        parent::__construct();
        $this->db = $this->load->database('cisco',true);
    }



    public function getAwardInvoiceList($cashpoolCode, $begindate = null , $enddate = null, $isManual = null)
    {

        $sql = "
            select  `Vendorcode` ,
              `InvoiceNo`,
              `AwardDate` ,
              `PayDate` ,
              `PayAmount` ,
              `PayDpe` ,
              `PayDiscount`,
              `IsManual`
              from Customer_OptimalAwards
              where CashpoolCode = '{$cashpoolCode}'
        ";


            if ($begindate != null) {

                $sql .= " and AwardDate >= '{$begindate}' ";
            }
            if ($enddate != null) {

                $sql .= " and AwardDate < '{$enddate}' ";

            }


        if( $isManual != null){
            $sql .= " and IsManual = '{$isManual}' ";
        }else{
            $sql .= " and IsManual = 0 ";
        }

        $sql .= " order by AwardDate;" ;
        $query = $this->db->query($sql);

        return $query->result_array();
    }

     // Get category
	public function getDailyAwardList( $cashpoolCode, $begindate = null , $enddate = null, $isManual = 0)
    {

        $sql = "select Id,
              `AwardDate`, 
              `PayDate`,
              `PayAmount` ,
              `PayDiscount` ,
              `InvoiceCount`,
              `AvgDpe` ,
              `AvgDiscount` ,
              `AvgAPR`
              from `Customer_DailyAwards` where CashpoolCode = '{$cashpoolCode}'";


            if ($begindate != null) {

                $sql .= " and AwardDate >= '{$begindate}' ";
            }
            if ($enddate != null) {

                $sql .= " and AwardDate < '{$enddate}' ";

            }


        if( $isManual != null){
            $sql .= " and AwardType = '{$isManual}'";
        }else{
            $sql .= " and AwardType = 0 ";
        }

        $sql .= " order by AwardDate desc;" ;

        $query = $this->db->query($sql);

        return $query->result_array();
    }


}
