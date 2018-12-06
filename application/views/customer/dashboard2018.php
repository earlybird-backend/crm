<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<!--context-->
<style>
    .dashboard_top_biao
    {
        background-repeat:no-repeat; background-position:20px 0px;height:60px;
    }
    .dashboarda
    {
        width:1130px;
    }
    .dashboarda .boand
    {
        margin: 0 auto;
        width: 95%;
    }
    .dashboarda .boand .row1
    {
        height:20px;
    }
    .dashboarda .boand .row2
    {
        height:25px;
    }
    .dashboarda .boand .row3
    {
        height:45px;
    }
    .dashboarda .boand .row4
    {
        height:18px;padding-left: 15px;margin-top: 5px;
    }
    .dashboarda .boand .row5
    {
        color: #FFFFFF;padding-left: 15px;
    }
    .dashboarda .boand .title
    {
        color: #FFFFFF;font-size: 13px;font-weight: 600px;display:block;
    }
    .dashboarda .boand .dataNumber
    {
        display:block;font-size:30px;font-weight:600;color: #FFFFFF
    }

    .dashboard_middle_1 .labels
    {
        -webkit-border-radius: 3px;
        border-radius: 3px;
        overflow: hidden;
        padding: 2px 3px 2px 5px;
        font-size: 12px;
        font-weight:bold;
        color: #FFFFFF;
        letter-spacing:1.5px;
    }

    .dashboard_top_biao .txtdiv
    {
        margin-left:85px;
    }
    .dashboard_top_biao .txtdivb
    {
        display:block;
        font-size:24px;
        font-weight:600;
    }
    .dashboard_top_biao .txtdivb1
    {
        font-size:12px;
        margin-top: 10px;
    }
    .dashboard_middle_1
    {

    }
    .dashboard_middle_1 .board
    {
        width: 240px;
        height: 150px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        background-repeat: repeat-x;
        overflow: hidden;
        padding-left: 15px;
    }
    #myTable th,td
    {
        text-align: center;
    }
    #myTable th {
        font-size: 12px;
        text-align: center;
    }

    #myTable .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    #myTable .split2:before
    {
        content:" / ";
    }

</style>
<div class="dashboarda">
    <!--小看板-->
    <div class="row boand" style="margin-top:20px">
        <div class="col-md-3 dashboard_top_biao" style="background-image:url(/assets/img/dashboardImg/top_1_1.jpg);">
            <div class="txtdiv">
                <div class="row txtdivb"> 23232323 </div>
                <div class="row txtdivb1">
                    总用户数
                </div>
            </div>
        </div>
        <div class="col-md-3 dashboard_top_biao" style="background-image:url(/assets/img/dashboardImg/top_1_2.jpg);">
            <div class="txtdiv">
                <div class="row txtdivb"> 23232323 </div>
                <div class="row txtdivb1">
                    总访问数
                </div>
            </div>
        </div>
        <div class="col-md-3 dashboard_top_biao" style="background-image:url(/assets/img/dashboardImg/top_1_3.jpg);">
            <div class="txtdiv">
                <div class="row txtdivb">
                    23232323
                </div>
                <div class="row txtdivb1"> 总发票数 </div>
            </div>
        </div>
        <div class="col-md-3 dashboard_top_biao" style="background-image:url(/assets/img/dashboardImg/top_1_4.jpg);">
            <div class="txtdiv">
                <div class="row txtdivb">23232323</div>
                <div class="row txtdivb1"> 总金额 </div>
            </div>
        </div>
    </div>
    <!--小看板-->
    <!--线图-->
    <div class="row boand" style="width:95%;background-color:#98eed4;margin-top: 15px;height: 200px;">
        &nbsp;
    </div>
    <!--线图-->
    <!--看板-->
    <div class="row boand" style="margin-top:15px">
        <div class="col-md-3 dashboard_middle_1">
            <div class="board" style="background-color: #0073b7;background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_1.jpg)">
                <div class="row row1"></div>
                <div class="row row2">
                    <div class="col-md-8 title">客户统计</div>
                    <div class="col-md-4"><span class="labels" style="background-color: #18bc9c;">实时</span></div>
                </div>
                <div class="row row3">
                    <div class="col-md-8 dataNumber">1234</div>
                </div>
                <div class="row row4">
                    <div class="col-md-12" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_1_1.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;">
                        1234
                    </div>
                </div>
                <div class="row row5 title">当前客户总数</div>
            </div>
        </div>
        <div class="col-md-3 dashboard_middle_1">
            <div class="board" style="background-color: #45a0dd;background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_2.jpg)">
                <div class="row row1"></div>
                <div class="row row2">
                    <div class="col-md-8 title">市场活跃</div>
                    <div class="col-md-4"><span class="labels" style="background-color: #3498db;">实时</span></div>
                </div>
                <div class="row row3">
                    <div class="col-md-8 dataNumber">1043</div>
                </div>
                <div class="row row4">
                    <div class="col-md-12" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_2_1.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;">
                        2592
                    </div>
                </div>
                <div class="row row5 title">当前有资金的市场</div>
            </div>
        </div>
        <div class="col-md-3 dashboard_middle_1">
            <div class="board" style="background-color: #8884bd;background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_3.jpg)">
                <div class="row row1"></div>
                <div class="row row2">
                    <div class="col-md-8 title">供应商统计</div>
                    <div class="col-md-4"><span class="labels" style="background-color: #2c3e50;">实时</span></div>
                </div>
                <div class="row row3">
                    <div class="col-md-6 dataNumber">1043</div>
                    <div class="col-md-6 dataNumber">6754</div>
                </div>
                <div class="row row4">
                    <div class="col-md-6" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_3_1.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;padding-left: 18px;">
                        <span style="font-size:12px;">参与开价人数</span>
                    </div>
                    <div class="col-md-6" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_3_2.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;padding-left: 18px;">
                        <span style="font-size:12px;">参与开价次数</span>
                    </div>
                </div>
                <div class="row row5 title"></div>
            </div>
        </div>
        <div class="col-md-3 dashboard_middle_1">
            <div class="board" style="background-color: #1acdaa;background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_4.jpg)">
                <div class="row row1"></div>
                <div class="row row2">
                    <div class="col-md-8 title">发票统计</div>
                    <div class="col-md-4"><span class="labels" style="background-color: #2c3e50;">实时</span></div>
                </div>
                <div class="row row3">
                    <div class="col-md-6 dataNumber">5302</div>
                    <div class="col-md-6 dataNumber">8327</div>
                </div>
                <div class="row row4">
                    <div class="col-md-6" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_4_1.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;padding-left: 18px;">
                        <span style="font-size:12px;">参与开价人数</span>
                    </div>
                    <div class="col-md-6" style="background-image: url(/assets/img/dashboardImg/dashboard_middle_1_bg_4_2.png);background-repeat: no-repeat;line-height: 14px;color: #FFFFFF;padding-left: 18px;">
                        <span style="font-size:12px;">参与开价次数</span>
                    </div>
                </div>
                <div class="row row5 title"></div>
            </div>
        </div>
    </div>
    <!--看板-->
