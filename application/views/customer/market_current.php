<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>

<?php

$paymentType = array("month" => "月付","week" => "周付", "day" => "次日付");

?>
<div class="clearing-status">
    <div class="grapha-header">
        <p>
            <label>市场名称：</label>
            <span> <?php echo $market["CompanyName"]."|".$market["CompanyDivision"]."(".$market["CurrencyName"].")"; ?></span>
        </p>
        <p>
            <label>期望年化率|成本年化率：</label>
            <span> <?php echo $market["ExpectAPR"]."% | ".$market["MiniAPR"]."%"; ?></span>
        </p>
        <p>
            <label>今日｜早付日：</label>
            <span> <?php echo $market["ServiceDate"]." | ".$market["NextPaydate"]; ?></span>
        </p>
        <p>
            <label>计划资金｜可用资金：</label>
            <span> <?php echo round($market["AvailableAmount"],2)." | ".round($market["AutoAmount"],2); ?></span>
        </p>
        <p>
            <label>支付周期：</label>
            <span>
                <?php
                        switch ( strtolower($market["PaymentType"])){
                            case "month":
                                echo "每月" . ($market["PaymentDay"] > 0 ? "第{$market["PaymentDay"]}天支付" : "最后1天支付") ;
                                break;
                            case "week":
                                echo "每周" . ($market["PaymentDay"] < 6 ? "第".(intval($market["PaymentDay"]) + 1)."天支付" : "最后1天支付") ;
                                break;
                            case "day":
                                echo "每隔 {$market["PaymentDay"]} 个交易日支付";
                                break;
                            default:
                                break;
                        }
                ?>
            </span>
        </p>
    </div>
</div>

<div class="" style="margin-bottom:20px;">
        <div class="row">
            <div class="col-md-4">

               <div class="stat-main">

                    <div class="stat-header">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i> 市场总值
                    </div>
                    <div class="stat-content grapha-black">

                            <div class="content-main">
                                <?php echo $stat["currencySign"].$stat['total']['discount']; ?>
                            </div>
                            <div class="content-add">
                                <p><?php echo $stat['total']['apr']; ?>% 年化率</p>
                                <p><?php echo $stat['total']['dpe']; ?> 提前天数</p>
                            </div>
                    </div>
                   <div class="stat-footer">
                       <?php echo $stat["currencySign"].$stat['total']['paid']; ?> 应付
                   </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-main">
                    <div class="stat-header">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i> 清算总值
                    </div>

                    <div class="stat-content grapha-green">
                            <div class="content-main">
                                <?php echo $stat["currencySign"].$stat['clear']['discount']; ?>
                            </div>
                            <div class="content-add">
                                <p><?php echo $stat['clear']['apr']; ?>% 年化率</p>
                                <p><?php echo $stat['clear']['dpe']; ?> 提前天数</p>
                            </div>
                    </div>
                    <div class="stat-footer">
                        <?php echo $stat["currencySign"].$stat['clear']['paid']; ?> 应付
                    </div>
                </div>
            </div>
            <div class="col-md-4">
        <div class="stat-main">
        	<div class="stat-header">
        		<i class="fa fa-bar-chart" aria-hidden="true"></i> 未清算总值
        	</div>

            <div class="stat-content grapha-red">
                    <div class="content-main">
                        <?php echo $stat["currencySign"].$stat['noclear']['discount']; ?>
                    </div>
                    <div class="content-add">
                        <p><?php echo $stat['noclear']['apr']; ?>% 年化率</p>
                        <p><?php echo $stat['noclear']['dpe']; ?> 提前天数</p>
                    </div>
            </div>
            <div class="stat-footer">
                <?php echo $stat["currencySign"].$stat['noclear']['paid']; ?> 应付
            </div>
		</div>
    
    </div>
        </div>
</div>

<div class="clearing-status">
        <div class="grapha-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i> Current Market</h5>
                </div>
                <div class="col-md-6">
                    <!--<h5 class="pull-right"><a href="#" data-toggle="modal" data-target="#supplier_add"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Supplier</a></h5>
    <a href="javascript:void(0)" data-toggle="modal" data-target="#supplier_add" ><i class="fa fa-plus" aria-hidden="true"></i> 新增供应商</a></h4>-->
                </div>
            </div>
        </div>
        <div class="graph-body">
            <div class="table-body" style="padding:25px;">
                <table id="myTable" class="display" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="text-align: center;width:140px;"><span>序号</span></th>
                        <th style="text-align: center;width:140px;"><span>供应商</span></th>
                        <th style="text-align: center;"><span>供应商ID</span></th>
                        <th style="text-align: center;"><span>开价时间</span></th>
                        <th style="text-align: center;"><span>开价APR</span></th>
                        <th style="text-align: center;"><span>获得APR</span></th>
                        <th style="text-align: center;"><span>折扣金额</span></th>
                        <th style="text-align: center;"><span>清算应收</span></th>
                        <th style="text-align: center;"><span>未清算应收</span></th>
                        <th style="text-align: center;"><span>最小成交金额</span></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if( isset($list) && is_array($list) ) {

                        $key = 0;
                        foreach($list as $i => $v){
                            $key += 1;
                            ?>
                            <tr>

                                <td style="text-align: center;width: 100px;"><?php echo $key  ; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['supplier']; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $i; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['offerTime']; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['offerAPR']; ?>%</td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['getAPR']; ?>%</td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['discount']; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['clear']; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['noclear']; ?></td>
                                <td style="text-align: center;width: 100px;"><?php echo $v['minPaid'] > 0 ? strval($v['minPaid']) : '最小发票'; ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        var selectedCount = 0;

        $(document).ready(function(){

            var lastIdx = null;

            var table = $('#myTable').DataTable({
                bStateSave:true,
                bFiltered:false,
                info:true,
                ordering:false,
                searching:false,
                bLengthChange: true,
                paging:true,
            });

            $('#myTable tbody')
                .on( 'mouseover', 'td', function () {

                    if (table.cell(this).index() === undefined || table.cell(this).index() === null)
                        return;

                    var colIdx = table.cell(this).index().column;

                    if ( colIdx !== lastIdx && colIdx > 0 ) {
                        $( table.cells().nodes() ).removeClass( 'highlight' );
                        $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
                    }
                } )
                .on( 'mouseleave', function () {
                    $(table.cells().nodes() ).removeClass( 'highlight' );
                } );

            $('.v-detail').click(function(){
                var v_id = $(this).closest('tr').attr('id');
                location.href = "<?php echo base_url($link."/supplier_list?code=");?>"+v_id;
            });

            $('.history').click(function(){
                var v_id = $(this).closest('tr').attr('id');
                location.href = "<?php echo base_url($link."/history");?>?vid="+v_id;
            });
        });


        function show_detail(item){

            $('#'+item).toggle();
        }

    </script>


<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>