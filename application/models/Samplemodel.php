<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SampleModel extends CI_Model {


    //生成模拟数据
    //,$daterange,$raterange,$amountrange
    public function generate($supplier,$invoiceNum){


        $maxAmount = 50000.00;
        $minAmount = 3000.00;

        $max_supplier = count($supplier) - 1;
    
        $list = array();
    
        foreach ($supplier as $key=>$s)
        {             

            //随机一个发票数
            $_invoiceNum =  intval( $invoiceNum * rand(80,110)/100 );
    
            for($i = 0 ;$i < $_invoiceNum ; $i++)
            {

                $amount = rand($minAmount, $maxAmount) + rand ( 1,99)/100;

                $days =  intval( rand(40,120) );

                $paydate = date('Y-m-d',strtotime("+$days day",time()));

                $invoce = strtolower( $s["Vendorcode"])."_".str_replace('-','',date('Y-m-d',strtotime("-$days day",time()))).rand(1,99);
        
                array_push($list,
                    array(
                        'vendorcode' => $s["Vendorcode"],
                        'invoiceno' => $invoce,
                        'invoicedate' => date('Y-m-d',strtotime("-$days day",time())) ,
                        'amount' => $amount,
                        'estpaydate' => $paydate
                    )
                );

            }
    
        }
    
    
        return $list;
    }

    
    private static function randSupplier($num, $original = array())
    {
        $tmp = [
                'Abliy','Blanda','Coco','Divo','Evno','Folay','Gooye','Hovg','Itelija','Jista','Komoni','Linda',
                'Marda','Novad','Opeeli','Prohli','Quom','Ruglo','Simda','Tenli','Uova','Vipe','Wuli','Xezl','Yolada','Zida',
                'S0_Abliy','S0_Blanda','S0_Coco','S0_Divo','S0_Evno','S0_Folay','S0_Gooye','S0_Hovg','S0_Itelija','S0_Jista','S0_Komoni','S0_Linda',
                'S0_Marda','S0_Novad','S0_Opeeli','S0_Prohli','S0_Quom','S0_Ruglo','S0_Simda','S0_Tenli','S0_Uova','S0_Vipe','S0_Wuli','S0_Xezl','S0_Yolada','S0_Zida',
                'S1_Abliy','S1_Blanda','S1_Coco','S1_Divo','S1_Evno','S1_Folay','S1_Gooye','S1_Hovg','S1_Itelija','S1_Jista','S1_Komoni','S1_Linda',
                'S1_Marda','S1_Novad','S1_Opeeli','S1_Prohli','S1_Quom','S1_Ruglo','S1_Simda','S1_Tenli','S1_Uova','S1_Vipe','S1_Wuli','S1_Xezl','S1_Yolada','S1_Zida',
                'S2_Abliy','S2_Blanda','S2_Coco','S2_Divo','S2_Evno','S2_Folay','S2_Gooye','S2_Hovg','S2_Itelija','S2_Jista','S2_Komoni','S2_Linda',
                'S2_Marda','S2_Novad','S2_Opeeli','S2_Prohli','S2_Quom','S2_Ruglo','S2_Simda','S2_Tenli','S2_Uova','S2_Vipe','S2_Wuli','S2_Xezl','S2_Yolada','S2_Zida',
                'S3_Abliy','S3_Blanda','S3_Coco','S3_Divo','S3_Evno','S3_Folay','S3_Gooye','S3_Hovg','S3_Itelija','S3_Jista','S3_Komoni','S3_Linda',
                'S3_Marda','S3_Novad','S3_Opeeli','S3_Prohli','S3_Quom','S3_Ruglo','S3_Simda','S3_Tenli','S3_Uova','S3_Vipe','S3_Wuli','S3_Xezl','S3_Yolada','S3_Zida',
                'S4_Abliy','S4_Blanda','S4_Coco','S4_Divo','S4_Evno','S4_Folay','S4_Gooye','S4_Hovg','S4_Itelija','S4_Jista','S4_Komoni','S4_Linda',
                'S4_Marda','S4_Novad','S4_Opeeli','S4_Prohli','S4_Quom','S4_Ruglo','S4_Simda','S4_Tenli','S4_Uova','S4_Vipe','S4_Wuli','S4_Xezl','S4_Yolada','S4_Zida',
                'S5_Abliy','S5_Blanda','S5_Coco','S5_Divo','S5_Evno','S5_Folay','S5_Gooye','S5_Hovg','S5_Itelija','S5_Jista','S5_Komoni','S5_Linda',
                'S5_Marda','S5_Novad','S5_Opeeli','S5_Prohli','S5_Quom','S5_Ruglo','S5_Simda','S5_Tenli','S5_Uova','S5_Vipe','S5_Wuli','S5_Xezl','S5_Yolada','S5_Zida',
                'L0_Abliy','L0_Blanda','L0_Coco','L0_Divo','L0_Evno','L0_Folay','L0_Gooye','L0_Hovg','L0_Itelija','L0_Jista','L0_Komoni','L0_Linda',
                'L0_Marda','L0_Novad','L0_Opeeli','L0_Prohli','L0_Quom','L0_Ruglo','L0_Simda','L0_Tenli','L0_Uova','L0_Vipe','L0_Wuli','L0_Xezl','L0_Yolada','L0_Zida',
                'L1_Abliy','L1_Blanda','L1_Coco','L1_Divo','L1_Evno','L1_Folay','L1_Gooye','L1_Hovg','L1_Itelija','L1_Jista','L1_Komoni','L1_Linda',
                'L1_Marda','L1_Novad','L1_Opeeli','L1_Prohli','L1_Quom','L1_Ruglo','L1_Simda','L1_Tenli','L1_Uova','L1_Vipe','L1_Wuli','L1_Xezl','L1_Yolada','L1_Zida',
                'L2_Abliy','L2_Blanda','L2_Coco','L2_Divo','L2_Evno','L2_Folay','L2_Gooye','L2_Hovg','L2_Itelija','L2_Jista','L2_Komoni','L2_Linda',
                'L2_Marda','L2_Novad','L2_Opeeli','L2_Prohli','L2_Quom','L2_Ruglo','L2_Simda','L2_Tenli','L2_Uova','L2_Vipe','L2_Wuli','L2_Xezl','L2_Yolada','L2_Zida',
                'L3_Abliy','L3_Blanda','L3_Coco','L3_Divo','L3_Evno','L3_Folay','L3_Gooye','L3_Hovg','L3_Itelija','L3_Jista','L3_Komoni','L3_Linda',
                'L3_Marda','L3_Novad','L3_Opeeli','L3_Prohli','L3_Quom','L3_Ruglo','L3_Simda','L3_Tenli','L3_Uova','L3_Vipe','L3_Wuli','L3_Xezl','L3_Yolada','L3_Zida',
                'L4_Abliy','L4_Blanda','L4_Coco','L4_Divo','L4_Evno','L4_Folay','L4_Gooye','L4_Hovg','L4_Itelija','L4_Jista','L4_Komoni','L4_Linda',
                'L4_Marda','L4_Novad','L4_Opeeli','L4_Prohli','L4_Quom','L4_Ruglo','L4_Simda','L4_Tenli','L4_Uova','L4_Vipe','L4_Wuli','L4_Xezl','L4_Yolada','L4_Zida',
                'L5_Abliy','L5_Blanda','L5_Coco','L5_Divo','L5_Evno','L5_Folay','L5_Gooye','L5_Hovg','L5_Itelija','L5_Jista','L5_Komoni','L5_Linda',
                'L5_Marda','L5_Novad','L5_Opeeli','L5_Prohli','L5_Quom','L5_Ruglo','L5_Simda','L5_Tenli','L5_Uova','L5_Vipe','L5_Wuli','L5_Xezl','L5_Yolada','L5_Zida',
                'D0_Abliy','D0_Blanda','D0_Coco','D0_Divo','D0_Evno','D0_Folay','D0_Gooye','D0_Hovg','D0_Itelija','D0_Jista','D0_Komoni','D0_Linda',
                'D0_Marda','D0_Novad','D0_Opeeli','D0_Prohli','D0_Quom','D0_Ruglo','D0_Simda','D0_Tenli','D0_Uova','D0_Vipe','D0_Wuli','D0_Xezl','D0_Yolada','D0_Zida',
                'D1_Abliy','D1_Blanda','D1_Coco','D1_Divo','D1_Evno','D1_Folay','D1_Gooye','D1_Hovg','D1_Itelija','D1_Jista','D1_Komoni','D1_Linda',
                'D1_Marda','D1_Novad','D1_Opeeli','D1_Prohli','D1_Quom','D1_Ruglo','D1_Simda','D1_Tenli','D1_Uova','D1_Vipe','D1_Wuli','D1_Xezl','D1_Yolada','D1_Zida',
                'D2_Abliy','D2_Blanda','D2_Coco','D2_Divo','D2_Evno','D2_Folay','D2_Gooye','D2_Hovg','D2_Itelija','D2_Jista','D2_Komoni','D2_Linda',
                'D2_Marda','D2_Novad','D2_Opeeli','D2_Prohli','D2_Quom','D2_Ruglo','D2_Simda','D2_Tenli','D2_Uova','D2_Vipe','D2_Wuli','D2_Xezl','D2_Yolada','D2_Zida',
                'D3_Abliy','D3_Blanda','D3_Coco','D3_Divo','D3_Evno','D3_Folay','D3_Gooye','D3_Hovg','D3_Itelija','D3_Jista','D3_Komoni','D3_Linda',
                'D3_Marda','D3_Novad','D3_Opeeli','D3_Prohli','D3_Quom','D3_Ruglo','D3_Simda','D3_Tenli','D3_Uova','D3_Vipe','D3_Wuli','D3_Xezl','D3_Yolada','D3_Zida',
                'D4_Abliy','D4_Blanda','D4_Coco','D4_Divo','D4_Evno','D4_Folay','D4_Gooye','D4_Hovg','D4_Itelija','D4_Jista','D4_Komoni','D4_Linda',
                'D4_Marda','D4_Novad','D4_Opeeli','D4_Prohli','D4_Quom','D4_Ruglo','D4_Simda','D4_Tenli','D4_Uova','D4_Vipe','D4_Wuli','D4_Xezl','D4_Yolada','D4_Zida',
                'D5_Abliy','D5_Blanda','D5_Coco','D5_Divo','D5_Evno','D5_Folay','D5_Gooye','D5_Hovg','D5_Itelija','D5_Jista','D5_Komoni','D5_Linda',
                'D5_Marda','D5_Novad','D5_Opeeli','D5_Prohli','D5_Quom','D5_Ruglo','D5_Simda','D5_Tenli','D5_Uova','D5_Vipe','D5_Wuli','D5_Xezl','D5_Yolada','D5_Zida',
            ]; 
        
        $list = array();

        $index = count($original) > 0 ? count($original) : 0;
        $num = count($original) + $num;

        if($num > count($tmp))
            $num = count($tmp);

        
        for($i = $index; $i < $num ; $i++)
        {            
            $list[] = $tmp[$i] ;                                                         
        }

        $file = BASEPATH."tmp/supplier.txt";

        file_put_contents($file, json_encode(array_merge($original,$list),true));


        return $list;
    }
       
    
    

}