</div>
<!--列表-->
<div style="margin: 50px 0px 50px 0px;">
    <table id="myTable" class="display" cellspacing="0">
        <thead>
        <tr>
            <th style="width: 50px">序号</th>
            <th style="width:240px;">公司名称</th>
            <th>市场状态</th>
            <th>&sdot;可用资金<br>&sdot;启用日期</th>
            <th style="width: 200px;">
                <div style="width: 200px">
                    <div style="margin: 0 auto;text-align: left">
                        &sdot;有效的应付发票(总金额/数量)<br>
                        &sdot;已清算发票(总金额/数量)
                    </div>
                </div>
            </th>
            <th>下个早付日期</th>
            <th style="width: 200px;">当前可清算发票<br>(总金额/折扣金额/平均年化率)</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $i = 1;
        foreach ($markets as $market)
        {?>
        <tr>
            <td><?php echo $i++ ?></td>
            <td><?php echo $market['CashpoolName']."({$market['Currency']})" ?></td>
            <td>
                <?php
                echo $market['CashpoolStatus']==1?'正常竞价':
                    $market['CashpoolStatus']==2?'无可用发票':'未参与市场';
                ?>
            </td>
            <td>
                <span class="split1"><?php echo number_format($market['ValidAmount'],2)?></span><br>
                <span class="split1"><?php echo $market['AllocateDate']?></span>
            </td>
            <td>
                <span class="split1"><?php echo number_format($market['TotalAmount'],2)?></span>
                <span class="split2"></span>
                <span class="split1"><?php echo intval($market['TotalInvoiceCount'])?></span><br>
                <span class="split1"><?php echo number_format(0.0,2)?></span>
                <span class="split2"></span>
                <span class="split1"><?php echo intval(0)?></span>
            </td>
            <td>
                <?php echo $market['EstPaydate']?>
            </td>
            <td>
                <span class="split1"><?php echo number_format($market['PayAmount'],2)?></span>
                <span class="split2"></span>
                <span class="split1"><?php echo number_format($market['PayDiscount'],2)?></span>
                <span class="split2"></span>
                <span class="split1"><?php echo number_format($market['AvgAPR'],2)?></span>
            </td>
        </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();

    });
    $('#myTable').DataTable( {
        bStateSave:true,
        bFiltered:false,
        info:false,
        ordering:false,
        searching:false,
        bLengthChange: true,
        paging:false
    } );
</script>
<!--context-->
<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>
