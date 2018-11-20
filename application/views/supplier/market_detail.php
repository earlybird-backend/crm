<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'supplier/__lefter.php'; ?>

<style>        

    td.highlight {
        background-color: whitesmoke !important;
    }
		.market-title{
			background: #ffffff;
			padding:25px;
		}
		.market-title h2{
			margin: 0;
		}
		.market-title .title-filter{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}

		.market-title .title-filter input[type='date']{
			margin-right: 25px;
		}
		.market-title .title-filter h4,.market-title .title-filter select{
			margin-right: 15px;
		}
		.sub-title{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}
		.sub-title h4{
			margin-right:15px ;
		}
		.table-body h4{
			font-weight: bold;
		}
		.modal-dialog{
			width: 60%;
		}
		.modal-dialog .modal-body{
			padding:20px;
		}
		button.close,button.close:hover{
			color:#000;
		}
		.data-box td{
			text-align: center;
		}
		.glyphicon-align-justify{
			cursor: pointer;
		}
		#vendorcode:disabled{
			background: #eeeeee;
		}
		.result-today{
			margin-top: 15px;
		}
		.result-today table tr td{
			text-align: left;
		}
		.result-today .title{
			font-weight: bold;
			width: 100px;
			text-align: right;
		}

</style>

<div class="clearing-status market-title">
	<h3>市场名称：<?php echo (isset($info)&&is_array($info))?$info[0]['buyer']:'' ?></h3>
	<div class="title-filter">
			<input type="date" id="filter-date" value="<?php echo $querydate ?>">
			<input type="hidden" name="cashpoolcode" value="<?php echo $cashpoolcode ?>"/>
			<input type="hidden" name="cashpoolid" value="<?php echo $cashpoolid ?>"/>
			<h4>选供应商</h4>
			<select id="vendorcode" name="vendorcode" <?php echo ($vendorcode=="all")?'disabled':'' ?>>
				<?php foreach($vendors as $item): ?>
				<option value="<?php echo $item['vendorcode'] ?>"
						<?php echo ($vendorcode==$item['vendorcode'])?'selected':'' ?>>
						<?php echo $item['supplier'] ?></option>
				<?php endforeach ?>
			</select>
			<input type="checkbox" <?php echo ($vendorcode=="all")?'checked':'' ?> id="select-all" value="all"> 全部

	</div>
