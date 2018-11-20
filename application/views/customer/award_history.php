<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'customer/__lefter.php'; ?>

<style>        

    td.highlight {
        background-color: whitesmoke !important;
    }
    .vt-clearing-status{margin-bottom: 20px}
		.data-panel ul{
			padding-left: 0;
		}
		.data-panel ul li{
			list-style: none;
			text-align: left;
		}
		.data-panel ul li label{
			margin-right: 5px;
		}

		.filter-box .row{
			margin-bottom: 10px;
		}

		.filter-box .row label{
			margin-right: 10px;
		}

		.filter-box .row input{
			margin-right: 10px;
		}

		.filter-box .row select{
			width: 80px;
		}
		.filter-box .row button{
			padding: 0px 10px;
		}
		.glyphicon-download-alt{
			cursor: pointer;
		}
		.down-csv{
			margin-left: 25px;
		}
</style>

<!-- main content start -->
    <div class="content-strip">
    	<div class="clearing-status vt-clearing-status">
            <div class="table-detail">
                <div class="table-body text-center" style="padding:25px;">
                    <div class="row data-panel">
											<div class="col-sm-3">
												<ul>
													<li><label>总清算发票金额:</label><?php echo $panel['TotalAmount'] ?></li>
													<li><label>发票数量:</label><?php echo $panel['InvoiceCount'] ?></li>
													<li><label>涉及供应商数量:</label><?php echo $panel['VendorCount'] ?></li>
													<li><label>平均年化收益率:</label><?php echo $panel['AvgApr'] ?>%</li>
												</ul>
											</div>
											<div class="col-sm-3">
												<ul>
													<li><label>平台清算金额:</label><?php echo $panel['TotalAmount_A'] ?></li>
													<li><label>清算获得折扣金额:</label><?php echo $panel['TotalDiscount_A'] ?></li>
													<li><label>平均折扣率:</label><?php echo $panel['AvgDiscount_A'] ?>%</li>
													<li><label>平均年化收益率:</label><?php echo $panel['AvgApr_A'] ?>%</li>
												</ul>
											</div>
											<div class="col-sm-3">
												<ul>
													<li><label>手工清算金额:</label><?php echo $panel['TotalAmount_M'] ?></li>
													<li><label>清算获得折扣金额:</label><?php echo $panel['TotalDiscount_M'] ?></li>
													<li><label>平均折扣率:</label><?php echo $panel['AvgDiscount_M'] ?>%</li>
													<li><label>平均年化收益率:</label><?php echo $panel['AvgApr_M'] ?>%</li>
												</ul>
											</div>
											<div class="col-sm-3">
												<ul>
													<li><label>开始日期~结束日期:</label><?php echo $panel['FirstAwardDate'].'~'.$panel['LastAwardDate'] ?></li>
													<li><label>发票早付日期 最小~最大 (天):</label><?php echo $panel['MinPayDPE'].'~'.$panel['MaxPayDPE'] ?></li>
													<li><label>平均早付日期 (天):</label><?php echo $panel['AvgPayDPE'] ?></li>
												</ul>
											</div>
										</div>
                </div>
            </div>
        </div>
        <div class="clearing-status vt-clearing-status">
            <div class="table-detail">
                <div class="table-body filter-box" style="padding:25px;">
                    <div class="row">
											<div class="col-sm-2">
													<input type="hidden" name="marketid" value="<?php echo $marketid ?>" />
													<label >清算日期</label>
													<select name="cleardate">
														<option value="7" <?php echo ($clearday==7)?"selected":"" ?>>近7天</option>
														<option value="15" <?php echo ($clearday==15)?"selected":"" ?>>近15天</option>
														<option value="30" <?php echo ($clearday==30)?"selected":"" ?>>近30天</option>
														<option value="60" <?php echo ($clearday==60)?"selected":"" ?>>近60天</option>
														<option value="90" <?php echo ($clearday==90)?"selected":"" ?>>近90天</option>
														<option value="100" <?php echo ($clearday==100)?"selected":"" ?>>自定义</option>
													</select>
											</div>
											<div class="col-sm-6">
												<label >开始时间</label>
												<input type="date" name="startdate" <?php echo ($clearday==100)?"":"disabled" ?> value="<?php echo $start ?>" />
												<label >结束时间</label>
												<input type="date" name="endtdate" <?php echo ($clearday==100)?"":"disabled" ?> value="<?php echo $end ?>"/>
											</div>
											<div class="col-sm-2">
												<label>清算类型</label>
												<select name="cleartype">
													<option value="-1" <?php echo ($cleartype==-1)?"selected":"" ?>>全部清算</option>
													<option value="1" <?php echo ($cleartype==1)?"selected":"" ?>>平台清算</option>
													<option value="0" <?php echo ($cleartype==0)?"selected":"" ?>>手工清算</option>
												</select>
											</div>
											<div class="col-sm-2">
												<button class="btn btn-default filter-btn">确定</button>
											</div>
										</div>
                </div>
            </div>
        </div>
        <div class="clearing-status vt-clearing-status">
            <div class="table-detail">
                <div class="table-body text-center" style="padding:25px;">
									<table id="myTable" class="display" cellspacing="0">
										<thead>
										<tr>
											<th style="text-align: center;"><span>清算日期</span></th>
											<th style="text-align: center;"><span>清算发票金额</span></th>
											<th style="text-align: center;"><span>成交供应商数量</span></th>
											<th style="text-align: center;"><span>清算发票数量</span></th>
											<th style="text-align: center;"><span>平均早付天数</span></th>
											<th style="text-align: center;"><span>获得折扣金额</span></th>
											<th style="text-align: center;"><span>平均折扣率</span></th>
											<th style="text-align: center;"><span>平均年化收益率</span></th>
											<th style="text-align: center;"><span>发票下载</span></th>
										</tr>
										</thead>
										<tbody>
										<?php foreach($history as $v): ?>
											<tr data-awarddate="<?php echo $v['AwardDate'] ?>">
												<td style="text-align: center;"><?php echo $v['AwardDate'] ?></td>
												<td style="text-align: center;"><?php echo $v['PayAmount'] ?></td>
												<td style="text-align: center;"><?php echo $v['VendorCount'] ?></td>
												<td style="text-align: center;"><?php echo $v['InvoiceCount'] ?></td>
												<td style="text-align: center;"><?php echo $v['AvgDpe'] ?></td>
												<td style="text-align: center;"><?php echo $v['PayDiscount'] ?></td>
												<td style="text-align: center;"><?php echo $v['AvgDiscount'] ?>%</td>
												<td style="text-align: center;"><?php echo $v['AvgAPR'] ?>%</td>
												<td style="text-align: center;"><span class="glyphicon glyphicon-download-alt"></span></td>
											</tr>
										<?php endforeach; ?>
										</tbody>
									</table>
                </div>
            </div>
        </div>
        <!-- clearing end -->
    </div>
    <!--  main content end -->

	<div class="modal fade" tabindex="-1" id="download_invoice" role="dialog">
		<div class="modal-dialog modal-md" style="width: 800px;">
			<div class="modal-content">
					<div class="modal-body">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<div class="list-form market-title">发票下载</div>
						<section class="popup-input" id="section_pop2">
							<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 550px;">
								<div class="table-wrap-sec main-wtd">
									<div class="row close-market-row">
										<div class="col-sm-6">
											<button class="btn btn-success down-csv">下载CSV</button>
										</div>
									</div>
									<div class="table-detail">
										<div class="table-body text-center" style="padding:25px;">
											<table id="invoiceTable" class="display" cellspacing="0">
												<thead>
												<tr>
													<th style="text-align: center;"><span>供应商</span></th>
													<th style="text-align: center;"><span>CODE</span></th>
													<th style="text-align: center;"><span>发票编号</span></th>
													<th style="text-align: center;"><span>发票开票日期</span></th>
													<th style="text-align: center;"><span>原支付日期</span></th>
													<th style="text-align: center;"><span>发票金额</span></th>
												</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="file-sec-wrap-info">
						<button type="submit" class="btn-rectangular">确定</button>
						<iframe style="width: 0;height: 0" id="download_frame"></iframe>
					</div>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        var selectedCount = 0;

        $(document).ready(function(){

            var lastIdx = null;
            var link = "";

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
                } )
                .on('click', 'a', function() {
                    link = "<?php echo base_url($link) . "/";?>" + $(this).attr("name") + "/market_id=" + $(this).parent().parent().attr("id");
                    location.href = link;
                });

            var invoiceTable = $('#invoiceTable').DataTable({
                bStateSave:true,
                bFiltered:false,
                info:true,
                ordering:false,
                searching:false,
                bLengthChange: true,
                paging:true,
            });

            $('select[name="cleardate"]').change(function(){
            	if($(this).val()==100){
                  $('input[name="endtdate"]').attr('disabled',false);
                  $('input[name="startdate"]').attr('disabled',false);
                  $('input[name="endtdate"]').val(getFormatDate(new Date()));
                  $('input[name="startdate"]').val(getFormatDate(new Date()));
							}else{
                  $('input[name="endtdate"]').attr('disabled',true);
                  $('input[name="startdate"]').attr('disabled',true);
                  var newdate = new Date();
                  newdate.setDate((newdate.getDate() + 1) - 1 * $(this).val())
                  startdate = getFormatDate(newdate);
                  $('input[name="endtdate"]').val(getFormatDate(new Date()));
                  $('input[name="startdate"]').val(startdate);
							}
						});

            $('.filter-btn').click(function(){
                var marketid = $('input[name="marketid"]').val();
                var cleardate = "";
                var enddate = getFormatDate(new Date());
                var startdate = getFormatDate(new Date());
                var cleartype = "&type="+$('select[name="cleartype"]').val();
                if($('select[name="cleardate"]').val()=='100'){
                    enddate = $('input[name="endtdate"]').val();
                    startdate = $('input[name="startdate"]').val();
								}else{
										var newdate = new Date();
                    newdate.setDate((newdate.getDate() + 1) - 1 * $('select[name="cleardate"]').val())
                    startdate = getFormatDate(newdate);
								}
                cleardate = "&start="+startdate+"&end="+enddate;
								window.location.href = '/customer/customer/history?marketid='+marketid+cleardate+cleartype+"&clearday="+$('select[name="cleardate"]').val();
						});

            $('.glyphicon-download-alt').click(function(){
                var marketid = $('input[name="marketid"]').val();
                var awarddate = $(this).closest('tr').data('awarddate');
                $('.warp').show();
                $.get('/customer/customer/invoice/'+awarddate+'/'+marketid,function(result){
//                    console.log(result);return;
//                    result = JSON.parse(result);
                    $('#invoiceTable tbody').html('');
                    for(var i=0; i<result.length;i++){
											var tr_class ="";
											if(i%2==0) tr_class = "odd";
											var tr_html = $('<tr class="'+tr_class+'">'
                          +'<td style="text-align: center;">'+result[i].supplier+'</td>'
													+'<td style="text-align: center;">'+result[i].vendorcode+'</td>'
                          +'<td style="text-align: center;">'+result[i].InvoiceNo+'</td>'
                          +'<td style="text-align: center;">'+result[i].InvoiceDate+'</td>'
                          +'<td style="text-align: center;">'+result[i].EstPaydate+'</td>'
                          +'<td style="text-align: center;">'+result[i].InvoiceAmount+'</td></tr>');
											$('#invoiceTable tbody').append(tr_html);
										}

                    $('.warp').hide();
										$('#download_invoice').data('awarddate',awarddate);
                    $('#download_invoice').modal({
                        keyboard:false
                    });
								});

						});

            $('.down-csv').click(function(){
                var marketid = $('input[name="marketid"]').val();
                var awarddate = $('#download_invoice').data('awarddate');
                $('#download_frame').attr('src','/customer/customer/download_invoice/'+awarddate+'/'+marketid);
//                window.open('/customer/customer/download_invoice/'+awarddate+'/'+marketid);
						});
        })

        function getFormatDate(nowDate){
//            var nowDate = new Date();
            var year = nowDate.getFullYear();
            var month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1) : nowDate.getMonth() + 1;
            var date = nowDate.getDate() < 10 ? "0" + nowDate.getDate() : nowDate.getDate();
            return year + "-" + month + "-" + date;
        }







		</script>





<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>