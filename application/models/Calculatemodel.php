<?php


defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @description Science & Native & Creative
 * @copyright   Copyright 2016-2017
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      loudon <loudon@sigmacloud.cc>
 * @package     Weixin
 * @link        http://www.sigmacloud.cc
 */
class Calculatemodel extends MY_Model
{
    
    /*
     * 公式定义 
     * 
    */    
    private static function callFormula($amount,$bidtype,$bidrate,$maturitydate ,$paydate)
    {        
        
        //付款日期与到期日期的天数差
        $day1 = strtotime($paydate);
        $day2 = strtotime($maturitydate);
        $dpe = 0 ;
                        
        
        if ($day1 < $day2) {
                        
            $tmp = $day2;
            $day2 = $day1;
            $day1 = $tmp;

            $days = $day1 > $day2 ? ($day1 - $day2) / 86400 : 0 ;
        }                                              
        
        if( $bidtype == 'discount'){
            $discount = round($amount*$bidrate/100,2) ;
            $yieldreate  = $bidrate;
            $apr = round($discount/$dpe*365/$amount*100,2) ;
        }
        else{
            //时间差的利息金额
            $apr = $bidrate;
            $discount = round($amount/365*$bidrate/100 * $days ,2);
            $yieldreate = round($discount/$amount*100,2);
        }
        
        return array(
            'dpe'   => $dpe,
            'apr' => $apr ,
            'discount'   => $discount,
            'yieldrate' => $yieldreate            
        );
                
    }
      
        
    //计算
    public function calculate($paydate,$source = array()) {
                                
        //判断是否为数组且不为空
        if(is_array($source) && count($source) > 0 )
        {
            $supplier = array();
            $result = array();   
                
            foreach ($source as $r)
            {                 
                
                $tmp = self::callFormula($r['amount'], $r['bidtype'],$r['bidrate'], $r['estpaydate'], $paydate) ;
                
                if(!array_key_exists($r['vendorcode'],$result))
                {
                    
                    $result[$r['vendorcode']] = array(
                        
                        'supplier' => $r['supplier'] ,
                        'vendorcode' => $r['vendorcode'] ,
                        'bidid' => $r['bidid'],
                        'bidrate' => $r['bidrate'],
                        'minpayamount' => $r['minpayamount'],
                        'list' => array()
                    );
                    
                }
                
                array_push($result[$r['vendorcode']]['list'] ,
                    array (
                       'id'  => $r['id'],                       
                       'invoice'  => $r['invoice'] ,                     
                       'estpaydate'  => $r['estpaydate'] ,
                       'amount'   => $r['amount'] ,
                       'dpe' => $tmp['dpe'],
                       'apr' => $tmp['apr'],
                       'discount'  => $tmp['discount'],
                       'yieldrate'=> $tmp['yieldrate']
                    )
                );    
                
            }
                
                        
            return $result;
            
        }else
        {
            return array();
        }
                
    }

    //分析支付方案
    /*
     * 所有折扣均要转为 年化率去计算
     */
    public function analyticalPlan($expecteapr = 5 ,$amount = 0.00,$data = array())
    {
        $result = array();        

            if (isset($data) && is_array($data) && count($data) > 0) {

                $count = count($data);
                for ($i = 1; $i <= $count; $i++) {

                    $rst = self::combination($data, $i);

                    foreach ($rst as $r) {

                        $x = 0; // 总金额
                        $y = 0; //总收益
                        $z = 0; //ARP收益率
                        $w = 0; //加权更新平均折扣率
                        $t = 0; //成交发票总金额                        
                        $a = 0;

                        foreach ($r as $v) {
                            $x += $v['amount'];
                            $y += $v['discount'];
                            $z += $v['yieldrate'];
                        }

                        foreach ($r as $v) {
                                $a += round($v['apr'] * $v['amount'] / $x, 2);  //加权平均APR
                                $w += round($v['discount'] * 100 / $x, 2);; //加权平均折扣率
                        }

                        if ($x < $amount && $a >= $expecteapr) {
                            $result[] = array('total_amount' => $x, 'total_discount ' => $y,'count_invouce' => $t , 'avr_discount' => $w, 'avr_apr' => $a, 'source' => $r);
                        }
                    }
                }

                //排序

                foreach ($result as $key => $value) {
                    //$amount[$key] = $value['amount'];
                    $profile[$key] = $value['avg_discount'];
                    //$yieldrate[$key] = $value['rate'] / count($value['list']);
                }

                $n = count($result);

                array_multisort($profile, $result);  //收益最高
                //array_multisort($amount,$result);   //金额最高
                //array_multisort($yieldrate,$result);   //平均收益最高

                return $result[$n - 1];

            }


        return $result;

    }           

    private static function combination($a,$m)
    {
        
            $r = array();
            $n = count($a);
             
            if ($m <= 0 || $m > $n) {
                return $r;
            }
        
            for ($i=0; $i<$n; $i++) {
                        
                $t = array($a[$i]);
        
              
                if ($m == 1 ) {
        
                    $r[] = $t ;
        
                } else {
                     
                    $b = array_slice($a, $i+1);
                    $c = self::combination($b, $m-1);
        
                    foreach ($c as $v) {
                        $r[] = array_merge($t, $v);
                    }
                }
               
            }
        
            return $r;        
        
    }
    
    
    private static function randSupplier($num)
    {
        $tmp = [
                'Abliy','Blanda','Coco','Divo','Evno','Folay','Gooye','Hovg','Itelija','Jista','Komoni','Linda',
                'Marda','Novad','Opeeli','Prohli','Quom','Ruglo','Simda','Tenli','Uova','Vipe','Wuli','Xezl','Yolada','Zida'        
            ]; 
        
        $list = array();
        
        for($i = 1; $num >= $i; $i++)
        {
            $index = rand(0,25-$i);

            array_push($list, $tmp[$index]) ;            
            array_splice($tmp, $index, 1);                     
        }
        
        return $list;
    }
       
    
    

}