</div>
<!-- main content start -->
    <div class="">

    	<div class="clearing-status">

            <div class="table-detail">
                  <div class="table-body" style="padding:25px;">
                      <h4>开价记录</h4>
                      <table id="myTable" class="display data-box" cellspacing="0">
                      <thead>
                           <tr>
                             <th style="text-align: center;width: 80px;"><span>序号</span></th>
                             <th style="text-align: center;width:120px;"><span>供应商</span></th>
                             <th style="text-align: center;width: 100px;"><span>开价时间</span></th>
                             <th style="text-align: center;width: 120px;"><span>开价</span></th>
                             <th style="text-align: center;width: 80px;"><span>APR</span></th>
                             <th style="text-align: center;width: 80px;"><span>折扣率</span></th>
                             <th style="text-align: center;width: 80px;"><span>最低成交金额</span></th>
                             <th style="text-align: center;width: 60px;"><span>折扣金额</span></th>
                             <th style="text-align: center;width: 100px;"><span>清算应收</span></th>
                             <th style="text-align: center;width: 120px;"><span>未清算应收</span></th>
                             <th style="text-align: center;width: 100px;"><span>发票明细</span></th>

                          </tr>
                      </thead>
                      <tbody>
                            <?php
                                if( isset($price) && is_array($price) ) {
                                    foreach($price as $index=>$v){
                            ?>
                                <tr>
                                    <td style="text-align: center;width: 80px;"><?php echo $index+1 ?></td>
                                    <td style="text-align: center;width: 120px;"><?php echo $v['supplier']; ?>  </td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['createtime']; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['bidtype'].''.$v['bidrate'].'%'; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['apr'] ?>%</td>
                                    <td style="text-align: center;width: 80px;"><?php echo round($v['discount']/$v['clearpayment']*100,2); ?>%</td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['minamount']; ?></td>
                                    <td style="text-align: center;width: 60px;"><?php echo $v['discount']; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['clearpayment']; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $v['noclearpayment']; ?></td>
                                    <td style="text-align: center;width: 80px;"><span data-qid="<?php echo $v['Id'] ?>" class="glyphicon glyphicon-align-justify invoice1"></span></td>

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

        <!-- clearing end -->


    </div>
<!--今日开价结果-->
	<div class="">

		<div class="clearing-status">

			<div class="table-detail">
				<div class="table-body" style="padding:25px;">
					<div class="sub-title">
						<h4>当日结果</h4>
						<select class="cleartype" name="cleartype">
							<?php foreach($cleartype_option as $key=>$item): ?>
							<option value="<?php echo $key ?>" <?php echo ($key==$cleartype)?'selected':'' ?>><?php echo $item ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="result-today">
						<table width="80%">
							<tr>
								<td class="title">折扣：</td>
								<td><?php echo ($results['Discount']==null || $results['Discount']=="")?0:$results['Discount']; ?></td>
								<td class="title">清算应收：</td>
								<td><?php echo ($results['ClearAmount']==null || $results['ClearAmount']=="")?0:$results['ClearAmount']; ?></td>
								<td class="title">发票明细：</td>
								<td width="100"><span class="glyphicon glyphicon-align-justify invoice2"></span></td>
								<td class="title">未清算应收：</td>
								<td><?php echo ($results['NoClearAmount']==null || $results['NoClearAmount']=="")?0:$results['NoClearAmount']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- clearing end -->


	</div>
    <!--  main content end -->



	<!--开价记录发票模板-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">发票明细</h4>
				</div>
				<div class="modal-body">
					<div class="loading" style="width:100%;text-align: center"><img style="width: 150px;" src="/assets/images/logo_pre.gif" /></div>
					<table id="myTable3" style="display: none;" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:120px;"><span>供应商</span></th>
							<th style="text-align: center;width:120px;"><span>供应商Code</span></th>
							<th style="text-align: center;width: 100px;"><span>发票编号</span></th>
							<th style="text-align: center;width: 120px;"><span>开票日期</span></th>
							<th style="text-align: center;width: 80px;"><span>原支付日期</span></th>
							<th style="text-align: center;width: 80px;"><span>发票金额</span></th>
							<th style="text-align: center;width: 80px;"><span>是否清算</span></th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<!--当日结果发票模板-->
	<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">发票明细</h4>
				</div>
				<div class="modal-body">
					<div class="loading" style="width:100%;text-align: center"><img style="width: 150px;" src="/assets/images/logo_pre.gif" /></div>
					<table id="myTable4" style="display: none;" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:120px;"><span>供应商</span></th>
							<th style="text-align: center;width:120px;"><span>供应商Code</span></th>
							<th style="text-align: center;width: 100px;"><span>发票编号</span></th>
							<th style="text-align: center;width: 120px;"><span>开票日期</span></th>
							<th style="text-align: center;width: 80px;"><span>原支付日期</span></th>
							<th style="text-align: center;width: 80px;"><span>发票金额</span></th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>




<script type="text/javascript">

    var selectedCount = 0;
    var table ;

    $(document).ready(function(){
    	var colIdx;
        var lastIdx = null;	  

    	  //<!-- 配置 datatable 开始-->
	     table = $('.data-box').DataTable({
           bStateSave:true,
           bFiltered:false,
           info:true,
           ordering:false,
           searching:false,
           bLengthChange: true,
           paging:true,
  	     });

  	   	//<!-- 配置 datatable 结束-->
    	 $('.data-box tbody')
          .on( 'mouseover', 'td', function () {

              if(table.cell(this).index() != undefined ){
                  colIdx = table.cell(this).index().column;
                  if ( colIdx !== lastIdx && colIdx > 0 ) {
                      $(table.cells().nodes() ).removeClass( 'highlight' );
                      $(table.column( colIdx ).nodes() ).addClass( 'highlight' );
                  }
              }
          } )
          .on( 'mouseleave', function () {

      	    $(table.cells().nodes() ).removeClass( 'highlight' );

          })
          .on( 'click', 'tr', function () {
           	if($(this).hasClass('normal')){
                   $(this).toggleClass('selected');
                   $('#'+$(this).attr('id').replace('tr_','chk_')).prop('checked',$(this).hasClass('selected'));

                   if($(this).hasClass('selected') ){
                       selectedCount ++ ;
                   }else{
               	       selectedCount -- ;

                   	$('#myTable .check-all').each(function(){
           	  	    	 if($(this).is(':checked'))
           	  	  	    	 $(this).prop('checked',false);
           	    	 })
                   }
           	}
          });


    $('#filter-date').change(function(){
        $.select_vendor();
		});

    $('#select-all').click(function(){
        $.select_vendor();
		});

		$('.cleartype').change(function(){
				$.select_vendor();
		});

    $('#vendorcode').change(function(){
        $.select_vendor();
		});

    $.select_vendor = function(){
        var code = $('input[name="cashpoolcode"]').val();
        var id = $('input[name="cashpoolid"]').val();
        var filter_date = $('#filter-date').val();
        var select_vendor = $('#vendorcode').find("option:selected").val();
        var cleartype = $('.cleartype').find("option:selected").val();
        var url = '/supplier/market/detail/'+code+'/'+id+'/'+filter_date;
        if($('#select-all').is(':checked')){
            url+='/all';
        }else{
            url+='/'+select_vendor;
				}
				url+='/'+cleartype;

        window.location.href = url;
		}

		$('.invoice1').click(function(){

        $('#myModal').modal({
            keyboard:false
        });
				$('#myTable3').css('display','none');
				$('.loading').css('display','block');
        var code = $('input[name="cashpoolcode"]').val();
		    $.get('/supplier/market/invoice/'+code+'/'+$(this).data('qid'),function(data){
		        var tbody = "";
		        for(var i=0;i<data.length;i++){
		            var IsAwarded = "清算";
		            if(data[i].IsAwarded=="0") IsAwarded="未清算"
		            tbody +='<tr>';
                tbody+='<td>'+data[i].supplier+'</td>'+
                    '<td>'+data[i].vendorcode+'</td>'+
                    '<td>'+data[i].InvoiceNo+'</td>'+
                    '<td>'+data[i].InvoiceDate+'</td>'+
                    '<td>'+data[i].EstPaydate+'</td>'+
                    '<td>'+data[i].InvoiceAmount*1+'</td>'+
                    '<td>'+IsAwarded+'</td>';
                tbody +='</tr>';
						}
						$('#myTable3 tbody').html(tbody);
						$('#myTable3').css('display','table');
            $('.loading').css('display','none');
				});
		});

    $('.invoice2').click(function(){
        $('#invoiceModal').modal({
            keyboard:false
        });
        $('#myTable4').css('display','none');
        $('.loading').css('display','block');
        var codeid = $('input[name="cashpoolid"]').val();
        var select_vendor = $('#vendorcode').find("option:selected").val();
        if($('#select-all').is(':checked')){
            select_vendor = "null";
        }
        var filter_date = $('#filter-date').val();
        $.get('/supplier/market/invoice_rs/'+codeid+'/'+select_vendor+'/'+filter_date,function(data){
            var tbody = "";
            for(var i=0;i<data.length;i++){
                var IsAwarded = "清算";
                if(data[i].IsAwarded=="0") IsAwarded="未清算"
                tbody +='<tr>';
                tbody+='<td>'+data[i].supplier+'</td>'+
                    '<td>'+data[i].vendorcode+'</td>'+
                    '<td>'+data[i].InvoiceNo+'</td>'+
                    '<td>'+data[i].InvoiceDate+'</td>'+
                    '<td>'+data[i].EstPaydate+'</td>'+
                    '<td>'+data[i].InvoiceAmount*1+'</td>'
                tbody +='</tr>';
            }
            $('#myTable4 tbody').html(tbody);
            $('#myTable4').css('display','table');
            $('.loading').css('display','none');
        });
		});

   });

</script>




<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>