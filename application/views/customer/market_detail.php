<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'customer/__lefter.php'; ?>

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
	</style>

	<div class="clearing-status market-title">
		<h3>市场名称：<?php echo (isset($info)&&is_array($info))?$info[0]['buyer']:'' ?></h3>
		<div class="title-filter">
			<input type="date" id="filter-date" value="<?php echo $querydate ?>">
			<input type="hidden" name="cashpoolcode" value="<?php echo $cashpoolcode ?>"/>
			<input type="hidden" name="cashpoolid" value="<?php echo $cashpoolid ?>"/>
		</div>
	</div>
	<!-- main content start -->
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
					<table id="myTable2" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width: 80px;"><span>折扣</span></th>
							<th style="text-align: center;width:120px;"><span>清算应收</span></th>
							<th style="text-align: center;width: 100px;"><span>未清算应收</span></th>
							<th style="text-align: center;width: 120px;"><span>发票明细</span></th>

						</tr>
						</thead>
						<tbody>
            <?php
            if( isset($results) && is_array($results) ) {
                foreach($results as $v){
                    ?>
									<tr>
										<td style="text-align: center;width: 80px;"><?php echo ($v['Discount']==null || $v['Discount']=="")?0:$v['Discount']; ?></td>
										<td style="text-align: center;width: 120px;"><?php echo ($v['ClearAmount']==null || $v['ClearAmount']=="")?0:$v['ClearAmount']; ?>  </td>
										<td style="text-align: center;width: 80px;"><?php echo ($v['NoClearAmount']==null || $v['NoClearAmount']=="")?0:$v['NoClearAmount']; ?></td>
										<td style="text-align: center;width: 80px;"><span class="glyphicon glyphicon-align-justify invoice2"></span></td>

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
	<!--  main content end -->


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
          table2 = $('#myTable2').DataTable({
              bStateSave:true,
              bFiltered:false,
              info:true,
              ordering:false,
              searching:false,
              bLengthChange: true,
              paging:true,
          });

          table4 = $('#myTable4').DataTable({
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

                  if(table2.cell(this).index() != undefined ){
                      colIdx = table2.cell(this).index().column;
                      if ( colIdx !== lastIdx && colIdx > 0 ) {
                          $(table2.cells().nodes() ).removeClass( 'highlight' );
                          $(table2.column( colIdx ).nodes() ).addClass( 'highlight' );
                      }
                  }
              } )
              .on( 'mouseleave', function () {

                  $(table2.cells().nodes() ).removeClass( 'highlight' );

              });


          $('#filter-date').change(function(){
              $.select_vendor();
          });
          $('.cleartype').change(function(){
              $.select_vendor();
          });
          $.select_vendor = function(){
              var code = $('input[name="cashpoolcode"]').val();
              var id = $('input[name="cashpoolid"]').val();
              var filter_date = $('#filter-date').val();
              var cleartype = $('.cleartype').find("option:selected").val();
              var url = '/customer/market/detail/'+code+'/'+id+'/'+filter_date;
              url+='/'+cleartype;

              window.location.href = url;
          }



          $('.invoice2').click(function(){
              $('#invoiceModal').modal({
                  keyboard:false
              });
              $('#myTable4').css('display','none');
              $('.loading').css('display','block');
              var codeid = $('input[name="cashpoolid"]').val();
              var filter_date = $('#filter-date').val();
              $.get('/customer/market/invoice_rs/'+codeid+'/'+filter_date,function(data){
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
                  table4.destroy();
                  table4 = $('#myTable4').DataTable({
                      stateSave:true,
                      bFiltered:false,
                      info:false,
                      ordering:false,
                      searching:false,
                      bLengthChange: false,
                      paging:false,
                  });
              });
          });

      });

	</script>




<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>