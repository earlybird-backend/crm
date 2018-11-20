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

		.filter-box .row button{
			padding: 0px 10px;
		}

</style>

<!-- main content start -->
    <div class="content-strip">
        <div class="clearing-status vt-clearing-status">
            <div class="table-detail">
                <div class="table-body filter-box" style="padding:25px;">
                    <div class="row">
						<div class="col-sm-4">
								<label >Days Paid Early</label>
								<select name="early">
									<option value="1" <?php echo ($early==1)?"selected":"" ?>>Less than 15 days</option>
									<option value="2" <?php echo ($early==2)?"selected":"" ?>>15 days to 30 days</option>
									<option value="3" <?php echo ($early==3)?"selected":"" ?>>30 to 45 days</option>
									<option value="4" <?php echo ($early==4)?"selected":"" ?>>45 days or more</option>
									<option value="5" <?php echo ($early==5)?"selected":"" ?>>custom</option>
								</select>
						</div>
						<div class="col-sm-8">
							<label >Original PayDate</label>
							<label >From</label>
							<input type="date" name="startdate" <?php echo ($early!=5)?"disabled":"" ?> value="<?php echo $start ?>" />
							<label >To</label>
							<input type="date" name="enddate" <?php echo ($early!=5)?"disabled":"" ?>  value="<?php echo $end ?>"/>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<input type="hidden" name="code" value="<?php echo $code ?>" />
							<input type="hidden" name="nextpay" value="<?php echo $nextpay ?>" />
							<label >Invoice Status</label>
							<select name="status">
								<option value="All" <?php echo ($status=='All')?"selected":"" ?>>All</option>
								<option value="Eligible" <?php echo ($status=='Eligible')?"selected":"" ?>>Eligible</option>
								<option value="Ineligible" <?php echo ($status=='Ineligible')?"selected":"" ?>>Ineligible</option>
								<option value="Awarded" <?php echo ($status=='Awarded')?"selected":"" ?>>Awarded</option>
								<option value="Adjustment" <?php echo ($status=='Adjustment')?"selected":"" ?>>Adjustment</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label >Invoice Amount</label>
							<select name="amount">
								<option value="1" <?php echo ($amount==1)?"selected":"" ?>>Less than 25,000</option>
								<option value="2" <?php echo ($amount==2)?"selected":"" ?>>25,000 to 50,000</option>
								<option value="3" <?php echo ($amount==3)?"selected":"" ?>>50,000 to 75,000</option>
								<option value="4" <?php echo ($amount==4)?"selected":"" ?>>75,000 or more</option>
							</select>
						</div>
						<div class="col-sm-4">
							<button class="btn btn-default filter-btn">确定</button>
							<input type="file" accept="application/vnd.ms-excel" id="sync" name="sync" style="display: none" />
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
											<th style="text-align: center;"><span>供应商编号</span></th>
											<th style="text-align: center;"><span>供应商</span></th>
											<th style="text-align: center;"><span>发票编号</span></th>
											<th style="text-align: center;"><span>发票金额</span></th>
											<th style="text-align: center;"><span>原支付日期</span></th>
											<th style="text-align: center;"><span>发票状态</span></th>
										</tr>
										</thead>
										<tbody>
										<?php foreach($rs as $v): ?>
											<tr>
												<td style="text-align: center;"><?php echo $v['Vendorcode'] ?></td>
												<td style="text-align: center;"><?php echo $v['Supplier'] ?></td>
												<td style="text-align: center;"><?php echo $v['InvoiceNo'] ?></td>
												<td style="text-align: center;"><?php echo $v['InvoiceAmount'] ?></td>
												<td style="text-align: center;"><?php echo $v['EstPaydate'] ?></td>
												<td style="text-align: center;"><?php echo $invStatus[ $v['Status'] ]; ?></td>
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

            var invoiceTable = $('#invoiceTable').DataTable({
                bStateSave:true,
                bFiltered:false,
                info:true,
                ordering:false,
                searching:false,
                bLengthChange: true,
                paging:true,
            });

						$('select[name="early"]').change(function(){
						    if($(this).val()==5){
						        $('input[name="startdate"]').attr('disabled',false);
						        $('input[name="enddate"]').attr('disabled',false);
								}else{
                    $('input[name="startdate"]').attr('disabled',true);
                    $('input[name="enddate"]').attr('disabled',true);
								}
						});

            $('.filter-btn').click(function(){
                var code = $('input[name="code"]').val();
                var nextpay = $('input[name="nextpay"]').val();
                var status = '&status='+$('select[name="status"]').val();
                var early = '&early='+$('select[name="early"]').val();
                var amount = '&amount='+$('select[name="amount"]').val();
                var start = '&start='+$('input[name="startdate"]').val();
                var end = '&end='+$('input[name="enddate"]').val();

                if(early.indexOf('5')!=-1){
                    window.location.href = '/customer/market/invoice_detail?nextpay='+nextpay+'&code='+code+status+early+amount+start+end;
								}else{
                    window.location.href = '/customer/market/invoice_detail?nextpay='+nextpay+'&code='+code+status+early+amount;
								}

						});

            $('.sync_btn').click(function(){
                $('input[name="sync"]').click();
						});

            $('input[name="sync"]').change(function(){
                var form = new FormData();
                var xls = document.getElementById('sync').files[0];
                var code = $('input[name="code"]').val();
                form.append('code',code);
                form.append('xls',xls);
                $('.warp').show();
                $.ajax({
                    url: "/customer/market/sync",
                    data: form,
                    type: "POST",
                    dataType: "json",
                    cache: false,//上传文件无需缓存
                    processData: false,//用于对data参数进行序列化处理 这里必须false
                    contentType: false, //必须
                    success: function (result) {
                        if(result.code==-1){
                            alert(result.msg);
												}
                        $('.warp').hide();

                    },
                });
						});
        })

		</script>





<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>